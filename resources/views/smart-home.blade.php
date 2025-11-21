<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Smart Home Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  
  <!-- Added Three.js for luxury 3D animations -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0a0e27 100%);
      min-height: 100vh;
      font-family: "Inter", "Segoe UI", -apple-system, BlinkMacSystemFont, sans-serif;
      color: #e2e8f0;
      overflow-x: hidden;
    }

    /* Enhanced header with 3D perspective and glass morphism */
    .header {
      padding: 3rem 1.5rem;
      border-bottom: 1px solid rgba(148, 163, 184, 0.1);
      background: linear-gradient(180deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.6) 100%);
      backdrop-filter: blur(20px);
      position: relative;
      overflow: hidden;
      perspective: 1000px;
    }

    .header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
      pointer-events: none;
    }

    .header-content {
      position: relative;
      z-index: 1;
      max-width: 1400px;
      margin: 0 auto;
      animation: slideInDown 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .header h1 {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 0.5rem;
      color: #f1f5f9;
      letter-spacing: -1px;
      background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .header p {
      font-size: 1rem;
      color: #94a3b8;
      margin: 0;
      font-weight: 500;
    }

    /* Luxury gradient container for devices */
    .devices-container {
      padding: 4rem 1.5rem;
      position: relative;
      z-index: 1;
    }

    .devices-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(circle at 50% 0%, rgba(139, 92, 246, 0.05) 0%, transparent 50%);
      pointer-events: none;
    }

  .devices-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* 3 kolom di layar besar */
  gap: 2.5rem;
  max-width: 1600px;
  margin: 0 auto;
}

@media (max-width: 1024px) {
  .devices-grid {
    grid-template-columns: repeat(2, 1fr); /* 2 kolom tablet */
  }
}

