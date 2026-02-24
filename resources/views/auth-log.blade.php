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
    <h1 class="mb-4">Authentication Log</h1>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>User Type</th>
                <th>IP Address</th>
                <th>Browser/Device</th>
                <th>Login At</th>
            </tr>
        </thead>
        <tbody>
            @forelse(auth()->user()->authentications as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->authenticatable_id }}</td>
                    <td>{{ $log->authenticatable_type }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->user_agent }}</td>
                    <td>{{ $log->login_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No login records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>