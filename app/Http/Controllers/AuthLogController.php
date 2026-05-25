<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AuthenticationLogExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthLogController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->authentications();
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('login_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('login_at', '<=', $request->date_to);
        }
        
        // Filter by IP address
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'LIKE', '%' . $request->ip_address . '%');
        }
        
        // Filter by login status
        if ($request->filled('status')) {
            if ($request->status === 'successful') {
                $query->whereNotNull('login_at');
            } elseif ($request->status === 'failed') {
                $query->whereNotNull('login_failed_at');
            }
        }
        
        $logs = $query->paginate(15);
        
        // Get statistics
        $stats = [
            'total_logins' => auth()->user()->authentications()->count(),
            'unique_ips' => auth()->user()->authentications()->distinct('ip_address')->count('ip_address'),
            'last_login' => auth()->user()->authentications()->latest('login_at')->first(),
            'logins_this_week' => auth()->user()->authentications()
                ->where('login_at', '>=', Carbon::now()->subDays(7))
                ->count(),
            'devices' => auth()->user()->authentications()
                ->distinct('user_agent')
                ->count('user_agent')
        ];
        
        return view('auth-log', compact('logs', 'stats'));
    }
    
    public function export(Request $request)
    {
        return Excel::download(new AuthenticationLogExport($request), 'authentication-logs.xlsx');
    }
    
    public function exportPdf()
    {
        $logs = auth()->user()->authentications()->get();
        $pdf = Pdf::loadView('pdf.auth-logs', compact('logs'));
        return $pdf->download('authentication-logs.pdf');
    }
    
    public function statistics()
    {
        $logs = auth()->user()->authentications()
            ->select(DB::raw('DATE(login_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->limit(30)
            ->get();
            
        $ipData = auth()->user()->authentications()
            ->select('ip_address', DB::raw('count(*) as count'))
            ->groupBy('ip_address')
            ->orderBy('count', 'DESC')
            ->limit(10)
            ->get();
            
        return response()->json([
            'daily_logins' => $logs,
            'top_ips' => $ipData
        ]);
    }
    
    public function destroy($id)
    {
        $log = auth()->user()->authentications()->findOrFail($id);
        $log->delete();
        
        return redirect()->route('auth-log')->with('success', 'Log entry deleted successfully!');
    }
    
    public function clearAll()
    {
        auth()->user()->authentications()->delete();
        
        return redirect()->route('auth-log')->with('success', 'All logs cleared successfully!');
    }
}