<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->last_active = Carbon::createFromTimestamp($session->last_activity)->diffForHumans();
                return $session;
            });

        return view('sessions.index', compact('sessions'));
    }

    public function destroy($id)
    {
        DB::table('sessions')->where('id', $id)->delete();
        
        return back()->with('success', 'Device logged out successfully.');
    }
}