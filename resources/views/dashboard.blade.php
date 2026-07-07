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
    ::-webkit-scrollbar { width: 4px; height: 4px; }
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

  <header class="flex items-center justify-between bg-sidebar border-b border-divider px-4 py-2.5 flex-shrink-0 z-20 relative">
    
    <div class="flex items-center gap-3">
      <button onclick="toggleSidebar()" class="md:hidden text-white hover:text-orange transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
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
          <p class="text-muted text-[9px] mt-0.5 leading-tight max-w-[160px]">Politeknik Negeri Batam</p>
        </div>
      </div>
    </div>

    <div class="hidden lg:block text-center absolute left-1/2 transform -translate-x-1/2">
      <p class="text-white text-base font-semibold leading-none">Dashboard Monitoring</p>
      <p class="text-muted text-xs mt-0.5">Ringkasan real-time kendaraan masuk</p>
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
        <a href="{{ url('/dashboard') }}" class="nav-link active flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-white">
          <svg class="w-4 h-4 text-orange flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
          </svg>
          Dashboard
        </a>
        <a href="{{ url('/liveview') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
          </svg>
          Live view
        </a>
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
      
      <div class="mt-auto px-2 pb-4">
        <button onclick="logout()" class="w-full flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-red-400 hover:bg-red-500/10 transition-colors">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          Keluar
        </button>
      </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-4 flex flex-col gap-4 bg-surface md:ml-0">

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 flex-shrink-0">
        <div class="stat-card bg-card rounded-xl p-4 flex flex-col gap-2 border border-divider">
          <div class="flex items-center justify-between">
            <span class="text-muted text-[10px] font-semibold uppercase tracking-wider">Hari Ini</span>
            <div class="w-7 h-7 bg-[#1a3a2a] rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
          </div>
          <p id="stat-hari-ini" class="text-white text-3xl font-bold leading-none">...</p>
          <p class="text-muted text-[11px]">Kendaraan masuk</p>
        </div>

        <div class="stat-card bg-card rounded-xl p-4 flex flex-col gap-2 border border-divider">
          <div class="flex items-center justify-between">
            <span class="text-muted text-[10px] font-semibold uppercase tracking-wider">Minggu Ini</span>
            <div class="w-7 h-7 bg-[#1a2a3a] rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
          </div>
          <p id="stat-minggu-ini" class="text-white text-3xl font-bold leading-none">...</p>
          <p id="stat-minggu-persen" class="text-green text-[11px] flex items-center gap-1">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="18 15 12 9 6 15"/></svg> 0%
          </p>
        </div>

        <div class="stat-card bg-card rounded-xl p-4 flex flex-col gap-2 border border-divider">
          <div class="flex items-center justify-between">
            <span class="text-muted text-[10px] font-semibold uppercase tracking-wider">Mobil</span>
            <div class="w-7 h-7 bg-[#1a3a2a] rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
          </div>
          <p id="stat-mobil" class="text-white text-3xl font-bold leading-none">...</p>
          <p class="text-muted text-[11px]">Terdeteksi hari ini</p>
        </div>

        <div class="stat-card bg-card rounded-xl p-4 flex flex-col gap-2 border border-divider">
          <div class="flex items-center justify-between">
            <span class="text-muted text-[10px] font-semibold uppercase tracking-wider">Truck/Pickup</span>
            <div class="w-7 h-7 bg-[#3a2a1a] rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-orange" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 3h15v13H1zM16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
          </div>
          <p id="stat-truk" class="text-white text-3xl font-bold leading-none">...</p>
          <p class="text-muted text-[11px]">Terdeteksi hari ini</p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 flex-shrink-0" style="min-height:230px">
        <div class="col-span-1 lg:col-span-2 bg-card border border-divider rounded-xl flex items-center justify-center py-10 lg:py-0">
          <div class="text-center">
            <svg class="w-10 h-10 text-divider mx-auto mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/><line x1="4" y1="4" x2="20" y2="20" stroke="currentColor"/></svg>
            <p class="text-muted text-xs italic">Menunggu streaming CCTV...</p>
          </div>
        </div>

        <div class="bg-card border border-divider rounded-xl p-4 flex flex-col">
          <p class="text-white text-xs font-semibold mb-0.5">Distribusi Jenis</p>
          <p class="text-muted text-[10px] mb-3">Persentase kendaraan hari ini</p>
          <div class="flex-1 flex items-center justify-center min-h-[160px]">
            <canvas id="donutChart" width="160" height="160" style="max-width:160px;max-height:160px;"></canvas>
          </div>
          <div class="flex flex-col gap-1.5 mt-3">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green flex-shrink-0"></span><span class="text-muted text-[10px]">Mobil</span></div>
              <span id="legend-mobil" class="text-white text-[10px] font-semibold">0%</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-orange flex-shrink-0"></span><span class="text-muted text-[10px]">Truk/Pickup</span></div>
              <span id="legend-truk" class="text-white text-[10px] font-semibold">0%</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#444] flex-shrink-0"></span><span class="text-muted text-[10px]">Lainnya</span></div>
              <span id="legend-lainnya" class="text-white text-[10px] font-semibold">0%</span>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 flex-shrink-0">
        <div class="bg-card border border-divider rounded-xl p-4">
          <div class="flex items-center justify-between mb-3">
            <div>
              <p class="text-white text-xs font-semibold">Aktivitas Terbaru</p>
              <p class="text-muted text-[10px]">5 deteksi terakhir</p>
            </div>
            <a href="{{ url('/reports') }}" class="text-orange text-[11px] font-medium flex items-center gap-1 hover:underline">Lihat semua</a>
          </div>
          <div id="aktivitas-container" class="flex flex-col divide-y divide-divider">
            <p class="text-muted text-xs italic py-2">Memuat data...</p>
          </div>
        </div>

        <div class="bg-card border border-divider rounded-xl p-4">
          <p class="text-white text-xs font-semibold mb-4">Status Sistem</p>
          <div id="status-container" class="flex flex-col gap-3">
             <p class="text-muted text-xs italic">Memuat status...</p>
          </div>
        </div>
      </div>

    </main>
  </div>

  <script>
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

    /* ── Autentikasi Check ── */
    const token = localStorage.getItem('firebase_token');
    const uid = localStorage.getItem('firebase_uid');
    
    if (!token) {
        window.location.href = "{{ url('/login') }}";
    }

    function logout() {
        localStorage.removeItem('firebase_token');
        localStorage.removeItem('firebase_uid');
        window.location.href = "{{ url('/login') }}";
    }

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

    /* ── Donut chart Initialization ── */
    let donutChartInstance;
    const ctx = document.getElementById('donutChart').getContext('2d');
    donutChartInstance = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Mobil', 'Truk/Pickup', 'Lainnya'],
        datasets: [{
          data: [0, 0, 0], 
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
            callbacks: { label: (ctx) => ` ${ctx.label}: ${ctx.parsed}%` },
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

    /* ── FUNGSI REAL-TIME UTAMA ── */
    async function fetchDashboardData() {
        const query = `
            query {
                totalKendaraanMasukHariIni
                totalKendaraanMasukMingguIni { total persentase }
                totalMobilHariIni
                totalTrukHariIni
                aktivitasTerbaru { id vehicle_type detected_at }
                statusSistem { id nama deskripsi status_text is_active }
            }
        `;

        try {
            const response = await fetch('/graphql', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}` 
                },
                body: JSON.stringify({ query })
            });

            const result = await response.json();

            if (result.errors) {
                console.error("GraphQL Errors:", result.errors);
                return;
            }

            const data = result.data;

            // 1. Update Stat Cards
            document.getElementById('stat-hari-ini').innerText = data.totalKendaraanMasukHariIni;
            document.getElementById('stat-minggu-ini').innerText = data.totalKendaraanMasukMingguIni.total;
            document.getElementById('stat-mobil').innerText = data.totalMobilHariIni;
            document.getElementById('stat-truk').innerText = data.totalTrukHariIni;

            // Persentase Minggu Ini
            const persenMingguIni = data.totalKendaraanMasukMingguIni.persentase;
            const isNaik = persenMingguIni >= 0;
            const persentaseEl = document.getElementById('stat-minggu-persen');
            persentaseEl.className = `${isNaik ? 'text-green' : 'text-red-400'} text-[11px] flex items-center gap-1`;
            persentaseEl.innerHTML = `
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    ${isNaik ? '<polyline points="18 15 12 9 6 15"/>' : '<polyline points="6 9 12 15 18 9"/>'}
                </svg>
                ${isNaik ? '+' : ''}${persenMingguIni}%
            `;

            // 2. Update Donut Chart
            const total = data.totalKendaraanMasukHariIni;
            const mobil = data.totalMobilHariIni;
            const truk = data.totalTrukHariIni;
            let lainnya = total - mobil - truk;
            if(lainnya < 0) lainnya = 0; 

            let pMobil = total > 0 ? Math.round((mobil / total) * 100) : 0;
            let pTruk = total > 0 ? Math.round((truk / total) * 100) : 0;
            let pLainnya = total > 0 ? Math.round((lainnya / total) * 100) : 0;

            donutChartInstance.data.datasets[0].data = [pMobil, pTruk, pLainnya];
            donutChartInstance.update();

            document.getElementById('legend-mobil').innerText = pMobil + '%';
            document.getElementById('legend-truk').innerText = pTruk + '%';
            document.getElementById('legend-lainnya').innerText = pLainnya + '%';

            // 3. Render Aktivitas Terbaru (Real-time List)
            const aktivitasContainer = document.getElementById('aktivitas-container');
            
            if (data.aktivitasTerbaru && data.aktivitasTerbaru.length > 0) {
                aktivitasContainer.innerHTML = ''; // Kosongkan daftar lama
                data.aktivitasTerbaru.forEach(log => {
                    const dateObj = new Date(log.detected_at);
                    const tgl = `${dateObj.getDate()}/${dateObj.getMonth()+1}/${dateObj.getFullYear()}`;
                    const jam = `${String(dateObj.getHours()).padStart(2,'0')}:${String(dateObj.getMinutes()).padStart(2,'0')}:${String(dateObj.getSeconds()).padStart(2,'0')}`;
                    
                    const vType = log.vehicle_type ? log.vehicle_type.toLowerCase() : '';
                    const isTruk = vType === 'truk' || vType === 'truck' || vType === 'pickup';
                    
                    const iconBg = isTruk ? 'bg-[#3a2a1a]' : 'bg-[#1a3a2a]';
                    const iconColor = isTruk ? 'text-orange' : 'text-green';
                    const svgIcon = isTruk 
                        ? `<path d="M1 3h15v13H1zM16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>`
                        : `<rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>`;

                    const rowHTML = `
                    <div class="flex items-center gap-3 py-2.5">
                      <div class="w-8 h-8 ${iconBg} rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 ${iconColor}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${svgIcon}</svg>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-white text-[11px] font-medium capitalize">${log.vehicle_type}</p>
                        <p class="text-muted text-[10px]">${tgl}</p>
                      </div>
                      <span class="text-muted text-[10px] flex-shrink-0">${jam}</span>
                    </div>`;
                    
                    aktivitasContainer.insertAdjacentHTML('beforeend', rowHTML);
                });
            } else {
                aktivitasContainer.innerHTML = '<p class="text-muted text-xs italic py-2">Belum ada deteksi hari ini.</p>';
            }

            // 4. Render Status Sistem
            const statusContainer = document.getElementById('status-container');
            
            if (data.statusSistem && data.statusSistem.length > 0) {
                statusContainer.innerHTML = ''; // Kosongkan status lama
                data.statusSistem.forEach(sys => {
                    const badgeBg = sys.is_active ? 'bg-green/20 text-green' : 'bg-red-500/20 text-red-400';
                    const rowHTML = `
                    <div class="flex items-center gap-3">
                      <div class="w-9 h-9 bg-[#2a2a2a] rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-white text-[11px] font-medium">${sys.nama}</p>
                        <p class="text-muted text-[10px] truncate">${sys.deskripsi}</p>
                      </div>
                      <span class="badge ${badgeBg}">${sys.status_text}</span>
                    </div>`;
                    statusContainer.insertAdjacentHTML('beforeend', rowHTML);
                });
            }

        } catch (error) {
            console.error("Gagal mengambil data Dashboard:", error);
        }
    }

    // -- LOGIKA SMART POLLING --
    let lastKnownLogId = null;

    async function checkNewData() {
        // Query SUPER RINGAN: Hanya minta 1 ID terakhir, tanpa minta data lain
        const query = `
            query {
                vehicleLogs(limit: 1, orderBy: [{ column: "created_at", order: DESC }]) {
                    id
                }
            }
        `;

        try {
            const response = await fetch('/graphql', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}` 
                },
                body: JSON.stringify({ query })
            });

            const result = await response.json();
            if (result.errors || !result.data.vehicleLogs || result.data.vehicleLogs.length === 0) {
                // Jika database kosong, tetap jalankan fetch 1 kali saat awal
                if (lastKnownLogId === null) fetchDashboardData();
                return;
            }

            const latestId = result.data.vehicleLogs[0].id;
            
            // Logika Pintar:
            if (lastKnownLogId === null) {
                // 1. Jika baru buka web pertama kali, simpan ID dan tarik semua data
                lastKnownLogId = latestId;
                fetchDashboardData();
            } else if (lastKnownLogId !== latestId) {
                // 2. JIKA ADA DATA BARU MASUK! Simpan ID baru, dan perbarui Dashboard
                console.log("Ada kendaraan baru! Mengupdate Dashboard...");
                lastKnownLogId = latestId;
                fetchDashboardData();
            }
            // 3. Jika ID masih sama, jangan lakukan apa-apa (Hemat Server)

        } catch (error) {
            console.error("Gagal mengecek pembaruan data:", error);
        }
    }

    // Jalankan pengecekan ringan ini setiap 2 detik
    setInterval(checkNewData, 2000);
    // Pengecekan pertama saat web dibuka
    checkNewData();
  </script>
</body>
</html>