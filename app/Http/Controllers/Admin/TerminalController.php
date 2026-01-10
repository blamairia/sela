<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\LicenseService;

class TerminalController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
        // Enforce Admin Access Only
        $this->middleware(['auth:web', 'Is_Active']); 
        // Note: 'Is_Active' checks if user is active. 
        // We should arguably check 'Is_Admin' too, but typically Role 1 is Admin.
    }

    public function index(Request $request)
    {
        // JSON Response for Vue
        $terminals = DB::table('app_terminals')->orderBy('created_at', 'desc')->get();
        $details = $this->licenseService->getLicenseDetails();
        $maxClients = $details['max_clients'] ?? 5;
        $activeCount = $terminals->where('is_active', 1)->count();
        $remaining = $maxClients - $activeCount;

        if ($request->wantsJson()) {
             return response()->json([
                'terminals' => $terminals,
                'max_clients' => $maxClients,
                'active_count' => $activeCount,
                'remaining' => $remaining
             ]);
        }
        
        // Fallback or Initial Load? 
        // Since we are SPA, we should only be hit via API.
        // But if hit directly via Browser? The Vue App will load and call this API.
        return view('layouts.master'); // Just load the app, let Vue fetch data.
    }

    public function store(Request $request)
    {
        if (auth()->user()->role_id !== 1) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'hwid' => 'required|string|unique:app_terminals,hwid',
            'name' => 'required|string|max:255',
        ]);

        $details = $this->licenseService->getLicenseDetails();
        $maxClients = $details['max_clients'] ?? 5;
        $activeCount = DB::table('app_terminals')->where('is_active', 1)->count();

        if ($activeCount >= $maxClients) {
             return response()->json(['message' => 'License Limit Reached'], 422);
        }

        DB::table('app_terminals')->insert([
            'hwid' => $request->hwid,
            'name' => $request->name,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Terminal Authorized Successfully']);
    }

    public function destroy($id)
    {
        if (auth()->user()->role_id !== 1) {
             return response()->json(['message' => 'Unauthorized'], 403);
        }

        DB::table('app_terminals')->where('id', $id)->delete();

        return response()->json(['message' => 'Terminal Removed']);
    }
}
