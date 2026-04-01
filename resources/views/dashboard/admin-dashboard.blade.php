<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition"
                >
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome, {{ auth()->user()->name }}!</h2>
            <p class="text-gray-600">Admin control panel</p>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <!-- Admin Info Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="bg-red-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600">Administrator</p>
                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- System Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                    </div>
                    <svg class="w-12 h-12 text-blue-600 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 6a3 3 0 11-6 0 3 3 0 016 0zm5 2a2 2 0 11-4 0 2 2 0 014 0zm-1-2a3 3 0 11-6 0 3 3 0 016 0zm5 2a2 2 0 11-4 0 2 2 0 014 0zM9 19c-5 0-8-2-8-5v-3h2v3c0 1.717 2.305 3 6 3s6-1.283 6-3v-3h2v3c0 3-3 5-8 5z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Verified Users</p>
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::whereNotNull('email_verified_at')->count() }}</p>
                    </div>
                    <svg class="w-12 h-12 text-green-600 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Admin Users</p>
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
                    </div>
                    <svg class="w-12 h-12 text-purple-600 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM15.657 14.243a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM11 17a1 1 0 102 0v-1a1 1 0 10-2 0v1zM5.757 14.243a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM3 10a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.757 5.757a1 1 0 000-1.414L5.05 3.636a1 1 0 00-1.414 1.414l.707.707z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Water Level Sensor Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Water Level Monitoring</h3>
            <div id="sensor-container" class="space-y-4">
                <p class="text-gray-600">Loading sensor data...</p>
            </div>
        </div>

        <!-- Admin Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">System Management</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="bg-blue-50 hover:bg-blue-100 rounded-lg p-4 text-center transition">
                    <svg class="w-12 h-12 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m0 0v2m0-2a2 2 0 110 4m0-4a2 2 0 100 4m0-4v2m0-2h.01M12 14v4m0-4a2 2 0 110 4m0-4a2 2 0 100 4m0-4v2m0-2h.01" />
                    </svg>
                    <h4 class="font-bold text-gray-900">Manage Users</h4>
                    <p class="text-sm text-gray-600 mt-1">Add, edit, or remove users</p>
                </button>

                <button class="bg-green-50 hover:bg-green-100 rounded-lg p-4 text-center transition">
                    <svg class="w-12 h-12 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <h4 class="font-bold text-gray-900">Verification Settings</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage email verification</p>
                </button>

                <button class="bg-purple-50 hover:bg-purple-100 rounded-lg p-4 text-center transition">
                    <svg class="w-12 h-12 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M4.22 4.22a9 9 0 0113.56 0M3 12a9 9 0 0118 0m-9 9a9.001 9.001 0 01-8.95-8.95" />
                    </svg>
                    <h4 class="font-bold text-gray-900">System Logs</h4>
                    <p class="text-sm text-gray-600 mt-1">View security events</p>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Fetch latest sensor data
        async function loadSensorData() {
            try {
                const response = await fetch('/api/latest-sensor');
                const data = await response.json();
                
                const container = document.getElementById('sensor-container');
                container.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-600">
                            <p class="text-gray-600 text-sm mb-1">Current Reading</p>
                            <p class="text-3xl font-bold text-blue-600">${data.sensor || 0}%</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-600">
                            <p class="text-gray-600 text-sm mb-1">Green Threshold</p>
                            <p class="text-3xl font-bold text-green-600">${data.green || 0}%</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-600">
                            <p class="text-gray-600 text-sm mb-1">Yellow Threshold</p>
                            <p class="text-3xl font-bold text-yellow-600">${data.yellow || 0}%</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-600">
                            <p class="text-gray-600 text-sm mb-1">Red Threshold</p>
                            <p class="text-3xl font-bold text-red-600">${data.red || 0}%</p>
                        </div>
                    </div>
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Last updated: <span id="update-time"></span></p>
                    </div>
                `;
                document.getElementById('update-time').textContent = new Date().toLocaleTimeString();
            } catch (error) {
                console.error('Error loading sensor data:', error);
                document.getElementById('sensor-container').innerHTML = 
                    '<p class="text-red-600">Failed to load sensor data</p>';
            }
        }

        // Load sensor data on page load and every 10 seconds for real-time monitoring
        loadSensorData();
        setInterval(loadSensorData, 10000);
    </script>
</body>
</html>
