<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Water Sensor Dashboard</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Header Styles */
    .header {
        background: rgba(255, 255, 255, 0.95);
        padding: 20px 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-title {
        color: #333;
        font-size: 24px;
        font-weight: 600;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .user-details {
        text-align: right;
    }

    .user-name {
        color: #333;
        font-weight: 600;
        font-size: 14px;
    }

    .user-role {
        color: #667eea;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 500;
    }

    .btn-logout {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.3s;
    }

    .btn-logout:hover {
        background: #c0392b;
    }

    /* Main Content */
    .main-content {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        gap: 30px;
        align-items: center;
    }

    h1 {
        color: white;
        margin-bottom: 20px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    .dashboard-container {
        display: flex;
        align-items: center;
        gap: 40px;
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        max-width: 900px;
        width: 100%;
    }

    /* Stats Section */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        width: 100%;
        max-width: 1000px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-label {
        color: #666;
        font-size: 13px;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .stat-value {
        color: #667eea;
        font-size: 32px;
        font-weight: 700;
    }

    .stat-unit {
        color: #999;
        font-size: 12px;
        margin-top: 5px;
    }

    /* Measurements Table */
    .measurements-section {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 1000px;
    }

    .measurements-section h2 {
        color: #333;
        margin-bottom: 20px;
        font-size: 20px;
    }

    .measurements-table {
        width: 100%;
        border-collapse: collapse;
    }

    .measurements-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #667eea;
    }

    .measurements-table th {
        padding: 15px;
        text-align: left;
        color: #333;
        font-weight: 600;
        font-size: 14px;
    }

    .measurements-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        color: #666;
        font-size: 14px;
    }

    .measurements-table tbody tr:hover {
        background: #f9f9f9;
    }

    .measurements-table tbody tr:last-child td {
        border-bottom: none;
    }

    .level-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .level-badge.low {
        background: #d5f4e6;
        color: #27ae60;
    }

    .level-badge.medium {
        background: #fff3cd;
        color: #856404;
    }

    .level-badge.high {
        background: #fadbd8;
        color: #c0392b;
    }

    .tank {
        position: relative;
        width: 160px;
        height: 320px;
        background-color: #e0e0e0;
        border: 6px solid #333;
        border-radius: 0 0 15px 15px;
        overflow: hidden;
        box-shadow: inset 0 0 20px rgba(0,0,0,0.1);
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
        font-size: 1.5rem;
        font-weight: bold;
        transition: height 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        height: 0%;
        overflow: hidden;
    }

    /* Primary Wave - Fast, detailed waves */
    .water::before {
        content: "";
        position: absolute;
        top: -12px;
        left: 0;
        width: 200%;
        height: 30px;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 1200 60' preserveAspectRatio='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0,30 Q150,10 300,30 T600,30 T900,30 T1200,30' stroke='rgba(255,255,255,0.4)' stroke-width='2' fill='none'/%3E%3Cpath d='M0,40 Q150,20 300,40 T600,40 T900,40 T1200,40' stroke='rgba(255,255,255,0.3)' stroke-width='1.5' fill='none'/%3E%3C/svg%3E");
        background-size: 600px 100%;
        background-repeat: repeat-x;
        background-position: 0 0;
        animation: wavePrimary 3s linear infinite;
    }

    /* Secondary Wave - Slower, larger rolling waves */
    .water::after {
        content: "";
        position: absolute;
        top: -8px;
        left: 0;
        width: 200%;
        height: 25px;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 1200 60' preserveAspectRatio='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0,35 Q300,15 600,35 T1200,35' stroke='rgba(255,255,255,0.25)' stroke-width='2.5' fill='none'/%3E%3C/svg%3E");
        background-size: 800px 100%;
        background-repeat: repeat-x;
        background-position: 0 0;
        animation: waveSecondary 5s linear infinite reverse;
    }

    /* Wave Animations */
    @keyframes wavePrimary {
        0% { 
            background-position: 0 0;
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-2px);
        }
        100% { 
            background-position: 600px 0;
            transform: translateY(0px);
        }
    }

    @keyframes waveSecondary {
        0% { 
            background-position: 0 0;
            transform: translateY(1px);
        }
        50% {
            transform: translateY(-1px);
        }
        100% { 
            background-position: 800px 0;
            transform: translateY(1px);
        }
    }

    /* Bubble effect when water rises */
    @keyframes bubble {
        0% {
            opacity: 0.6;
            transform: translateY(0) scale(1);
        }
        50% {
            opacity: 0.8;
        }
        100% {
            opacity: 0;
            transform: translateY(-20px) scale(0.8);
        }
    }

    /* Lights Panel */
    .lights-panel {
        display: flex;
        flex-direction: column;
        gap: 25px;
        padding: 20px;
        background: #222;
        border-radius: 15px;
    }

    .light {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #333;
        border: 3px solid #111;
        transition: all 0.3s;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.5);
    }

    /* Light States */
    .light.red.active { background-color: #ff4d4d; box-shadow: 0 0 20px #ff4d4d; }
    .light.yellow.active { background-color: #ffeb3b; box-shadow: 0 0 20px #ffeb3b; }
    .light.green.active { background-color: #4caf50; box-shadow: 0 0 20px #4caf50; }

    .label-group { text-align: center; }
    .label { margin-top: 15px; font-weight: bold; color: #555; text-transform: uppercase; letter-spacing: 1px; }

    /* Bubble elements for rising water effect */
    .bubble {
        position: absolute;
        bottom: 0;
        width: 8px;
        height: 8px;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 50%;
        animation: bubble 1.5s ease-in forwards;
    }

    .bubble:nth-child(1) { left: 20%; animation-delay: 0s; animation-duration: 1.2s; }
    .bubble:nth-child(2) { left: 40%; animation-delay: 0.3s; animation-duration: 1.4s; }
    .bubble:nth-child(3) { left: 60%; animation-delay: 0.6s; animation-duration: 1.3s; }
    .bubble:nth-child(4) { left: 80%; animation-delay: 0.9s; animation-duration: 1.5s; }
</style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-title">🌊 Water Level Monitoring System</div>
        <div class="user-info">
            <div class="user-details">
                <div class="user-name">{{ session('user_name', 'User') }}</div>
                <div class="user-role">{{ session('user_role', 'user') }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>💧 Water Sensor Dashboard</h1>

        <!-- Tank Visualization & Lights -->
        <div class="dashboard-container">
            <div class="label-group">
                <div class="tank">
                    <div id="water" class="water">
                        <span id="sensor">0</span>
                    </div>
                </div>
                <p class="label">Tank Level</p>
            </div>

            <div class="lights-panel">
                <div id="light-red" class="light red"></div>
                <div id="light-yellow" class="light yellow"></div>
                <div id="light-green" class="light green"></div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-label">Current Level</div>
                <div class="stat-value" id="stat-current">0</div>
                <div class="stat-unit">cm</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Maximum Level</div>
                <div class="stat-value" id="stat-max">0</div>
                <div class="stat-unit">cm</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Minimum Level</div>
                <div class="stat-value" id="stat-min">0</div>
                <div class="stat-unit">cm</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Status</div>
                <div class="stat-value" id="stat-status" style="color: #27ae60; font-size: 24px;">✓</div>
                <div class="stat-unit">System OK</div>
            </div>
        </div>

        <!-- Measurements Section (Admin Only) -->
        @if (session('user_role') === 'admin')
        <div class="measurements-section">
            <h2>📊 Water Level Measurements History</h2>
            <table class="measurements-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Water Level (cm)</th>
                        <th>Green Zone</th>
                        <th>Yellow Zone</th>
                        <th>Red Zone</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="measurements-tbody">
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">Loading measurements...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>

<script>
let previousHeight = 0;
let measurements = [];
let maxLevel = 0;
let minLevel = 999;

async function fetchData() {
    try {
        const response = await fetch('/api/latest-sensor');
        const data = await response.json();

        const water = document.getElementById('water');
        const sensor = document.getElementById('sensor');
        const val = data.sensor;

        sensor.innerText = val;

        // Update statistics
        document.getElementById('stat-current').innerText = val;
        if (val > maxLevel) {
            maxLevel = val;
            document.getElementById('stat-max').innerText = maxLevel;
        }
        if (val < minLevel && val > 0) {
            minLevel = val;
            document.getElementById('stat-min').innerText = minLevel;
        }

        // To ensures 390 is 100% (mouth of the container)
        let heightPercent = Math.min(Math.max((val / 390) * 100, 0), 100);
        water.style.height = heightPercent + "%";

        // Create bubbles when water rises
        if (heightPercent > previousHeight) {
            createBubbles(water);
        }
        previousHeight = heightPercent;

        // Logic for Lights
        document.getElementById('light-green').classList.toggle('active', val >= 100);
        document.getElementById('light-yellow').classList.toggle('active', val >= 250);
        document.getElementById('light-red').classList.toggle('active', val >= 330);

        // Store measurement for history
        const timestamp = new Date().toLocaleString();
        const green = val >= 100 ? '✓' : '✗';
        const yellow = val >= 250 ? '✓' : '✗';
        const red = val >= 330 ? '✓' : '✗';
        
        measurements.unshift({
            timestamp,
            level: val,
            green,
            yellow,
            red,
            status: val >= 330 ? '🔴 High' : val >= 250 ? '🟡 Medium' : '🟢 Low'
        });

        // Keep only last 10 measurements
        if (measurements.length > 10) {
            measurements = measurements.slice(0, 10);
        }

        // Update admin measurements table if visible
        const tbody = document.getElementById('measurements-tbody');
        if (tbody) {
            updateMeasurementsTable();
        }

    } catch (err) {
        console.error("Error fetching sensor data:", err);
    }
}

function createBubbles(waterElement) {
    // Create 4 bubbles for rising effect
    for (let i = 0; i < 4; i++) {
        const bubble = document.createElement('div');
        bubble.className = 'bubble';
        bubble.style.left = (Math.random() * 100) + '%';
        waterElement.appendChild(bubble);
        
        // Remove bubble after animation completes
        setTimeout(() => bubble.remove(), 1500);
    }
}

function updateMeasurementsTable() {
    const tbody = document.getElementById('measurements-tbody');
    if (measurements.length === 0) return;

    tbody.innerHTML = measurements.map(m => `
        <tr>
            <td>${m.timestamp}</td>
            <td><strong>${m.level} cm</strong></td>
            <td>${m.green}</td>
            <td>${m.yellow}</td>
            <td>${m.red}</td>
            <td>
                <span class="level-badge ${m.level >= 330 ? 'high' : m.level >= 250 ? 'medium' : 'low'}">
                    ${m.status}
                </span>
            </td>
        </tr>
    `).join('');
}

setInterval(fetchData, 1000); // 1s update for smoother animation
fetchData();
</script>

</body>
</html>