@media (max-width: 640px) {
  .devices-grid {
    grid-template-columns: 1fr; /* 1 kolom HP */
  }
}


    /* Premium 3D device cards with advanced animations */
    .device-card {
      background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.5) 100%);
      border: 1px solid rgba(148, 163, 184, 0.2);
      border-radius: 1.5rem;
      padding: 2.5rem;
      transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
      backdrop-filter: blur(15px);
      position: relative;
      overflow: hidden;
      transform-style: preserve-3d;
      cursor: pointer;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .device-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, rgba(59, 130, 246, 0.1) 100%);
      opacity: 0;
      transition: opacity 0.5s ease;
      pointer-events: none;
    }

    .device-card::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(255, 255, 255, 0.1) 0%, transparent 50%);
      opacity: 0;
      pointer-events: none;
    }

    .device-card:hover {
      background: linear-gradient(135deg, rgba(30, 41, 59, 1) 0%, rgba(15, 23, 42, 0.8) 100%);
      border-color: rgba(139, 92, 246, 0.4);
      transform: translateY(-12px) rotateX(5deg);
      box-shadow: 0 25px 60px rgba(139, 92, 246, 0.25), 0 0 50px rgba(59, 130, 246, 0.1);
    }

    .device-card:hover::before {
      opacity: 1;
    }

    .device-card:hover::after {
      opacity: 1;
    }

    .device-card.active {
      border-color: rgba(16, 185, 129, 0.5);
      background: linear-gradient(135deg, rgba(30, 41, 59, 1) 0%, rgba(15, 23, 42, 0.9) 100%);
      box-shadow: 0 0 40px rgba(16, 185, 129, 0.3), inset 0 0 20px rgba(16, 185, 129, 0.1);
      animation: activeGlow 2s ease-in-out infinite;
    }

    @keyframes activeGlow {
      0%, 100% {
        box-shadow: 0 0 40px rgba(16, 185, 129, 0.3), inset 0 0 20px rgba(16, 185, 129, 0.1);
      }
      50% {
        box-shadow: 0 0 60px rgba(16, 185, 129, 0.5), inset 0 0 30px rgba(16, 185, 129, 0.2);
      }
    }

    /* 3D device header with floating animation */
    .device-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 2rem;
      position: relative;
      z-index: 2;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% {
        transform: translateY(0px);
      }
      50% {
        transform: translateY(-8px);
      }
    }

    .device-emoji {
      font-size: 4.5rem;
      line-height: 1;
      filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.3));
      transition: transform 0.3s ease;
    }

    .device-card:hover .device-emoji {
      transform: scale(1.15) rotateZ(-10deg);
    }

    /* Enhanced status indicator with advanced animations */
    .status-indicator {
      width: 14px;
      height: 14px;
      border-radius: 50%;
      border: 2px solid;
      position: relative;
      box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.5);
    }

    .status-indicator.on {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border-color: rgba(16, 185, 129, 0.8);
      box-shadow: 0 0 20px rgba(16, 185, 129, 0.8), inset 0 0 4px rgba(0, 0, 0, 0.5), 0 0 10px rgba(16, 185, 129, 0.4);
      animation: statusPulse 2s ease-in-out infinite;
    }

    .status-indicator.off {
      background: linear-gradient(135deg, #64748b 0%, #475569 100%);
      border-color: rgba(100, 116, 139, 0.5);
      box-shadow: 0 0 10px rgba(100, 116, 139, 0.3), inset 0 0 4px rgba(0, 0, 0, 0.5);
    }

    @keyframes statusPulse {
      0%, 100% {
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.8), inset 0 0 4px rgba(0, 0, 0, 0.5), 0 0 10px rgba(16, 185, 129, 0.4);
      }
      50% {
        box-shadow: 0 0 30px rgba(16, 185, 129, 1), inset 0 0 4px rgba(0, 0, 0, 0.5), 0 0 15px rgba(16, 185, 129, 0.6);
      }
    }

    .device-name {
      font-size: 1.5rem;
      font-weight: 700;
      color: #f1f5f9;
      margin-bottom: 1.5rem;
      text-transform: capitalize;
      letter-spacing: -0.3px;
      position: relative;
      z-index: 2;
    }

    /* Premium device actions with enhanced button styling */
    .device-actions {
      display: flex;
      gap: 1rem;
      margin-top: 2rem;
      position: relative;
      z-index: 2;
    }

    .btn-toggle {
      flex: 1;
      padding: 1rem;
      border: none;
      border-radius: 1rem;
      font-weight: 700;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      position: relative;
      overflow: hidden;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-toggle::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.4);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .btn-toggle:active::before {
      width: 400px;
      height: 400px;
    }

    .btn-on {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      border: 2px solid rgba(16, 185, 129, 0.6);
    }

    .btn-on:hover {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      transform: translateY(-4px) scale(1.02);
      box-shadow: 0 12px 30px rgba(16, 185, 129, 0.4);
      border-color: rgba(16, 185, 129, 0.8);
    }

    .btn-off {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      border: 2px solid rgba(239, 68, 68, 0.6);
    }

    .btn-off:hover {
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      transform: translateY(-4px) scale(1.02);
      box-shadow: 0 12px 30px rgba(239, 68, 68, 0.4);
      border-color: rgba(239, 68, 68, 0.8);
    }

    /* Responsive design improvements */
    @media (max-width: 768px) {
      .header h1 {
        font-size: 2.2rem;
      }

      .devices-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }

      .device-emoji {
        font-size: 3.5rem;
      }

      .btn-toggle {
        padding: 0.875rem;
        font-size: 0.9rem;
      }
    }

    /* SweetAlert luxury styling */
    .swal2-popup {
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
      border: 1px solid rgba(148, 163, 184, 0.3) !important;
      border-radius: 1.5rem !important;
      backdrop-filter: blur(20px) !important;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5) !important;
    }

    .swal2-title {
      color: #f1f5f9 !important;
      font-weight: 700 !important;
      font-size: 1.5rem !important;
    }

    .swal2-html-container {
      color: #cbd5e1 !important;
      font-size: 0.95rem !important;
    }

    .swal2-confirm {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
      border-radius: 1rem !important;
      border: none !important;
      font-weight: 700 !important;
      box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3) !important;
    }

    .swal2-confirm:hover {
      transform: translateY(-2px) !important;
      box-shadow: 0 12px 30px rgba(16, 185, 129, 0.5) !important;
    }

    .swal2-cancel {
      background: linear-gradient(135deg, #64748b 0%, #475569 100%) !important;
      border-radius: 1rem !important;
      border: none !important;
      font-weight: 700 !important;
    }

    /* Keyframe animations for entrance effects */
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

    .device-card {
      animation: fadeInUp 0.6s ease-out forwards;
    }

    
    
  </style>
</head>
<body>

<div class="header">
  <div class="header-content">
    <h1>Smart Home Control</h1>
    <p>Manage your connected devices with luxury precision</p>
  </div>
</div>

<div class="devices-container">
  <div class="devices-grid">
    @foreach ($data as $item)
      @php
        $emoji = match(strtolower($item->object)) {
          'tv' => '📺',
          'lampu','lamp' => '💡',
          'ac' => '❄️',
          'kulkas','fridge' => '🧊',
          'oven' => '🍕',
          'pintu' => '🚪',
          default => '⚙️'
        };
      @endphp

      <div class="device-card {{ $item->status ? 'active' : '' }}" data-id="{{ $item->id }}" data-name="{{ ucfirst($item->object) }}">
        <div class="device-header">
          <div class="device-emoji">{{ $emoji }}</div>
          <div class="status-indicator {{ $item->status ? 'on' : 'off' }}"></div>
        </div>

        <h3 class="device-name">{{ ucfirst($item->name) }}</h3>

        <div class="device-actions">
          <button class="btn-toggle btn-on" onclick="toggleDevice(this, 1)">ON</button>
          <button class="btn-toggle btn-off" onclick="toggleDevice(this, 0)">OFF</button>
        </div>
      </div>
    @endforeach
  </div>
</div>

<script>
  function toggleDevice(button, status) {
    let card = $(button).closest('.device-card');
    let id = card.data("id");
    let deviceName = card.data("name");

    $.ajax({
      url: "/smart-home/update/" + id,
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
        card.toggleClass("active", status == 1);

        const statusText = status == 1 ? 'ON' : 'OFF';
        const statusIcon = status == 1 ? 'success' : 'info';
        const backgroundColor = status == 1 ? 'rgba(16, 185, 129, 0.15)' : 'rgba(59, 130, 246, 0.15)';

        Swal.fire({
          icon: statusIcon,
          title: `${deviceName}`,
          html: `<p style="color: #cbd5e1; margin: 0; font-size: 1.1rem;">Successfully turned <strong>${statusText}</strong></p>`,
          background: backgroundColor,
          backdrop: 'rgba(0, 0, 0, 0.5)',
          confirmButtonText: 'Done',
          allowOutsideClick: true,
          timer: 2500,
          timerProgressBar: true,
          showClass: {
            popup: 'animate__animated animate__fadeInScale'
          }
        });
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          html: '<p style="color: #fecaca;">Failed to update device. Please try again.</p>',
          background: 'rgba(127, 29, 29, 0.15)',
          confirmButtonText: 'Try Again'
        });
      }
    });
  }

  document.querySelectorAll('.device-card').forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width) * 100;
      const y = ((e.clientY - rect.top) / rect.height) * 100;
      card.style.setProperty('--mouse-x', x + '%');
      card.style.setProperty('--mouse-y', y + '%');
    });
  });
</script>

</body>
</html>
