<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DHT22 Sensor Monitoring Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --card-bg: rgba(30, 41, 59, 0.8);
        --accent-emerald: #10b981;
        --accent-emerald-light: #6ee7b7;
        --glow-color: rgba(16, 185, 129, 0.3);
        --text-primary: #f1f5f9;
        --text-secondary: #cbd5e1;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      html, body {
        width: 100%;
        height: 100%;
        overflow-x: hidden;
      }

      body {
        background: linear-gradient(135deg, #0f172a 0%, #1a1a2e 50%, #16213e 100%);
        min-height: 100vh;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
        color: var(--text-primary);
        position: relative;
        overflow: hidden;
      }

      /* Dynamic background lighting effect */
      body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 20% 50%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.05) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
      }

      .dashboard-container {
        padding: 2rem;
        position: relative;
        z-index: 1;
        max-width: 1400px;
        margin: 0 auto;
      }

      /* Enhanced header with luxury styling */
      .dashboard-header {
        text-align: center;
        margin-bottom: 3rem;
        animation: slideInDown 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
      }

      .dashboard-header h1 {
        font-weight: 800;
        font-size: clamp(2rem, 5vw, 3.5rem);
        background: linear-gradient(135deg, var(--accent-emerald-light), var(--accent-emerald));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
      }

      .dashboard-header p {
        font-size: 1.1rem;
        color: var(--text-secondary);
        font-weight: 400;
        letter-spacing: 0.5px;
      }

      /* Navigation button to smart-home */
      .header-nav {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
      }

      .nav-btn {
        padding: 0.75rem 2rem;
        border: 2px solid var(--accent-emerald);
        background: transparent;
        color: var(--accent-emerald);
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        font-size: 1rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        text-decoration: none;
        display: inline-block;
      }

      .nav-btn:hover {
        background: var(--accent-emerald);
        color: var(--primary-dark);
        box-shadow: 0 0 30px var(--glow-color), inset 0 0 20px rgba(16, 185, 129, 0.2);
        transform: translateY(-2px);
      }

      .nav-btn.primary {
        background: linear-gradient(135deg, var(--accent-emerald), var(--accent-emerald-light));
        color: var(--primary-dark);
        border-color: var(--accent-emerald);
      }

      .nav-btn.primary:hover {
        box-shadow: 0 10px 40px rgba(16, 185, 129, 0.4);
        transform: translateY(-4px);
      }

      /* Luxury glass morphism stat cards */
      .stat-card {
        background: rgba(30, 41, 59, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(16, 185, 129, 0.15);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
      }

      .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.4s ease;
      }

      .stat-card:hover {
        transform: translateY(-8px) perspective(1000px) rotateX(5deg);
        box-shadow: 0 20px 60px rgba(16, 185, 129, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        border-color: rgba(16, 185, 129, 0.4);
        background: rgba(30, 41, 59, 0.8);
      }

      .stat-card:hover::before {
        opacity: 1;
      }

      .stat-label {
        font-size: 0.95rem;
        color: var(--text-secondary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }

      .stat-value {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, var(--accent-emerald-light), var(--accent-emerald));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
      }

      .stat-unit {
        font-size: 1.2rem;
        color: var(--text-secondary);
        font-weight: 500;
      }

      /* Enhanced status indicator with glow animation */
      .status-indicator {
        display: inline-block;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: var(--accent-emerald);
        animation: statusPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        box-shadow: 0 0 10px var(--accent-emerald);
      }

      @keyframes statusPulse {
        0%, 100% {
          box-shadow: 0 0 10px var(--accent-emerald), 0 0 20px rgba(16, 185, 129, 0.3);
          transform: scale(1);
        }
        50% {
          box-shadow: 0 0 20px var(--accent-emerald), 0 0 40px rgba(16, 185, 129, 0.4);
          transform: scale(1.1);
        }
      }

      /* Luxury chart containers */
      .chart-container {
        background: rgba(30, 41, 59, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(16, 185, 129, 0.15);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      }

      .chart-container:hover {
        transform: translateY(-4px);
        border-color: rgba(16, 185, 129, 0.3);
        box-shadow: 0 15px 50px rgba(16, 185, 129, 0.15);
      }

      .chart-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        letter-spacing: 0.5px;
      }

      .chart-wrapper {
        position: relative;
        height: 300px;
      }

      .last-update {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-top: 1.5rem;
        text-align: right;
        opacity: 0.7;
      }

      /* Animations */
      @keyframes slideInDown {
        from {
          opacity: 0;
          transform: translateY(-30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .stat-card, .chart-container {
        animation: fadeInUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
      }

      .stat-card:nth-child(1) { animation-delay: 0.1s; }
      .stat-card:nth-child(2) { animation-delay: 0.2s; }
      .chart-container:nth-child(1) { animation-delay: 0.3s; }
      .chart-container:nth-child(2) { animation-delay: 0.4s; }

      /* Responsive design */
      @media (max-width: 768px) {
        .dashboard-header h1 {
          font-size: 2rem;
        }

        .stat-value {
          font-size: 2rem;
        }

        .dashboard-container {
          padding: 1rem;
        }

        .header-nav {
          gap: 0.5rem;
        }

        .nav-btn {
          padding: 0.6rem 1.5rem;
          font-size: 0.9rem;
        }
      }

      /* Chart.js customization */
      .chart-container canvas {
        filter: brightness(1.1);
      }
    </style>
  </head>
  <body>
    <div class="container-lg dashboard-container">
      <!-- Header -->
      <div class="dashboard-header">
        <h1>Sensor Monitoring <span class="status-indicator"></span></h1>
        <p>Real-time DHT22 Temperature & Humidity Tracking</p>
        <!-- Navigation to smart home dashboard -->
        <div class="header-nav">
          <a href="/smart-home" class="nav-btn primary">⚡ Go to Smart Home Dashboard</a>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="row">
        <div class="col-lg-6 col-md-6">
          <div class="stat-card temperature">
            <div class="stat-label">🌡️ Temperature</div>
            <div class="stat-value"><span id="temperature">--</span><span class="stat-unit">°C</span></div>
            <div class="last-update">Last updated: <span id="temp-update">--:--:--</span></div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="stat-card humidity">
            <div class="stat-label">💧 Humidity</div>
            <div class="stat-value"><span id="humidity">--</span><span class="stat-unit">%</span></div>
            <div class="last-update">Last updated: <span id="humid-update">--:--:--</span></div>
          </div>
        </div>
      </div>

      <!-- Charts -->
      <div class="row">
        <div class="col-lg-6 col-md-12">
          <div class="chart-container">
            <div class="chart-title">🌡️ Temperature Trend</div>
            <div class="chart-wrapper">
              <canvas id="temperatureChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12">
          <div class="chart-container">
            <div class="chart-title">💧 Humidity Trend</div>
            <div class="chart-wrapper">
              <canvas id="humidityChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" 
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
      // Data storage untuk chart (simpan 20 data terakhir)
      let temperatureData = [];
      let humidityData = [];
      let timeLabels = [];
      const MAX_DATA_POINTS = 20;

      // Inisialisasi Chart.js
      const temperatureCtx = document.getElementById('temperatureChart').getContext('2d');
      const humidityCtx = document.getElementById('humidityChart').getContext('2d');

      const temperatureChart = new Chart(temperatureCtx, {
        type: 'line',
        data: {
          labels: timeLabels,
          datasets: [{
            label: 'Temperature (°C)',
            data: temperatureData,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.08)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointBackgroundColor: '#10b981',
            pointBorderColor: '#0f172a',
            pointBorderWidth: 2,
            pointHoverRadius: 7
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              labels: {
                usePointStyle: true,
                padding: 15,
                font: { size: 12, weight: '600' },
                color: '#cbd5e1'
              }
            }
          },
          scales: {
            y: {
              beginAtZero: false,
              ticks: {
                font: { size: 11 },
                color: '#94a3b8'
              },
              grid: {
                color: 'rgba(16, 185, 129, 0.1)'
              }
            },
            x: {
              ticks: {
                font: { size: 11 },
                color: '#94a3b8'
              },
              grid: {
                color: 'rgba(16, 185, 129, 0.05)'
              }
            }
          }
        }
      });

      const humidityChart = new Chart(humidityCtx, {
        type: 'line',
        data: {
          labels: timeLabels,
          datasets: [{
            label: 'Humidity (%)',
            data: humidityData,
            borderColor: '#06b6d4',
            backgroundColor: 'rgba(6, 182, 212, 0.08)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointBackgroundColor: '#06b6d4',
            pointBorderColor: '#0f172a',
            pointBorderWidth: 2,
            pointHoverRadius: 7
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              labels: {
                usePointStyle: true,
                padding: 15,
                font: { size: 12, weight: '600' },
                color: '#cbd5e1'
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
              ticks: {
                font: { size: 11 },
                color: '#94a3b8'
              },
              grid: {
                color: 'rgba(6, 182, 212, 0.1)'
              }
            },
            x: {
              ticks: {
                font: { size: 11 },
                color: '#94a3b8'
              },
              grid: {
                color: 'rgba(6, 182, 212, 0.05)'
              }
            }
          }
        }
      });

      // Fungsi untuk update waktu
      function getTime() {
        const now = new Date();
        return now.toLocaleTimeString('id-ID', { 
          hour: '2-digit', 
          minute: '2-digit', 
          second: '2-digit' 
        });
      }

      // Fungsi untuk mendapatkan data
      $(document).ready(function() {
        function getData() {
          $.ajax({
            type: "GET",
            url: "/get-latest",
            success: function(response) {
              let temperature = parseFloat(response.temperature);
              let humidity = parseFloat(response.humidity);
              let currentTime = getTime();

              // Update values di card
              $('#temperature').text(temperature.toFixed(1));
              $('#humidity').text(humidity.toFixed(1));
              $('#temp-update').text(currentTime);
              $('#humid-update').text(currentTime);

              // Update chart data
              temperatureData.push(temperature);
              humidityData.push(humidity);
              timeLabels.push(currentTime);

              // Batasi jumlah data points
              if (temperatureData.length > MAX_DATA_POINTS) {
                temperatureData.shift();
                humidityData.shift();
                timeLabels.shift();
              }

              // Update chart
              temperatureChart.data.labels = timeLabels;
              temperatureChart.data.datasets[0].data = temperatureData;
              temperatureChart.update('none');

              humidityChart.data.labels = timeLabels;
              humidityChart.data.datasets[0].data = humidityData;
              humidityChart.update('none');
            },
            error: function() {
              console.log('Error fetching data');
            }
          });
        }

        // Panggil data pertama kali
        getData();

        // Update setiap 2 detik
        setInterval(getData, 2000);
      });
    </script>
  </body>
</html>
