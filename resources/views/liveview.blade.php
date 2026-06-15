<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gate Control AI — Live View</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            surface:  '#1a1a1a',
            sidebar:  '#1e1e1e',
            card:     '#252525',
            card2:    '#2a2a2a',
            inputbg:  '#333333',
            divider:  '#333333',
            muted:    '#777777',
            orange:   '#f97316',
            green:    '#22c55e',
            'green-dark': '#16a34a',
            teal:     '#14b8a6',
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
          },
        },
      },
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    html, body { height: 100%; overflow: hidden; }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: #1a1a1a; }
    ::-webkit-scrollbar-thumb { background: #444; border-radius: 4px; }
    .nav-link { transition: background .15s, color .15s; }
    .nav-link:hover { background: rgba(255,255,255,.06); }
    .nav-link.active { background: #333; color: #fff; }
    .pulse-dot::before {
      content: '';
      display: inline-block;
      width: 7px; height: 7px;
      border-radius: 50%;
      background: #ef4444;
      margin-right: 5px;
      animation: pulse-anim 1.4s infinite;
    }
    @keyframes pulse-anim {
      0%,100% { opacity: 1; }
      50%      { opacity: .3; }
    }
    /* live camera scanline shimmer */
    .camera-bg {
      background: #1e1e1e;
      position: relative;
      overflow: hidden;
    }
    .camera-bg::after {
      content: '';
      position: absolute;
      inset: 0;
      background: repeating-linear-gradient(
        0deg,
        transparent,
        transparent 3px,
        rgba(255,255,255,.018) 3px,
        rgba(255,255,255,.018) 4px
      );
      pointer-events: none;
    }
    /* corner brackets for camera frame */
    .cam-corner { position: absolute; width: 18px; height: 18px; border-color: #f97316; }
    .cam-corner.tl { top: 12px; left: 12px; border-top: 2px solid; border-left: 2px solid; }
    .cam-corner.tr { top: 12px; right: 12px; border-top: 2px solid; border-right: 2px solid; }
    .cam-corner.bl { bottom: 12px; left: 12px; border-bottom: 2px solid; border-left: 2px solid; }
    .cam-corner.br { bottom: 12px; right: 12px; border-bottom: 2px solid; border-right: 2px solid; }
  </style>
</head>
<body class="bg-surface font-sans text-white flex flex-col h-screen overflow-hidden">

  <!-- ═══════════ TOP HEADER ═══════════ -->
  <header class="flex items-center justify-between bg-sidebar border-b border-divider px-4 py-2.5 flex-shrink-0 z-10">

    <!-- Logo -->
    <div class="flex items-center gap-2.5">
      <div class="w-9 h-9 bg-orange rounded-lg flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
          <circle cx="12" cy="13" r="3"/>
        </svg>
      </div>
      <div class="leading-tight">
        <p class="text-white text-sm font-bold leading-none">Gate Control <span class="text-orange">AI</span></p>
        <p class="text-muted text-[9px] mt-0.5 leading-tight max-w-[160px]">Sistem Monitoring &amp; Pengendalian Gerbang Portal Politeknik Negeri Bukittinggi</p>
      </div>
    </div>

    <!-- Center title -->
    <div class="hidden md:block text-center">
      <p class="text-white text-base font-semibold leading-none">Live View</p>
      <p class="text-muted text-xs mt-0.5">Streaming CCTV/Camera — Portal Gerbang Politeknik</p>
    </div>

    <!-- Right: standby + clock -->
    <div class="flex items-center gap-3">
      <button class="flex items-center gap-1.5 bg-[#2a1a1a] border border-red-800 text-red-400 rounded-md px-3 py-1 text-xs font-medium">
        <span class="pulse-dot"></span>
        Standby
      </button>
      <div class="text-right leading-tight">
        <p id="clock" class="text-white text-lg font-bold tracking-widest leading-none">22:15:54</p>
        <p id="dateline" class="text-muted text-[10px] mt-0.5">Rabu, 15 April 2026</p>
      </div>
    </div>

  </header>

  <!-- ═══════════ BODY ═══════════ -->
  <div class="flex flex-1 overflow-hidden">

    <!-- ── SIDEBAR ── -->
    <aside class="w-44 bg-sidebar border-r border-divider flex flex-col flex-shrink-0 overflow-y-auto py-3">

      <p class="text-muted text-[9px] font-semibold uppercase tracking-widest px-4 mb-2">Menu Utama</p>

      <nav class="flex flex-col gap-0.5 px-2">
        <!-- Dashboard -->
        <a href="{{ url('/dashboard') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
          </svg>
          Dashboard
        </a>
        <!-- Live View — ACTIVE -->
        <a href="{{ url('/liveview') }}" class="nav-link active flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-white">
          <svg class="w-4 h-4 text-orange flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
          </svg>
          Live view
        </a>
        <!-- Laporan -->
        <a href="{{ url('/reports') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
          </svg>
          Laporan Kendaraan
        </a>
      </nav>

      <p class="text-muted text-[9px] font-semibold uppercase tracking-widest px-4 mt-5 mb-2">Pengaturan</p>

      <nav class="flex flex-col gap-0.5 px-2">
        <a href="{{ url('/jadwal') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
          Jadwal Operasional
        </a>
        <a href="{{ url('/perangkat') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/>
            <line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/>
            <line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/>
            <line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/>
            <line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/>
          </svg>
          Perangkat IoT
        </a>
      </nav>

    </aside>

    <!-- ── MAIN CONTENT ── -->
    <main class="flex-1 overflow-y-auto p-4 bg-surface">

      <div class="flex gap-3 h-full" style="min-height: 0;">

        <!-- LEFT: Camera feed -->
        <div class="flex-1 min-w-0 camera-bg border border-divider rounded-xl flex items-center justify-center relative" style="min-height: 420px;">
          <!-- Corner brackets -->
          <div class="cam-corner tl"></div>
          <div class="cam-corner tr"></div>
          <div class="cam-corner bl"></div>
          <div class="cam-corner br"></div>

          <!-- Top-left live badge -->
          <div class="absolute top-3 left-10 flex items-center gap-1.5 bg-black/50 rounded px-2 py-1">
            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse flex-shrink-0"></span>
            <span class="text-white text-[10px] font-semibold tracking-widest">LIVE</span>
          </div>

          <!-- Camera label -->
          <div class="absolute top-3 right-10 text-muted text-[10px]">CAM — 01</div>

          <!-- Center placeholder content -->
          <div class="text-center flex flex-col items-center gap-3 z-10">
            <svg class="w-14 h-14 text-[#3a3a3a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
              <circle cx="12" cy="13" r="3"/>
            </svg>
            <p class="text-[#3a3a3a] text-sm font-medium tracking-wide uppercase">CAMERA</p>
          </div>

          <!-- Bottom bar -->
          <div class="absolute bottom-0 left-0 right-0 bg-black/60 rounded-b-xl px-4 py-2 flex items-center justify-between">
            <span class="text-muted text-[10px]">Portal Gerbang Utama — Politeknik Negeri Bukittinggi</span>
            <span id="cam-time" class="text-muted text-[10px] font-mono">00:00:00</span>
          </div>
        </div>

        <!-- RIGHT: Info panels -->
        <div class="flex flex-col gap-3 w-56 flex-shrink-0">

          <!-- Statistik Live -->
          <div class="bg-card border border-divider rounded-xl p-4">
            <p class="text-white text-xs font-semibold mb-3">Statistik Live</p>

            <!-- Total -->
            <div class="flex items-center justify-between mb-3">
              <span class="text-muted text-[11px]">Total Terdeteksi</span>
              <span class="text-white text-sm font-bold">14</span>
            </div>

            <div class="h-px bg-divider mb-3"></div>

            <!-- Mobil row -->
            <div class="flex items-center justify-between mb-2.5">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-green flex-shrink-0"></span>
                <span class="text-muted text-[11px]">Mobil</span>
              </div>
              <span class="text-white text-sm font-bold">11</span>
            </div>

            <!-- Truck row -->
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-orange flex-shrink-0"></span>
                <span class="text-muted text-[11px]">Truck/Pickup</span>
              </div>
              <span class="text-white text-sm font-bold">3</span>
            </div>

            <!-- Mini progress bars -->
            <div class="mt-3 flex flex-col gap-1.5">
              <div>
                <div class="flex justify-between mb-1">
                  <span class="text-muted text-[9px]">Mobil</span>
                  <span class="text-muted text-[9px]">79%</span>
                </div>
                <div class="h-1 bg-divider rounded-full overflow-hidden">
                  <div class="h-full bg-green rounded-full" style="width:79%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-1">
                  <span class="text-muted text-[9px]">Truck/Pickup</span>
                  <span class="text-muted text-[9px]">21%</span>
                </div>
                <div class="h-1 bg-divider rounded-full overflow-hidden">
                  <div class="h-full bg-orange rounded-full" style="width:21%"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Log Deteksi -->
          <div class="bg-card border border-divider rounded-xl p-4 flex-1 flex flex-col min-h-0">
            <p class="text-white text-xs font-semibold mb-3">Log Deteksi</p>
            <div class="flex-1 overflow-y-auto flex items-center justify-center">
              <p class="text-muted text-[11px] italic text-center">Menunggu deteksi...</p>
            </div>
          </div>

          <!-- Info Model AI -->
          <div class="bg-card border border-divider rounded-xl p-4">
            <p class="text-white text-xs font-semibold mb-3">Info Model AI</p>

            <div class="flex flex-col gap-0 divide-y divide-divider">

              <!-- Model -->
              <div class="flex items-center justify-between py-2">
                <span class="text-muted text-[10px]">Model</span>
                <span class="text-white text-[10px] font-medium">Computer Vision</span>
              </div>

              <!-- Confidence -->
              <div class="flex items-center justify-between py-2">
                <span class="text-muted text-[10px]">Confidence</span>
                <span class="text-white text-[10px] font-medium">&ge; 60%</span>
              </div>

              <!-- Method -->
              <div class="flex items-center justify-between py-2">
                <span class="text-muted text-[10px]">Method</span>
                <span class="text-white text-[10px] font-medium text-right leading-tight">Virtual line<br>Counting</span>
              </div>

              <!-- Inference -->
              <div class="flex items-center justify-between py-2">
                <span class="text-muted text-[10px]">Inference</span>
                <div class="flex items-center gap-1.5">
                  <span class="w-1.5 h-1.5 rounded-full bg-green animate-pulse flex-shrink-0"></span>
                  <span class="text-white text-[10px] font-medium">~45ms</span>
                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- end right panels -->

      </div>
    </main>

  </div>

  <!-- ═══════════ SCRIPTS ═══════════ -->
  <script>
    // Live clock
    function updateClock() {
      const now = new Date();
      const days  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
      const months= ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      const hh = String(now.getHours()).padStart(2,'0');
      const mm = String(now.getMinutes()).padStart(2,'0');
      const ss = String(now.getSeconds()).padStart(2,'0');
      document.getElementById('clock').textContent    = `${hh}:${mm}:${ss}`;
      document.getElementById('cam-time').textContent = `${hh}:${mm}:${ss}`;
      document.getElementById('dateline').textContent =
        `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    }
    updateClock();
    setInterval(updateClock, 1000);
  </script>

</body>
</html>

What do you want to create?
