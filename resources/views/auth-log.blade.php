<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Log - Security Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            border-radius: 15px;
            transition: transform 0.3s;
            cursor: pointer;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .device-badge {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .login-success {
            border-left: 4px solid #28a745;
        }
        .login-failed {
            border-left: 4px solid #dc3545;
        }
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        #loginChart {
            max-height: 300px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-shield-alt text-primary"></i> Authentication Security Log</h1>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-chart-line"></i> Total Logins</h5>
                    <h2 class="mb-0">{{ $stats['total_logins'] }}</h2>
                    <small>All time</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-globe"></i> Unique IPs</h5>
                    <h2 class="mb-0">{{ $stats['unique_ips'] }}</h2>
                    <small>Different locations</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-calendar-week"></i> Last 7 Days</h5>
                    <h2 class="mb-0">{{ $stats['logins_this_week'] }}</h2>
                    <small>Recent activity</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-laptop"></i> Devices</h5>
                    <h2 class="mb-0">{{ $stats['devices'] }}</h2>
                    <small>Different devices</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Last Login Alert -->
    @if($stats['last_login'])
        <div class="alert alert-info mb-4">
            <i class="fas fa-clock"></i> Last login: 
            {{ $stats['last_login']->login_at->diffForHumans() }} 
            from IP: <strong>{{ $stats['last_login']->ip_address }}</strong>
            @if($stats['last_login']->location)
                ({{ $stats['last_login']->location }})
            @endif
        </div>
    @endif
    
    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-bar"></i> Login Activity (Last 30 Days)
                </div>
                <div class="card-body">
                    <canvas id="loginChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-pie"></i> Device Distribution
                </div>
                <div class="card-body">
                    <canvas id="deviceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="filter-section">
        <h5><i class="fas fa-filter"></i> Filter Logs</h5>
        <form method="GET" action="{{ route('auth-log') }}" class="row g-3">
            <div class="col-md-3">
                <label>Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label>Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3">
                <label>IP Address</label>
                <input type="text" name="ip_address" class="form-control" placeholder="Search IP..." value="{{ request('ip_address') }}">
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">All</option>
                    <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Successful</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
                <a href="{{ route('auth-log') }}" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Reset
                </a>
                <a href="{{ route('auth-log.export', request()->all()) }}" class="btn btn-success float-end">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </a>
                <a href="{{ route('auth-log.export-pdf') }}" class="btn btn-danger float-end me-2">
                    <i class="fas fa-file-pdf"></i> Export to PDF
                </a>
            </div>
        </form>
    </div>
    
    <!-- Logs Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-history"></i> Authentication History</h5>
            <button onclick="confirmClearAll()" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> Clear All Logs
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>IP Address</th>
                            <th>Location</th>
                            <th>Device/Browser</th>
                            <th>Login Time</th>
                            <th>Logout Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="{{ $log->login_failed_at ? 'login-failed' : 'login-success' }}">
                                <td>{{ $log->id }}</td>
                                <td>
                                    {{ $log->ip_address }}
                                    <button class="btn btn-sm btn-outline-info" onclick="getIPInfo('{{ $log->ip_address }}')">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </td>
                                <td>{{ $log->location ?? 'Unknown' }}</td>
                                <td>
                                    <span class="device-badge bg-secondary text-white">
                                        {{ $log->user_agent ? substr($log->user_agent, 0, 50) : 'Unknown' }}
                                    </span>
                                </td>
                                <td>{{ $log->login_at ? $log->login_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                <td>{{ $log->logout_at ? $log->logout_at->format('Y-m-d H:i:s') : 'Still logged in' }}</td>
                                <td>
                                    @if($log->login_failed_at)
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-success">Success</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $log->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No authentication logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Charts
document.addEventListener('DOMContentLoaded', function() {
    // Fetch chart data
    fetch('{{ route("auth-log.statistics") }}')
        .then(response => response.json())
        .then(data => {
            // Login Chart
            const ctx = document.getElementById('loginChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.daily_logins.map(item => item.date),
                    datasets: [{
                        label: 'Number of Logins',
                        data: data.daily_logins.map(item => item.count),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
            
            // Device Chart
            const deviceCtx = document.getElementById('deviceChart').getContext('2d');
            new Chart(deviceCtx, {
                type: 'pie',
                data: {
                    labels: data.top_ips.map(item => item.ip_address),
                    datasets: [{
                        data: data.top_ips.map(item => item.count),
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FF6384', '#C9CBCF'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
});

// Delete confirmation
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/auth-log/' + id + '/delete';
        }
    });
}

// Clear all confirmation
function confirmClearAll() {
    Swal.fire({
        title: 'Are you absolutely sure?',
        text: "This will delete ALL your authentication logs!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete everything!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ route("auth-log.clear") }}';
        }
    });
}

// Get IP Information
function getIPInfo(ip) {
    fetch(`https://ipapi.co/${ip}/json/`)
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                title: `IP Information: ${ip}`,
                html: `
                    <strong>Country:</strong> ${data.country_name}<br>
                    <strong>City:</strong> ${data.city}<br>
                    <strong>Region:</strong> ${data.region}<br>
                    <strong>ISP:</strong> ${data.org}<br>
                    <strong>Postal Code:</strong> ${data.postal}
                `,
                icon: 'info'
            });
        })
        .catch(() => {
            Swal.fire('Error', 'Could not fetch IP information', 'error');
        });
}
</script>
</body>
</html>