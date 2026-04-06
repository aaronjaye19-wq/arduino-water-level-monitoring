<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Water Sensor Dashboard</title>
<style>
    .navbar {
        background-color: #1a3a5a;
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    .navbar h2 { margin: 0; }
    .navbar .logout-btn {
        background-color: #ff4444;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }
    .navbar .logout-btn:hover { background-color: #cc0000; }

    body {
        font-family: 'Segoe UI', Tahoma, Verdana, sans-serif;
        background: #f0f2f5;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px;
    }

    h1 {
        color: #1a3a5a;
        margin-bottom: 30px;
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

<div class="navbar">
    <h2>💧 Water Sensor Dashboard</h2>
    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

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

<script>
let previousHeight = 0;

async function fetchData() {
    try {
        const response = await fetch('/api/latest-sensor');
        const data = await response.json();

        const water = document.getElementById('water');
        const sensor = document.getElementById('sensor');
        const val = data.sensor;

        sensor.innerText = val;

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

setInterval(fetchData, 1000); // 1s update for smoother animation
fetchData();
</script>

</body>
</html>
