<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthLogController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->authentications();

        // Filter by ID
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        // Filter by IP
        if ($request->filled('ip')) {
            $query->where('ip_address', 'like', '%' . $request->ip . '%');
        }

        // Filter by Date
        if ($request->filled('date')) {
            $query->whereDate('login_at', $request->date);
        }

        // Paginated logs
        $logs = $query->latest()->paginate(10)->withQueryString();

        // 🚨 Suspicious IP detection
        $ips = (clone $query)->pluck('ip_address')->unique();

        return view('auth-log', compact('logs', 'ips'));
    }
}