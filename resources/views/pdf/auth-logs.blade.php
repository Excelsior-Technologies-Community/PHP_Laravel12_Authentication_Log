<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth Log</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: white;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        hr {
            margin: 15px 0;
            border: none;
            border-top: 1px solid #ccc;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            font-size: 13px;
        }
        
        th {
            background: #f0f0f0;
        }
        
        .btn {
            display: inline-block;
            padding: 5px 10px;
            font-size: 12px;
            border: 1px solid #999;
            background: #fff;
            cursor: pointer;
            text-decoration: none;
            color: #000;
        }
        
        .btn:hover {
            background: #f0f0f0;
        }
        
        .btn-red {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }
        
        .btn-green {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }
        
        .btn-blue {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .stats {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .stat {
            border: 1px solid #ccc;
            padding: 10px;
            min-width: 120px;
            background: #fafafa;
        }
        
        .stat .num {
            font-size: 24px;
            font-weight: bold;
        }
        
        .stat .label {
            font-size: 12px;
            color: #666;
        }
        
        .filter-row {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            background: #fafafa;
        }
        
        .filter-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        
        .field {
            display: flex;
            flex-direction: column;
        }
        
        .field label {
            font-size: 11px;
            margin-bottom: 3px;
        }
        
        .field input, .field select {
            padding: 5px 8px;
            border: 1px solid #ccc;
            font-size: 12px;
        }
        
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #bee5eb;
            background: #d1ecf1;
            font-size: 13px;
        }
        
        .nav {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        
        .nav a, .nav button {
            margin-right: 10px;
        }
        
        .success-badge {
            background: #d4edda;
            padding: 2px 6px;
            font-size: 11px;
        }
        
        .failed-badge {
            background: #f8d7da;
            padding: 2px 6px;
            font-size: 11px;
        }
        
        .pagination {
            margin-top: 15px;
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        
        .pagination a, .pagination span {
            padding: 5px 10px;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #000;
            font-size: 12px;
        }
        
        .pagination .active span {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .empty {
            text-align: center;
            padding: 30px;
            color: #999;
        }
        
        @media (max-width: 700px) {
            .filter-form {
                flex-direction: column;
            }
            .stats {
                flex-direction: column;
            }
            th, td {
                font-size: 11px;
                padding: 5px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Simple Header -->
    <h2>Authentication Log</h2>
    
    <!-- Navigation -->
    <div class="nav">
        <a href="{{ route('dashboard') }}" class="btn">Dashboard</a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn">Logout</button>
        </form>
    </div>
    
    <!-- Stats -->
    <div class="stats">
        <div class="stat">
            <div class="num">{{ $stats['total_logins'] }}</div>
            <div class="label">Total Logins</div>
        </div>
        <div class="stat">
            <div class="num">{{ $stats['unique_ips'] }}</div>
            <div class="label">Unique IPs</div>
        </div>
        <div class="stat">
            <div class="num">{{ $stats['logins_this_week'] }}</div>
            <div class="label">Last 7 Days</div>
        </div>
        <div class="stat">
            <div class="num">{{ $stats['devices'] }}</div>
            <div class="label">Devices</div>
        </div>
    </div>
    
    <!-- Last Login Alert -->
    @if($stats['last_login'])
        <div class="alert">
            Last login: {{ $stats['last_login']->login_at->diffForHumans() }} from IP {{ $stats['last_login']->ip_address }}
        </div>
    @endif
    
    <!-- Filter Section -->
    <div class="filter-row">
        <form method="GET" action="{{ route('auth-log') }}" class="filter-form">
            <div class="field">
                <label>Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="field">
                <label>Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="field">
                <label>IP Address</label>
                <input type="text" name="ip_address" placeholder="IP" value="{{ request('ip_address') }}">
            </div>
            <div class="field">
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    <option value="successful" {{ request('status')=='successful' ? 'selected' : '' }}>Success</option>
                    <option value="failed" {{ request('status')=='failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="field">
                <button type="submit" class="btn btn-blue">Apply</button>
            </div>
            <div class="field">
                <a href="{{ route('auth-log') }}" class="btn">Reset</a>
            </div>
            <div class="field">
                <a href="{{ route('auth-log.export-pdf') }}" class="btn btn-red">PDF</a>
            </div>
            <div class="field">
                <a href="{{ route('auth-log.export', request()->all()) }}" class="btn btn-green">Excel</a>
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div style="margin-top: 20px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <strong>Login History</strong>
            <button onclick="confirmClearAll()" class="btn btn-red">Clear All</button>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IP</th>
                    <th>Location</th>
                    <th>Device</th>
                    <th>Login Time</th>
                    <th>Logout</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>
                            {{ $log->ip_address }}
                            <button class="btn" style="font-size: 10px; padding: 2px 5px;" onclick="getIPInfo('{{ $log->ip_address }}')">Info</button>
                        </td>
                        <td>{{ $log->location ?? '-' }}</td>
                        <td>{{ $log->user_agent ? substr($log->user_agent, 0, 40) : '-' }}</td>
                        <td>{{ $log->login_at ? $log->login_at->format('Y-m-d H:i:s') : '-' }}</td>
                        <td>{{ $log->logout_at ? $log->logout_at->format('Y-m-d H:i:s') : 'Active' }}</td>
                        <td>
                            @if($log->login_failed_at)
                                <span class="failed-badge">Failed</span>
                            @else
                                <span class="success-badge">Success</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn" style="font-size: 10px; padding: 2px 5px;" onclick="confirmDelete({{ $log->id }})">Del</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty">No logs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($logs->hasPages())
            <div class="pagination">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function confirmDelete(id) {
    if(confirm('Delete this log?')) {
        window.location.href = '/auth-log/' + id + '/delete';
    }
}

function confirmClearAll() {
    if(confirm('Delete ALL logs? This cannot be undone.')) {
        window.location.href = '{{ route("auth-log.clear") }}';
    }
}

function getIPInfo(ip) {
    fetch('https://ipapi.co/' + ip + '/json/')
        .then(res => res.json())
        .then(data => {
            alert('IP: ' + ip + '\nCountry: ' + (data.country_name || '-') + '\nCity: ' + (data.city || '-'));
        })
        .catch(() => alert('Error fetching IP info'));
}
</script>

</body>
</html>