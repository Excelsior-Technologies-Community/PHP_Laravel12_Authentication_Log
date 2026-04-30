<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Log</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h1 class="mb-4 text-center">🔐 Authentication Log</h1>

    {{-- FILTER FORM --}}
    <form method="GET" class="row mb-4">

        {{-- ID SEARCH --}}
        <div class="col-md-3">
            <input type="number" name="id" value="{{ request('id') }}" class="form-control" placeholder="Search by ID">
        </div>

        <div class="col-md-3">
            <input type="text" name="ip" value="{{ request('ip') }}" class="form-control" placeholder="Search by IP">
        </div>

        <div class="col-md-3">
            <input type="date" name="date" value="{{ request('date') }}" class="form-control">
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ url('/auth-log') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    {{-- FILTER LOGIC --}}
    @php
        $logs = collect(auth()->user()->authentications);

        // Filter by ID
        if(request('id')) {
            $logs = $logs->filter(fn($log) => $log->id == request('id'));
        }

        // Filter by IP
        if(request('ip')) {
            $logs = $logs->filter(fn($log) => str_contains($log->ip_address, request('ip')));
        }

        // Filter by Date
        if(request('date')) {
            $logs = $logs->filter(fn($log) =>
                \Carbon\Carbon::parse($log->login_at)->toDateString() == request('date')
            );
        }

        $ips = $logs->pluck('ip_address')->unique();
    @endphp

    {{-- Suspicious Alert --}}
    @if($ips->count() > 1)
        <div class="alert alert-danger">
            ⚠️ Suspicious Activity: Multiple IP addresses detected!
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-bordered shadow">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>User Type</th>
                    <th>IP Address</th>
                    <th>Browser/Device</th>
                    <th>Login At</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($logs as $log)
                    <tr class="text-center {{ !$log->logout_at ? 'table-success' : '' }}">

                        <td>{{ $log->id }}</td>

                        <td>{{ $log->authenticatable_id }}</td>

                        <td>
                            <span class="badge bg-primary">
                                {{ class_basename($log->authenticatable_type) }}
                            </span>
                        </td>

                        <td>{{ $log->ip_address }}</td>

                        <td>{{ $log->user_agent ?? 'Unknown' }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($log->login_at)->format('d M Y, h:i A') }}
                        </td>

                        <td>
                            @if($log->logout_at)
                                <span class="badge bg-secondary">Logged Out</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No login records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>