<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Smart Home Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    *{margin:0;padding:0;box-sizing:border-box}
    body{background:linear-gradient(135deg,#1a1f35,#16213e);min-height:100vh;font-family:"Segoe UI",sans-serif;color:#e0e0e0}
    .header{padding:2rem 1rem;border-bottom:1px solid rgba(255,255,255,.1);background:rgba(0,0,0,.3)}
    .header h1{font-size:2rem;font-weight:700;margin-bottom:.5rem;color:#fff}
    .devices-container{padding:2rem 1rem}
    .devices-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;max-width:1400px;margin:0 auto}
    .device-card{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);
      border-radius:1rem;padding:1.5rem;transition:.3s;backdrop-filter:blur(10px)}
    .device-card:hover{background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.2);
      transform:translateY(-4px);box-shadow:0 8px 32px rgba(0,0,0,.2)}
    .device-header{display:flex;justify-content:space-between;margin-bottom:1rem}
    .device-emoji{font-size:2.5rem}
    .status-indicator{width:10px;height:10px;border-radius:50%;animation:pulse 2s infinite}
    .status-indicator.on{background:#10b981;box-shadow:0 0 10px rgba(16,185,129,.5)}
    .status-indicator.off{background:#6b7280}
    @keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
    .device-actions{display:flex;gap:.75rem;margin-top:1.5rem}
    .btn-toggle{flex:1;padding:.75rem;border:none;border-radius:.75rem;font-weight:600;font-size:.95rem;cursor:pointer;transition:.3s}
    .btn-on{background:#10b981;color:white}
    .btn-on:hover{background:#059669}
    .btn-off{background:#ef4444;color:white}
    .btn-off:hover{background:#dc2626}
  </style>
</head>
<body>

<div class="header">
  <h1>🏠 Smart Home Dashboard</h1>
  <p>Monitor and control your home devices in real-time</p>
</div>

<div class="devices-container">
  <div class="devices-grid">
    @foreach ($data as $item)
      @php
        $emoji = match(strtolower($item->object)) {
          'tv' => '📺','lampu','lamp' => '💡','ac' => '❄️','kulkas','fridge' => '🧊','oven' => '🍕','pintu' => '🚪',default => '⚙️'
        };
      @endphp

      <div class="device-card" data-id="{{ $item->id }}">
        <div class="device-header">
          <div class="device-emoji">{{ $emoji }}</div>
          <div class="status-indicator {{ $item->status ? 'on' : 'off' }}"></div>
        </div>

        <h3 class="device-name">{{ ucfirst($item->object) }}</h3>

        <div class="device-actions">
          <button class="btn-toggle btn-on" onclick="toggleDevice(this,1)">ON</button>
          <button class="btn-toggle btn-off" onclick="toggleDevice(this,0)">OFF</button>
        </div>
      </div>
    @endforeach
  </div>
</div>

<script>
  function toggleDevice(button, status) {
    let card = $(button).closest('.device-card');
    let id = card.data("id");

    $.ajax({
      url: "/smarthome/update",
      type: "POST",
      data: {
        id: id,
        status: status,
        _token: "{{ csrf_token() }}"
      },
      success: function () {
        let indicator = card.find('.status-indicator');
        indicator.toggleClass("on", status == 1);
        indicator.toggleClass("off", status == 0);
      }
    });
  }
</script>

</body>
</html>
