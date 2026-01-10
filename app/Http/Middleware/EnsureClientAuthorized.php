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
        // 0. Exempt License Activation/Check Routes
        if ($request->is('license/*') || $request->is('api/license/*')) {
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

        // 2. Client Check (Only if we want to restrict specific terminals)
        // If the request comes from the server itself (localhost), usually we allow it.
        $clientIP = $request->ip();
        if ($clientIP === '127.0.0.1' || $clientIP === '::1') {
            // Localhost Admin - Valid
            return $next($request);
        }

        // Check for Client Hardware ID header
        $clientHWID = $request->header('X-Sela-HWID');

        if (!$clientHWID) {
            // If it's a browser access from another machine without the custom header -> Block
            // Or maybe redirect to a "Download Client App" page
            return response()->json(['message' => 'Access Denied. Please use the Sela POS Client Application.'], 403);
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
