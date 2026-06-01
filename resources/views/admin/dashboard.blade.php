<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard - All Users Logs</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Login Statistics (AJAX Chart)</h3>
                    <canvas id="loginChart" width="400" height="200"></canvas>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Import Data (Dropzone UI)</h3>
                    <form action="#" class="dropzone rounded-lg border-dashed border-2 border-gray-300 bg-gray-50 flex items-center justify-center h-48" id="logDropzone">
                        <div class="dz-message text-gray-500" data-dz-message>
                            <span>Drag and drop CSV files here to import legacy logs</span>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <h3 class="text-lg font-bold mb-4">All System Logs</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($logs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $log->user_name }} ({{ $log->email }})</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $log->ip_address }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $log->login_successful ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $log->login_successful ? 'Success' : 'Failed' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($log->login_at)->format('Y-m-d H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("{{ route('admin.chartData') }}")
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('loginChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Successful Logins', 'Failed Logins'],
                            datasets: [{
                                data: [data.successful, data.failed],
                                backgroundColor: ['#10B981', '#EF4444'],
                                hoverOffset: 4
                            }]
                        }
                    });
                });
        });
    </script>
</x-app-layout>