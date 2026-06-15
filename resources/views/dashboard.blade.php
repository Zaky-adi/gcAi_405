<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gate Control AI — Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
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
    .stat-card { box-shadow: 0 1px 3px rgba(0,0,0,.5); }
    .pulse-dot::before {
      content: '';
      display: inline-block;
      width: 7px; height: 7px;
      border-radius: 50%;
      background: #ef4444;
      margin-right: 5px;
      animation: pulse 1.4s infinite;
    }
    @keyframes pulse {
      0%,100% { opacity: 1; }
      50%      { opacity: .3; }
    }
    .badge { font-size: 10px; font-weight: 600; letter-spacing: .03em; border-radius: 4px; padding: 2px 7px; }
  </style>
</head>
<body class="bg-surface font-sans text-white flex flex-col h-screen overflow-hidden">

  <!-- ═══════════════════════════════ TOP HEADER ═══════════════════════════════ -->
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
        <p class="text-muted text-[9px] mt-0.5 leading-tight max-w-[160px]">Sistem Monitoring & Pengendalian Gerbang Portal Politeknik Negeri Bukittinggi</p>
      </div>
    </div>

    <!-- Center title -->
    <div class="hidden md:block text-center">
      <p class="text-white text-base font-semibold leading-none">Dashboard Monitoring</p>
      <p class="text-muted text-xs mt-0.5">Ringkasan real-time kendaraan masuk</p>
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

  <!-- ═══════════════════════════════ BODY (sidebar + main) ═══════════════════════════════ -->
  <div class="flex flex-1 overflow-hidden">

    <!-- ────────────── SIDEBAR ────────────── -->
    <aside class="w-44 bg-sidebar border-r border-divider flex flex-col flex-shrink-0 overflow-y-auto py-3">

      <p class="text-muted text-[9px] font-semibold uppercase tracking-widest px-4 mb-2">Menu Utama</p>

      <nav class="flex flex-col gap-0.5 px-2">
        <a href="{{ url('/dashboard') }}" class="nav-link active flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-white">
          <!-- grid icon -->
          <svg class="w-4 h-4 text-orange flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
          </svg>
          Dashboard
        </a>
        <a href="{{ url('/liveview') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <!-- video icon -->
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
          </svg>
          Live view
        </a>
        <a href="{{ url('/reports') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <!-- file icon -->
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
          <!-- clock icon -->
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
          Jadwal Operasional
        </a>
        <a href="{{ url('/perangkat') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <!-- cpu icon -->
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

    <!-- ────────────── MAIN CONTENT ────────────── -->
    <main class="flex-1 overflow-y-auto p-4 flex flex-col gap-4 bg-surface">

      <!-- STAT CARDS -->
      <div class="grid grid-cols-4 gap-3 flex-shrink-0">

        <!-- Hari Ini -->
        <div class="stat-card bg-card rounded-xl p-4 flex flex-col gap-2 border border-divider">
          <div class="flex items-center justify-between">
            <span class="text-muted text-[10px] font-semibold uppercase tracking-wider">Hari Ini</span>
            <div class="w-7 h-7 bg-[#1a3a2a] rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
              </svg>
            </div>
          </div>
          <p class="text-white text-3xl font-bold leading-none">943</p>
          <p class="text-muted text-[11px]">Kendaraan masuk</p>
        </div>

        <!-- Minggu Ini -->
        <div class="stat-card bg-card rounded-xl p-4 flex flex-col gap-2 border border-divider">
          <div class="flex items-center justify-between">
            <span class="text-muted text-[10px] font-semibold uppercase tracking-wider">Minggu Ini</span>
            <div class="w-7 h-7 bg-[#1a2a3a] rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
              </svg>
            </div>
          </div>
          <p class="text-white text-3xl font-bold leading-none">847</p>
          <p class="text-green text-[11px] flex items-center gap-1">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="18 15 12 9 6 15"/></svg>
            +12.5%
          </p>
        </div>

        <!-- Mobil -->
        <div class="stat-card bg-card rounded-xl p-4 flex flex-col gap-2 border border-divider">
          <div class="flex items-center justify-between">
            <span class="text-muted text-[10px] font-semibold uppercase tracking-wider">Mobil</span>
            <div class="w-7 h-7 bg-[#1a3a2a] rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="1" y="3" width="15" height="13" rx="2"/>
                <path d="M16 8h4l3 3v5h-7V8z"/>
                <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
              </svg>
            </div>
          </div>
          <p class="text-white text-3xl font-bold leading-none">646</p>
          <p class="text-muted text-[11px]">Terdeteksi hari ini</p>
        </div>

        <!-- Truck/Pickup -->
        <div class="stat-card bg-card rounded-xl p-4 flex flex-col gap-2 border border-divider">
          <div class="flex items-center justify-between">
            <span class="text-muted text-[10px] font-semibold uppercase tracking-wider">Truck/Pickup</span>
            <div class="w-7 h-7 bg-[#3a2a1a] rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-orange" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M1 3h15v13H1zM16 8h4l3 3v5h-7V8z"/>
                <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
              </svg>
            </div>
          </div>
          <p class="text-white text-3xl font-bold leading-none">409</p>
          <p class="text-muted text-[11px]">Terdeteksi hari ini</p>
        </div>

      </div>

      <!-- CAMERA FEED + DONUT CHART -->
      <div class="grid grid-cols-3 gap-3 flex-shrink-0" style="min-height:230px">

        <!-- Camera feed (spans 2 cols) -->
        <div class="col-span-2 bg-card border border-divider rounded-xl flex items-center justify-center">
          <div class="text-center">
            <svg class="w-10 h-10 text-divider mx-auto mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
              <circle cx="12" cy="13" r="3"/>
              <line x1="4" y1="4" x2="20" y2="20" stroke="currentColor"/>
            </svg>
            <p class="text-muted text-xs italic">capture kosong</p>
          </div>
        </div>

        <!-- Donut chart -->
        <div class="bg-card border border-divider rounded-xl p-4 flex flex-col">
          <p class="text-white text-xs font-semibold mb-0.5">Distribusi Jenis</p>
          <p class="text-muted text-[10px] mb-3">Persentase kendaraan hari ini</p>
          <div class="flex-1 flex items-center justify-center">
            <canvas id="donutChart" width="160" height="160" style="max-width:160px;max-height:160px;"></canvas>
          </div>
          <!-- Legend -->
          <div class="flex flex-col gap-1.5 mt-3">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-green flex-shrink-0"></span>
                <span class="text-muted text-[10px]">Mobil</span>
              </div>
              <span class="text-white text-[10px] font-semibold">41%</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-orange flex-shrink-0"></span>
                <span class="text-muted text-[10px]">Truk/Pickup</span>
              </div>
              <span class="text-white text-[10px] font-semibold">7%</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-[#444] flex-shrink-0"></span>
                <span class="text-muted text-[10px]">Lainnya</span>
              </div>
              <span class="text-white text-[10px] font-semibold">52%</span>
            </div>
          </div>
        </div>

      </div>

      <!-- AKTIVITAS TERBARU + STATUS SISTEM -->
      <div class="grid grid-cols-2 gap-3 flex-shrink-0">

        <!-- Aktivitas Terbaru -->
        <div class="bg-card border border-divider rounded-xl p-4">
          <div class="flex items-center justify-between mb-3">
            <div>
              <p class="text-white text-xs font-semibold">Aktivitas Terbaru</p>
              <p class="text-muted text-[10px]">5 deteksi terakhir</p>
            </div>
            <a href="#" class="text-orange text-[11px] font-medium flex items-center gap-1 hover:underline">
              Lihat semua
              <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
              </svg>
            </a>
          </div>

          <div class="flex flex-col divide-y divide-divider">

            <!-- Activity row -->
            <div class="flex items-center gap-3 py-2.5">
              <div class="w-8 h-8 bg-[#3a2a1a] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-orange" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M1 3h15v13H1zM16 8h4l3 3v5h-7V8z"/>
                  <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white text-[11px] font-medium">Truck/Pickup</p>
                <p class="text-muted text-[10px]">14/4/2026</p>
              </div>
              <span class="text-muted text-[10px] flex-shrink-0">18:12:53</span>
            </div>

            <div class="flex items-center gap-3 py-2.5">
              <div class="w-8 h-8 bg-[#1a3a2a] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="1" y="3" width="15" height="13" rx="2"/>
                  <path d="M16 8h4l3 3v5h-7V8z"/>
                  <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white text-[11px] font-medium">Mobil</p>
                <p class="text-muted text-[10px]">14/4/2026</p>
              </div>
              <span class="text-muted text-[10px] flex-shrink-0">18:12:50</span>
            </div>

            <div class="flex items-center gap-3 py-2.5">
              <div class="w-8 h-8 bg-[#1a3a2a] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="1" y="3" width="15" height="13" rx="2"/>
                  <path d="M16 8h4l3 3v5h-7V8z"/>
                  <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white text-[11px] font-medium">Mobil</p>
                <p class="text-muted text-[10px]">14/4/2026</p>
              </div>
              <span class="text-muted text-[10px] flex-shrink-0">18:12:10</span>
            </div>

            <div class="flex items-center gap-3 py-2.5">
              <div class="w-8 h-8 bg-[#3a2a1a] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-orange" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M1 3h15v13H1zM16 8h4l3 3v5h-7V8z"/>
                  <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white text-[11px] font-medium">Truck/Pickup</p>
                <p class="text-muted text-[10px]">14/4/2026</p>
              </div>
              <span class="text-muted text-[10px] flex-shrink-0">18:11:30</span>
            </div>

            <div class="flex items-center gap-3 py-2.5">
              <div class="w-8 h-8 bg-[#1a3a2a] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="1" y="3" width="15" height="13" rx="2"/>
                  <path d="M16 8h4l3 3v5h-7V8z"/>
                  <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white text-[11px] font-medium">Mobil</p>
                <p class="text-muted text-[10px]">14/4/2026</p>
              </div>
              <span class="text-muted text-[10px] flex-shrink-0">18:11:03</span>
            </div>

          </div>
        </div>

        <!-- Status Sistem -->
        <div class="bg-card border border-divider rounded-xl p-4">
          <p class="text-white text-xs font-semibold mb-4">Status Sistem</p>

          <div class="flex flex-col gap-3">

            <!-- Koneksi IoT -->
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 bg-[#1a3a2a] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <circle cx="12" cy="12" r="2"/>
                  <path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.49-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white text-[11px] font-medium">Koneksi IoT</p>
                <p class="text-muted text-[10px] truncate">MQTT Broker Port</p>
              </div>
              <span class="badge bg-green/20 text-green">Terhubung</span>
            </div>

            <!-- Kamera CCTV -->
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 bg-[#1a2a3a] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                  <circle cx="12" cy="13" r="3"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white text-[11px] font-medium">Kamera CCTV</p>
                <p class="text-muted text-[10px] truncate">Webcam Full HD 1080p</p>
              </div>
              <span class="badge bg-blue-500/20 text-blue-400">Aktif</span>
            </div>

            <!-- Model AI (YOLO) -->
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 bg-[#2a1a3a] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-purple-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                  <path d="M2 17l10 5 10-5"/>
                  <path d="M2 12l10 5 10-5"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white text-[11px] font-medium">Model AI (YOLO)</p>
                <p class="text-muted text-[10px] truncate">Model Live Detected</p>
              </div>
              <span class="badge bg-green/20 text-green">Running</span>
            </div>

            <!-- Edge Device -->
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 bg-[#3a2a1a] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-orange" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="4" y="4" width="16" height="16" rx="2"/>
                  <rect x="9" y="9" width="6" height="6"/>
                  <line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/>
                  <line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/>
                  <line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/>
                  <line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white text-[11px] font-medium">Edge Device</p>
                <p class="text-muted text-[10px] truncate">Raspberry Pi 4 Model B</p>
              </div>
              <span class="badge bg-green/20 text-green">Online</span>
            </div>

            <!-- Database Server -->
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 bg-[#2a2a2a] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <ellipse cx="12" cy="5" rx="9" ry="3"/>
                  <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/>
                  <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white text-[11px] font-medium">Database Server</p>
                <p class="text-muted text-[10px] truncate">MySQL — Localhost</p>
              </div>
              <span class="badge bg-green/20 text-green">Terhubung</span>
            </div>

          </div>
        </div>

      </div>

    </main>
  </div>

  <script>
    /* ── Live clock ── */
    function updateClock() {
      const now = new Date();
      const hh  = String(now.getHours()).padStart(2,'0');
      const mm  = String(now.getMinutes()).padStart(2,'0');
      const ss  = String(now.getSeconds()).padStart(2,'0');
      document.getElementById('clock').textContent = `${hh}:${mm}:${ss}`;

      const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
      const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      document.getElementById('dateline').textContent =
        `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    /* ── Donut chart ── */
    const ctx = document.getElementById('donutChart').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Mobil', 'Truk/Pickup', 'Lainnya'],
        datasets: [{
          data: [41, 7, 52],
          backgroundColor: ['#22c55e', '#f97316', '#3a3a3a'],
          borderWidth: 0,
          hoverOffset: 4,
        }],
      },
      options: {
        cutout: '68%',
        responsive: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => ` ${ctx.label}: ${ctx.parsed}%`,
            },
            backgroundColor: '#1e1e1e',
            titleColor: '#fff',
            bodyColor: '#aaa',
            borderColor: '#444',
            borderWidth: 1,
          },
        },
        animation: { animateRotate: true, duration: 800 },
      },
    });
  </script>

</body>
</html>
