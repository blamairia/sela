<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TerminalAuthController extends Controller
{
    /**
     * Handshake logic for Desktop Clients (Tauri/Electron).
     * URL: /terminal/login?hwid=XYZ&secret=ABC
     */
    public function handshake(Request $request)
    {
        // 1. Validate Secret (Simple defense against casual browser access)
        // In production, this secret should be in .env or hardcoded in PHP if encoded.
        // Ideally, use a Time-based Token or Hash, but for now a static secret suffices.
        $serverSecret = env('TERMINAL_SECRET', 'SELA_SECURE_HANDSHAKE_2026'); // Fallback for dev

        if ($request->input('secret') !== $serverSecret) {
            abort(403, 'Invalid Handshake Secret');
        }

        $hwid = $request->input('hwid');

        if (!$hwid) {
            abort(400, 'Missing HWID');
        }

        // 2. Check if Terminal is Authorized in DB
        // We do strictly check DB here. If not in DB, we don't login.
        $terminal = DB::table('app_terminals')->where('hwid', $hwid)->first();

        if (!$terminal) {
             // Optional: Auto-register pending terminals? 
             // For "Bulletproof", NO. Admin must manually add HWID first.
             return response()->json([
                 'error' => 'Terminal Not Registered',
                 'hwid' => $hwid,
                 'message' => 'Please ask Administrator to add this Terminal ID.'
             ], 403);
        }

        if (!$terminal->is_active) {
            return response()->json(['error' => 'Terminal is Banned/Inactive'], 403);
        }

        // 3. Login to Session
        session(['terminal_hwid' => $hwid]);
        
        // Log last login
        DB::table('app_terminals')->where('id', $terminal->id)->update([
            'last_seen_at' => now(),
            'last_ip' => $request->ip()
        ]);

        // 4. Redirect to Dashboard
        return redirect('/dashboard');
    }
}
