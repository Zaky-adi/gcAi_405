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

  <header class="flex items-center justify-between bg-sidebar border-b border-divider px-4 py-2.5 flex-shrink-0 z-20 relative">

    <div class="flex items-center gap-3">
      <button onclick="toggleSidebar()" class="md:hidden text-white hover:text-orange transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
      </button>

      <div class="flex items-center gap-2.5">
        <div class="w-9 h-9 bg-orange rounded-lg flex items-center justify-center flex-shrink-0">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
            <circle cx="12" cy="13" r="3"/>
          </svg>
        </div>
        <div class="leading-tight hidden sm:block">
          <p class="text-white text-sm font-bold leading-none">Gate Control <span class="text-orange">AI</span></p>
          <p class="text-muted text-[9px] mt-0.5 leading-tight max-w-[160px]">Sistem Monitoring &amp; Pengendalian Gerbang</p>
        </div>
      </div>
    </div>

    <div class="hidden lg:block text-center absolute left-1/2 transform -translate-x-1/2">
      <p class="text-white text-base font-semibold leading-none">Live View</p>
      <p class="text-muted text-xs mt-0.5">Streaming CCTV/Camera — Portal Gerbang Politeknik</p>
    </div>

    <div class="flex items-center gap-3">
      <button class="hidden sm:flex items-center gap-1.5 bg-[#2a1a1a] border border-red-800 text-red-400 rounded-md px-3 py-1 text-xs font-medium">
        <span class="pulse-dot"></span>
        Standby
      </button>
      <div class="text-right leading-tight">
        <p id="clock" class="text-white text-base sm:text-lg font-bold tracking-widest leading-none">00:00:00</p>
        <p id="dateline" class="text-muted text-[9px] sm:text-[10px] mt-0.5">Memuat Tanggal...</p>
      </div>
    </div>

  </header>

  <div class="flex flex-1 overflow-hidden relative">

    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-30 hidden md:hidden transition-opacity"></div>

    <aside id="appSidebar" class="absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-40 w-52 md:w-44 bg-sidebar border-r border-divider flex flex-col flex-shrink-0 overflow-y-auto py-3 h-full">

      <p class="text-muted text-[9px] font-semibold uppercase tracking-widest px-4 mb-2">Menu Utama</p>

      <nav class="flex flex-col gap-0.5 px-2">
        <a href="{{ url('/dashboard') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          Dashboard
        </a>
        <a href="{{ url('/liveview') }}" class="nav-link active flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-white">
          <svg class="w-4 h-4 text-orange flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
          Live view
        </a>
        <a href="{{ url('/reports') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          Laporan Kendaraan
        </a>
      </nav>

      <p class="text-muted text-[9px] font-semibold uppercase tracking-widest px-4 mt-5 mb-2">Pengaturan</p>

      <nav class="flex flex-col gap-0.5 px-2">
        <a href="{{ url('/jadwal') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 16 14"/></svg>
          Jadwal Operasional
        </a>
        <a href="{{ url('/perangkat') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>
          Perangkat IoT
        </a>
      </nav>

      <div class="mt-auto px-2 pb-4">
        <button onclick="logout()" class="w-full flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-red-400 hover:bg-red-500/10 transition-colors">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          Keluar
        </button>
      </div>

    </aside>

    <main class="flex-1 overflow-y-auto p-4 sm:p-5 bg-surface md:ml-0">

      <div class="flex flex-col lg:flex-row gap-3 lg:h-full" style="min-height: 0;">

        <div class="flex-1 min-w-0 camera-bg border border-divider rounded-xl flex items-center justify-center relative min-h-[300px] sm:min-h-[420px]">
          <div class="cam-corner tl"></div>
          <div class="cam-corner tr"></div>
          <div class="cam-corner bl"></div>
          <div class="cam-corner br"></div>

          <div class="absolute top-3 left-10 flex items-center gap-1.5 bg-black/50 rounded px-2 py-1">
            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse flex-shrink-0"></span>
            <span class="text-white text-[10px] font-semibold tracking-widest">LIVE</span>
          </div>

          <div class="absolute top-3 right-10 text-muted text-[10px]">CAM — 01</div>

          <div class="text-center flex flex-col items-center gap-3 z-10">
            <svg class="w-14 h-14 text-[#3a3a3a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
              <circle cx="12" cy="13" r="3"/>
            </svg>
            <p class="text-[#3a3a3a] text-sm font-medium tracking-wide uppercase">CAMERA</p>
          </div>

          <div class="absolute bottom-0 left-0 right-0 bg-black/60 rounded-b-xl px-4 py-2 flex flex-col sm:flex-row sm:items-center justify-between">
            <span class="text-muted text-[9px] sm:text-[10px] truncate">Portal Gerbang Utama — Politeknik Negeri Batam</span>
            <span id="cam-time" class="text-muted text-[9px] sm:text-[10px] font-mono mt-1 sm:mt-0">00:00:00</span>
          </div>
        </div>

        <div class="flex flex-col gap-3 w-full lg:w-56 flex-shrink-0">

          <div class="bg-card border border-divider rounded-xl p-4">
            <p class="text-white text-xs font-semibold mb-3">Statistik Live</p>

            <div class="flex items-center justify-between mb-3">
              <span class="text-muted text-[11px]">Total Terdeteksi</span>
              <span id="stat-total" class="text-white text-sm font-bold">0</span>
            </div>

            <div class="h-px bg-divider mb-3"></div>

            <div class="flex items-center justify-between mb-2.5">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-green flex-shrink-0"></span>
                <span class="text-muted text-[11px]">Mobil</span>
              </div>
              <span id="stat-mobil" class="text-white text-sm font-bold">0</span>
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-orange flex-shrink-0"></span>
                <span class="text-muted text-[11px]">Truck/Pickup</span>
              </div>
              <span id="stat-truck" class="text-white text-sm font-bold">0</span>
            </div>
          </div>

          <div class="bg-card border border-divider rounded-xl p-4 lg:flex-1 flex flex-col min-h-[150px] lg:min-h-0">
            <p class="text-white text-xs font-semibold mb-3">Log Deteksi</p>
            <div id="log-container" class="flex-1 overflow-y-auto flex flex-col gap-2">
              <p class="text-muted text-[11px] italic text-center">Menunggu deteksi...</p>
            </div>
          </div>

          <div class="bg-card border border-divider rounded-xl p-4">
            <p class="text-white text-xs font-semibold mb-3">Info Model AI</p>

            <div class="flex flex-col gap-0 divide-y divide-divider">
              <div class="flex items-center justify-between py-2">
                <span class="text-muted text-[10px]">Model</span>
                <span class="text-white text-[10px] font-medium">Computer Vision</span>
              </div>
              <div class="flex items-center justify-between py-2">
                <span class="text-muted text-[10px]">Confidence</span>
                <span class="text-white text-[10px] font-medium">&ge; 60%</span>
              </div>
              <div class="flex items-center justify-between py-2">
                <span class="text-muted text-[10px]">Method</span>
                <span class="text-white text-[10px] font-medium text-right leading-tight">Virtual line<br>Counting</span>
              </div>
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
      </div>
    </main>

  </div>

  <script>
    /* ── Autentikasi Check ── */
    const token = localStorage.getItem('firebase_token');
    if (!token) window.location.href = "{{ url('/login') }}";

    function logout() {
        localStorage.removeItem('firebase_token');
        localStorage.removeItem('firebase_uid');
        window.location.href = "{{ url('/login') }}";
    }

    /* ── Mobile Sidebar Toggle Logic ── */
    function toggleSidebar() {
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('-translate-x-full');
        if (overlay.classList.contains('hidden')) {
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.add('opacity-100'), 10);
        } else {
            overlay.classList.remove('opacity-100');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
    }

    /* ── Live clock ── */
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

    /* ── Fetch Realtime Data GraphQL ── */
    async function fetchRealtimeData() {
        const query = `
            query {
                vehicleLogs(limit: 15, orderBy: [{ column: "created_at", order: DESC }]) {
                    id
                    vehicle_type
                    confidence_score
                    detected_at
                }
            }
        `;

        try {
            const response = await fetch('/graphql', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}` // KOMENTAR DIHAPUS, TOKEN DIAKTIFKAN!
                },
                body: JSON.stringify({ query })
            });

            const result = await response.json();
            if (result.errors) throw new Error(result.errors[0].message);

            const logs = result.data.vehicleLogs;
            
            // Olah data untuk statistik (15 log terakhir)
            let total = logs.length;
            let mobil = logs.filter(log => log.vehicle_type === 'mobil').length;
            let truck = logs.filter(log => log.vehicle_type === 'truck' || log.vehicle_type === 'pickup').length;

            // Update angka di layar
            document.getElementById('stat-total').textContent = total;
            document.getElementById('stat-mobil').textContent = mobil;
            document.getElementById('stat-truck').textContent = truck;

            // Update Log List HTML
            const logContainer = document.getElementById('log-container');
            if (logs.length === 0) {
                logContainer.innerHTML = `<p class="text-muted text-[11px] italic text-center">Belum ada deteksi.</p>`;
            } else {
                logContainer.innerHTML = ''; 
                logs.forEach(log => {
                    const warna = (log.vehicle_type === 'mobil') ? 'text-green' : 'text-orange';
                    const jam = new Date(log.detected_at).toLocaleTimeString('id-ID');
                    
                    const item = `
                    <div class="flex items-center justify-between border-b border-divider pb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-white text-[10px] font-mono uppercase ${warna}">${log.vehicle_type}</span>
                            <span class="text-muted text-[9px]">${(log.confidence_score * 100).toFixed(0)}%</span>
                        </div>
                        <span class="text-muted text-[9px]">${jam}</span>
                    </div>`;
                    logContainer.insertAdjacentHTML('beforeend', item);
                });
            }

        } catch (error) {
            console.error("Gagal menarik data realtime:", error);
        }
    }

    // Panggil saat halaman dibuka
    document.addEventListener('DOMContentLoaded', fetchRealtimeData);

    // Refresh otomatis setiap 2.5 detik
    setInterval(fetchRealtimeData, 2500);
  </script>

</body>
</html>