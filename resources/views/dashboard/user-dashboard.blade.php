<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
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
            <p class="text-gray-600">This is your user dashboard</p>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 rounded-full p-4">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Profile Name</p>
                        <p class="text-xl font-bold text-gray-900">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Email Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 rounded-full p-4">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Email Address</p>
                        <p class="text-lg font-bold text-gray-900">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Role Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-purple-100 rounded-full p-4">
                        <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">User Role</p>
                        <p class="text-xl font-bold text-gray-900 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sensor Data Section -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Water Level Sensor</h3>
            <div id="sensor-container" class="space-y-4">
                <p class="text-gray-600">Loading sensor data...</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('auth.forgot-password') }}" class="bg-white hover:bg-gray-50 rounded-lg shadow p-6 text-center transition">
                    <svg class="w-12 h-12 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <h4 class="font-bold text-gray-900">Change Password</h4>
                    <p class="text-sm text-gray-600">Update your password</p>
                </a>

                <a href="{{ route('auth.login') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="bg-white hover:bg-gray-50 rounded-lg shadow p-6 text-center transition">
                    <svg class="w-12 h-12 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <h4 class="font-bold text-gray-900">Logout</h4>
                    <p class="text-sm text-gray-600">Sign out of your account</p>
                </a>
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
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm mb-1">Sensor Reading</p>
                            <p class="text-3xl font-bold text-blue-600">${data.sensor || 0}%</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm mb-1">Green Level</p>
                            <p class="text-3xl font-bold text-green-600">${data.green || 0}%</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm mb-1">Yellow Level</p>
                            <p class="text-3xl font-bold text-yellow-600">${data.yellow || 0}%</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm mb-1">Red Level</p>
                            <p class="text-3xl font-bold text-red-600">${data.red || 0}%</p>
                        </div>
                    </div>
                `;
            } catch (error) {
                console.error('Error loading sensor data:', error);
                document.getElementById('sensor-container').innerHTML = 
                    '<p class="text-red-600">Failed to load sensor data</p>';
            }
        }

        // Load sensor data on page load and every 30 seconds
        loadSensorData();
        setInterval(loadSensorData, 30000);
    </script>
</body>
</html>
