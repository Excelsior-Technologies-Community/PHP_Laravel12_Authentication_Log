<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $logs = DB::table('authentication_log')
            ->join('users', 'authentication_log.authenticatable_id', '=', 'users.id')
            ->select('authentication_log.*', 'users.name as user_name', 'users.email')
            ->orderBy('login_at', 'desc')
            ->paginate(15);

        return view('admin.dashboard', compact('logs'));
    }

    public function chartData()
    {
        $successful = DB::table('authentication_log')->where('login_successful', true)->count();
        $failed = DB::table('authentication_log')->where('login_successful', false)->count();

        return response()->json([
            'successful' => $successful,
            'failed' => $failed
        ]);
    }
}