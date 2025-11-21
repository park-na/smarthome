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
        --primary-color: #2c3e50;
        --secondary-color: #e74c3c;
        --accent-color: #3498db;
        --success-color: #27ae60;
      }

      body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      .dashboard-container {
        padding: 2rem 0;
      }

      .dashboard-header {
        color: white;
        text-align: center;
        margin-bottom: 2rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
      }

      .dashboard-header h1 {
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
      }

      .dashboard-header p {
        font-size: 1.1rem;
        opacity: 0.9;
      }

      .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-top: 4px solid;
      }

      .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.15);
      }

      .stat-card.temperature {
        border-top-color: #e74c3c;
      }

      .stat-card.humidity {
        border-top-color: #3498db;
      }

      .stat-label {
        font-size: 0.95rem;
        color: #7f8c8d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
      }

      .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
      }

      .stat-card.temperature .stat-value {
        color: #e74c3c;
      }

      .stat-card.humidity .stat-value {
        color: #3498db;
      }

      .stat-unit {
        font-size: 1rem;
        color: #95a5a6;
        font-weight: 400;
      }

      .status-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #27ae60;
        margin-left: 0.5rem;
        animation: pulse 2s infinite;
      }

      @keyframes pulse {
        0% {
          box-shadow: 0 0 0 0 rgba(39, 174, 96, 0.7);
        }
        70% {
          box-shadow: 0 0 0 10px rgba(39, 174, 96, 0);
        }
        100% {
          box-shadow: 0 0 0 0 rgba(39, 174, 96, 0);
        }
      }

      .chart-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        padding: 2rem;
        margin-bottom: 1.5rem;
        position: relative;
      }

      .chart-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1.5rem;
      }

      .chart-wrapper {
        position: relative;
        height: 300px;
      }

      .last-update {
        font-size: 0.85rem;
        color: #95a5a6;
        margin-top: 1rem;
        text-align: right;
      }
    </style>
  </head>
  <body>
    <div class="container-lg dashboard-container">
      <!-- Header -->
      <div class="dashboard-header">
        <h1>Sensor Monitoring <span class="status-indicator"></span></h1>
        <p>Real-time DHT22 Temperature & Humidity Tracking</p>
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
            borderColor: '#e74c3c',
            backgroundColor: 'rgba(231, 76, 60, 0.05)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#e74c3c',
            pointBorderColor: 'white',
            pointBorderWidth: 2,
            pointHoverRadius: 6
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
                font: { size: 12, weight: '600' }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: false,
              ticks: {
                font: { size: 11 }
              },
              grid: {
                color: 'rgba(0,0,0,0.05)'
              }
            },
            x: {
              ticks: {
                font: { size: 11 }
              },
              grid: {
                color: 'rgba(0,0,0,0.05)'
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
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.05)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#3498db',
            pointBorderColor: 'white',
            pointBorderWidth: 2,
            pointHoverRadius: 6
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
                font: { size: 12, weight: '600' }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
              ticks: {
                font: { size: 11 }
              },
              grid: {
                color: 'rgba(0,0,0,0.05)'
              }
            },
            x: {
              ticks: {
                font: { size: 11 }
              },
              grid: {
                color: 'rgba(0,0,0,0.05)'
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