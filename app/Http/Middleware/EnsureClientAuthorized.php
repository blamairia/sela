<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\LicenseService;
use Illuminate\Support\Facades\DB;

class EnsureClientAuthorized
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    public function handle($request, Closure $next)
    {
        // 0. Exempt License Activation/Check Routes AND Terminal Login
        if ($request->is('license/*') || $request->is('api/license/*') || $request->is('terminal/login')) {
            return $next($request);
        }

        // 0.5 Allow Authenticated Web Users (Admins/Staff accessing via Browser)
        if (auth('web')->check()) {
            return $next($request);
        }

        // 1. GLOBAL CHECK: Is the Server License valid?
        // We might want to cache this result to avoid checking disk every request
        $licenseStatus = $this->licenseService->verifyLicense();
        
        if ($licenseStatus !== true) {
            // Return JSON if expecting JSON, otherwise a view
            if ($request->expectsJson()) {
                 return response()->json(['message' => 'Server License Error: ' . $licenseStatus], 403);
            }
            abort(403, 'Server License Error: ' . $licenseStatus);
        }

        // 1.5 CHECK MAX CLIENTS LIMIT (Security feature)
        // This prevents Admin from manually inserting rows in DB to bypass limits
        $details = $this->licenseService->getLicenseDetails();
        $maxClients = $details['max_clients'] ?? 5; // Default safe limit
        $activeCount = DB::table('app_terminals')->where('is_active', 1)->count();
        
        // If we have more active terminals than allowed, we need to block access.
        // But we must be careful: don't block the Server (Localhost) or the Admin.
        // The check applies specifically when we are about to authorize a REMOTE TERMINAL.
        
        // Note: We'll defer the Blocking to the specific Client Check block to allow localhost.

        // 2. Client Check (Only if we want to restrict specific terminals)
        // If the request comes from the server itself (localhost), usually we allow it.
        $clientIP = $request->ip();
        if ($clientIP === '127.0.0.1' || $clientIP === '::1') {
            // Localhost Admin - Valid
            return $next($request);
        }

        // 3. CHECK SESSION (For Browser/Tauri Clients that performed Handshake)
        if (session()->has('terminal_hwid')) {
            // Check License Limit BEFORE allowing session
            if ($activeCount > $maxClients) {
                 abort(403, "License Limit Exceeded ($activeCount/$maxClients). Contact Support.");
            }

            $sessionHWID = session('terminal_hwid');
            $terminal = DB::table('app_terminals')->where('hwid', $sessionHWID)->first();

            if ($terminal && $terminal->is_active) {
                // Update 'last_seen'
                DB::table('app_terminals')->where('id', $terminal->id)->update([
                    'last_seen_at' => now(),
                    'last_ip' => $clientIP
                ]);
                return $next($request);
            } else {
                // Session invalid/banned - Clear it
                session()->forget('terminal_hwid');
            }
        }

        // 4. Check for Client Hardware ID header (Legacy/API Client)
        $clientHWID = $request->header('X-Sela-HWID');

        if (!$clientHWID) {
            // If it's a browser access from another machine without the custom header -> Block
            // Or maybe redirect to a "Download Client App" page
            return response()->json(['message' => 'Access Denied. Please use the Sela POS Client Application.'], 403);
        }

        // Check License Limit BEFORE allowing header access
        if ($activeCount > $maxClients) {
             return response()->json(['message' => "License Limit Exceeded ($activeCount/$maxClients). Contact Support."], 403);
        }

        // Check if this HWID is authorized in DB
        $terminal = DB::table('app_terminals')->where('hwid', $clientHWID)->first();

        if (!$terminal || !$terminal->is_active) {
            return response()->json(['message' => 'This POS Terminal is not authorized. ID: ' . $clientHWID], 403);
        }

        // Update 'last_seen' for the terminal
        DB::table('app_terminals')->where('id', $terminal->id)->update([
            'last_seen_at' => now(),
            'last_ip' => $clientIP
        ]);

        return $next($request);
    }
}
