<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Balance Change Logs</h1>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('balance_logs.index') }}" class="mb-6 bg-white p-4 shadow rounded">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">User Name</label>
                    <input type="text" name="name" id="name" value="{{ request('name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">User Email</label>
                    <input type="text" name="email" id="email" value="{{ request('email') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" id="date" value="{{ request('date') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filter</button>
                <a href="{{ route('balance_logs.index') }}" class="ml-2 text-blue-500 hover:underline">Clear Filters</a>
            </div>
        </form>

        <!-- Balance Logs Table -->
        <div class="bg-white shadow rounded overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Old Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">New Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Changed At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($logs as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $log->user ? $log->user->name . ' (' . $log->user->email . ')' : 'Unknown User' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($log->old_balance, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($log->new_balance, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $log->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No balance logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</body>
</html>
