<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class AuthenticationLogExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function collection()
    {
        $query = auth()->user()->authentications();
        
        if ($this->request->filled('date_from')) {
            $query->whereDate('login_at', '>=', $this->request->date_from);
        }
        
        if ($this->request->filled('date_to')) {
            $query->whereDate('login_at', '<=', $this->request->date_to);
        }
        
        return $query->get();
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'IP Address',
            'Browser/Device',
            'Login Time',
            'Logout Time',
            'Location'
        ];
    }
    
    public function map($log): array
    {
        return [
            $log->id,
            $log->ip_address,
            $log->user_agent,
            $log->login_at,
            $log->logout_at,
            $log->location ?? 'N/A'
        ];
    }
}