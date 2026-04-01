<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Water Level Monitoring</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: #333;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
        }

        .logout-btn {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: inherit;
            font-size: 14px;
        }

        .logout-btn:hover {
            background-color: white;
            color: #667eea;
        }

        .container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        .stat-label {
            font-size: 14px;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 15px;
        }

        .stat-unit {
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
        }

        .stat-trend {
            font-size: 12px;
            color: #4caf50;
            font-weight: 600;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: white;
            margin-bottom: 25px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .tank-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .tank-container {
            display: flex;
            align-items: center;
            gap: 60px;
            flex-wrap: wrap;
        }

        .tank-visual {
            position: relative;
            width: 180px;
            height: 360px;
            background-color: #e0e0e0;
            border: 6px solid #333;
            border-radius: 0 0 15px 15px;
            overflow: hidden;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .water {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: linear-gradient(180deg, #4da7db 0%, #2980b9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: bold;
            transition: height 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            height: 0%;
            overflow: hidden;
        }

        .water::before {
            content: "";
            position: absolute;
            top: -12px;
            left: 0;
            width: 200%;
            height: 30px;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 1200 60' preserveAspectRatio='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0,30 Q150,10 300,30 T600,30 T900,30 T1200,30' stroke='rgba(255,255,255,0.4)' stroke-width='2' fill='none'/%3E%3C/svg%3E");
            background-size: 600px 100%;
            background-repeat: repeat-x;
            animation: wavePrimary 3s linear infinite;
        }

        @keyframes wavePrimary {
            0% { background-position: 0 0; }
            100% { background-position: 600px 0; }
        }

        .lights-panel {
            display: flex;
            flex-direction: column;
            gap: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .light-indicator {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .light {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #ddd;
            border: 3px solid #999;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .light.active {
            box-shadow: 0 0 25px currentColor, inset 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .light.red.active {
            background-color: #ff4d4d;
        }

        .light.yellow.active {
            background-color: #ffeb3b;
        }

        .light.green.active {
            background-color: #4caf50;
        }

        .light-label {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            min-width: 120px;
        }

        .light-status {
            font-size: 13px;
            color: #999;
        }

        .readings-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .readings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .reading-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .reading-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .reading-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .reading-time {
            font-size: 11px;
            opacity: 0.8;
        }

        .table-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e0e0e0;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 700;
            color: #333;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .timestamp {
            color: #999;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-normal {
            background-color: #d4edda;
            color: #155724;
        }

        .status-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-critical {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }

            .navbar-menu {
                width: 100%;
                justify-content: space-between;
            }

            .container {
                padding: 0 15px;
            }

            .page-title {
                font-size: 24px;
                margin-bottom: 20px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .tank-container {
                flex-direction: column;
                gap: 30px;
                align-items: center;
            }

            .readings-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 10px 8px;
            }
        }

        .footer {
            text-align: center;
            padding: 30px;
            color: white;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-brand">Water Level Monitoring System</div>
        <div class="navbar-menu">
            <div class="user-info">
                <span class="user-name">Welcome, {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <h1 class="page-title">Dashboard</h1>

        <!-- Statistics Cards -->
        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-label">Current Water Level</div>
                <div class="stat-value" id="current-level">0</div>
                <div class="stat-unit">cm</div>
                <div class="stat-trend">Last updated: <span id="update-time">--:--</span></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Maximum Level (24h)</div>
                <div class="stat-value" id="max-level">0</div>
                <div class="stat-unit">cm</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Minimum Level (24h)</div>
                <div class="stat-value" id="min-level">0</div>
                <div class="stat-unit">cm</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Average Level (24h)</div>
                <div class="stat-value" id="avg-level">0</div>
                <div class="stat-unit">cm</div>
            </div>
        </div>

        <!-- Tank Visualization Section -->
        <div class="tank-section">
            <h2 class="section-title">Tank Visualization</h2>
            <div class="tank-container">
                <div>
                    <div class="tank-visual">
                        <div id="water" class="water">
                            <span id="sensor">0</span>
                        </div>
                    </div>
                    <p style="text-align: center; margin-top: 20px; color: #666;">Tank Level Display</p>
                </div>

                <div class="lights-panel">
                    <h3 style="margin-bottom: 20px; color: #333;">Status Indicators</h3>
                    
                    <div class="light-indicator">
                        <div id="light-green" class="light green"></div>
                        <div>
                            <div class="light-label">Optimal</div>
                            <div class="light-status">Level ≥ 100 cm</div>
                        </div>
                    </div>

                    <div class="light-indicator">
                        <div id="light-yellow" class="light yellow"></div>
                        <div>
                            <div class="light-label">Warning</div>
                            <div class="light-status">Level ≥ 250 cm</div>
                        </div>
                    </div>

                    <div class="light-indicator">
                        <div id="light-red" class="light red"></div>
                        <div>
                            <div class="light-label">Critical</div>
                            <div class="light-status">Level ≥ 330 cm</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Readings Section -->
        <div class="readings-section">
            <h2 class="section-title">Recent Readings</h2>
            <div class="readings-grid">
                <div class="reading-item">
                    <div class="reading-label">Current Measurement</div>
                    <div class="reading-value" id="reading-current">0 cm</div>
                    <div class="reading-time" id="reading-time">Just now</div>
                </div>

                <div class="reading-item">
                    <div class="reading-label">24h Maximum</div>
                    <div class="reading-value" id="reading-max">0 cm</div>
                </div>

                <div class="reading-item">
                    <div class="reading-label">24h Minimum</div>
                    <div class="reading-value" id="reading-min">0 cm</div>
                </div>

                <div class="reading-item">
                    <div class="reading-label">24h Average</div>
                    <div class="reading-value" id="reading-avg">0 cm</div>
                </div>
            </div>
        </div>

        <!-- Readings History Table -->
        <div class="table-section">
            <h2 class="section-title">Readings History (Last 24 Hours)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Water Level (cm)</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="readings-table">
                    <tr>
                        <td colspan="4" style="text-align: center; color: #999; padding: 30px;">
                            Loading readings history...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 Arduino Water Level Monitoring System. All rights reserved.</p>
    </div>

    <script>
        let previousHeight = 0;
        let readingsData = [];

        async function fetchData() {
            try {
                const response = await fetch('/api/latest-sensor');
                const data = await response.json();

                const water = document.getElementById('water');
                const sensor = document.getElementById('sensor');
                const val = data.sensor || 0;

                sensor.innerText = val;

                // Update current level
                document.getElementById('current-level').innerText = val;
                document.getElementById('reading-current').innerText = val + ' cm';
                document.getElementById('update-time').innerText = new Date().toLocaleTimeString();

                // Update tank height
                let heightPercent = Math.min(Math.max((val / 390) * 100, 0), 100);
                water.style.height = heightPercent + "%";

                // Simulate readings history (in real implementation, fetch from API)
                updateReadingsHistory(val);

                // Update lights
                document.getElementById('light-green').classList.toggle('active', val >= 100);
                document.getElementById('light-yellow').classList.toggle('active', val >= 250);
                document.getElementById('light-red').classList.toggle('active', val >= 330);

            } catch (err) {
                console.error("Error fetching sensor data:", err);
            }
        }

        function updateReadingsHistory(currentVal) {
            // Add current reading to history
            const now = new Date();
            readingsData.unshift({
                timestamp: now.toLocaleString(),
                level: currentVal,
                status: currentVal >= 330 ? 'Critical' : currentVal >= 250 ? 'Warning' : 'Normal'
            });

            // Keep only last 24 readings
            if (readingsData.length > 24) {
                readingsData = readingsData.slice(0, 24);
            }

            // Update statistics
            const levels = readingsData.map(r => r.level);
            const maxLevel = Math.max(...levels);
            const minLevel = Math.min(...levels);
            const avgLevel = (levels.reduce((a, b) => a + b, 0) / levels.length).toFixed(1);

            document.getElementById('max-level').innerText = maxLevel;
            document.getElementById('min-level').innerText = minLevel;
            document.getElementById('avg-level').innerText = avgLevel;

            document.getElementById('reading-max').innerText = maxLevel + ' cm';
            document.getElementById('reading-min').innerText = minLevel + ' cm';
            document.getElementById('reading-avg').innerText = avgLevel + ' cm';

            // Update table
            updateTable();
        }

        function updateTable() {
            const tbody = document.getElementById('readings-table');
            if (readingsData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #999; padding: 30px;">No readings available yet</td></tr>';
                return;
            }

            tbody.innerHTML = readingsData.map(reading => `
                <tr>
                    <td><span class="timestamp">${reading.timestamp}</span></td>
                    <td><strong>${reading.level} cm</strong></td>
                    <td>
                        <span class="status-badge status-${reading.status.toLowerCase()}">
                            ${reading.status}
                        </span>
                    </td>
                    <td>${reading.level >= 330 ? 'Tank nearly full!' : reading.level >= 250 ? 'Rising level' : 'Normal operation'}</td>
                </tr>
            `).join('');
        }

        // Fetch data every second for real-time updates
        setInterval(fetchData, 1000);
        fetchData();
    </script>
</body>
</html>
