<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gate Control AI — Laporan Kendaraan</title>
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
            inputbg:  '#2e2e2e',
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
    /* custom date input appearance */
    input[type="date"]::-webkit-calendar-picker-indicator {
      filter: invert(0.5);
      cursor: pointer;
    }
    /* table row hover */
    tbody tr:hover { background: rgba(255,255,255,.04); }
    /* thin scrollbar for table area */
    .table-scroll::-webkit-scrollbar { width: 4px; height: 4px; }
    .table-scroll::-webkit-scrollbar-track { background: #1e1e1e; }
    .table-scroll::-webkit-scrollbar-thumb { background: #444; border-radius: 4px; }
  </style>
</head>
<body class="bg-surface font-sans text-white flex flex-col h-screen overflow-hidden">

  <!-- ═══════════════════════ TOP HEADER ═══════════════════════ -->
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
      <p class="text-white text-base font-semibold leading-none">Laporan Kendaraan</p>
      <p class="text-muted text-xs mt-0.5">Rekap data kendaraan masuk</p>
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

  <!-- ═══════════════════════ BODY ═══════════════════════ -->
  <div class="flex flex-1 overflow-hidden">

    <!-- ─────────── SIDEBAR ─────────── -->
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
        <!-- Live View -->
        <a href="{{ url('/liveview') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
          </svg>
          Live view
        </a>
        <!-- Laporan Kendaraan — ACTIVE -->
        <a href="{{ url('/reports') }}" class="nav-link active flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-white">
          <svg class="w-4 h-4 text-orange flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
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

    <!-- ─────────── MAIN CONTENT ─────────── -->
    <main class="flex-1 overflow-y-auto p-4 flex flex-col gap-4 bg-surface">

      <!-- FILTER ROW -->
      <div class="flex flex-wrap items-end gap-3 flex-shrink-0">

        <!-- Dari Tanggal -->
        <div class="flex flex-col gap-1">
          <label class="text-muted text-[10px] font-semibold uppercase tracking-wider">Dari Tanggal</label>
          <div class="relative">
            <input type="date" value="2026-04-15"
              class="bg-inputbg border border-divider text-white text-xs rounded-md px-3 py-2 pr-9 appearance-none focus:outline-none focus:border-orange transition-colors w-36"
              style="color-scheme: dark;" />
          </div>
        </div>

        <!-- Sampai Tanggal -->
        <div class="flex flex-col gap-1">
          <label class="text-muted text-[10px] font-semibold uppercase tracking-wider">Sampai Tanggal</label>
          <div class="relative">
            <input type="date" value="2026-04-15"
              class="bg-inputbg border border-divider text-white text-xs rounded-md px-3 py-2 pr-9 appearance-none focus:outline-none focus:border-orange transition-colors w-36"
              style="color-scheme: dark;" />
          </div>
        </div>

        <!-- Jenis Kendaraan -->
        <div class="flex flex-col gap-1">
          <label class="text-muted text-[10px] font-semibold uppercase tracking-wider">Jenis Kendaraan</label>
          <select class="bg-inputbg border border-divider text-white text-xs rounded-md px-3 py-2 focus:outline-none focus:border-orange transition-colors w-32 appearance-none cursor-pointer"
                  style="color-scheme: dark;">
            <option value="all">Semua</option>
            <option value="mobil" selected>Mobil</option>
            <option value="truck">Truck/Pickup</option>
          </select>
        </div>

        <!-- Filter button -->
        <button class="flex items-center gap-2 bg-inputbg border border-divider text-white text-xs font-medium rounded-md px-4 py-2 hover:border-orange hover:text-orange transition-colors">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
          </svg>
          Filter
        </button>

        <!-- Export CSV button -->
        <button class="flex items-center gap-2 bg-inputbg border border-divider text-white text-xs font-medium rounded-md px-4 py-2 hover:border-green hover:text-green transition-colors">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
          Export CSV
        </button>

      </div>

      <!-- STAT CARDS -->
      <div class="grid grid-cols-3 gap-3 flex-shrink-0">

        <!-- Total Filter -->
        <div class="bg-card border border-divider rounded-xl p-4 flex flex-col gap-1">
          <p class="text-muted text-[10px] font-semibold uppercase tracking-wider">Total Filter</p>
          <p class="text-white text-3xl font-bold leading-none mt-1">44</p>
          <p class="text-muted text-[11px]">Kendaraan ditemukan</p>
        </div>

        <!-- Rata-rata/Hari -->
        <div class="bg-card border border-divider rounded-xl p-4 flex flex-col gap-1">
          <p class="text-muted text-[10px] font-semibold uppercase tracking-wider">Rata-rata/Hari</p>
          <p class="text-white text-3xl font-bold leading-none mt-1">44</p>
          <p class="text-muted text-[11px]">Kendaraan per hari</p>
        </div>

        <!-- Puncak Tertinggi -->
        <div class="bg-card border border-divider rounded-xl p-4 flex flex-col gap-1">
          <p class="text-muted text-[10px] font-semibold uppercase tracking-wider">Puncak Tertinggi</p>
          <p class="text-white text-3xl font-bold leading-none mt-1">10:00 <span class="text-xl text-muted font-semibold">(9)</span></p>
          <p class="text-muted text-[11px]">Jam tersibuk</p>
        </div>

      </div>

      <!-- DATA TABLE -->
      <div class="bg-card border border-divider rounded-xl flex-1 flex flex-col overflow-hidden min-h-0">

        <!-- Table header -->
        <div class="flex-shrink-0 border-b border-divider">
          <table class="w-full text-[11px]">
            <thead>
              <tr class="text-muted uppercase tracking-wider">
                <th class="text-center px-4 py-3 font-semibold w-14">No</th>
                <th class="text-center px-4 py-3 font-semibold">Waktu Masuk</th>
                <th class="text-center px-4 py-3 font-semibold">Jenis Kendaraan</th>
                <th class="text-center px-4 py-3 font-semibold">Confidence</th>
                <th class="text-center px-4 py-3 font-semibold">Sumber</th>
              </tr>
            </thead>
          </table>
        </div>

        <!-- Table body scroll -->
        <div class="flex-1 overflow-y-auto table-scroll">
          <table class="w-full text-[11px]">
            <tbody id="tableBody">
              <!-- rows injected by JS -->
            </tbody>
          </table>
        </div>

      </div>
      <!-- end table card -->

    </main>
  </div>

  <!-- ═══════════════════════ SCRIPTS ═══════════════════════ -->
  <script>
    // ── Live clock ──
    function updateClock() {
      const now   = new Date();
      const days  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
      const months= ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      const hh = String(now.getHours()).padStart(2,'0');
      const mm = String(now.getMinutes()).padStart(2,'0');
      const ss = String(now.getSeconds()).padStart(2,'0');
      document.getElementById('clock').textContent   = `${hh}:${mm}:${ss}`;
      document.getElementById('dateline').textContent =
        `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ── Table data ──
    const rows = [
      { no:1,  waktu:'15/4/2026, 17:55:44', jenis:'Truck/Pickup', confidence:'63.0%', sumber:'IoT Device' },
      { no:2,  waktu:'15/4/2026, 17:55:00', jenis:'Mobil',        confidence:'63.0%', sumber:'IoT Device' },
      { no:3,  waktu:'15/4/2026, 17:55:44', jenis:'Truck/Pickup', confidence:'63.0%', sumber:'IoT Device' },
      { no:4,  waktu:'15/4/2026, 17:55:44', jenis:'Mobil',        confidence:'63.0%', sumber:'IoT Device' },
      { no:5,  waktu:'15/4/2026, 17:55:44', jenis:'Mobil',        confidence:'63.0%', sumber:'IoT Device' },
      { no:6,  waktu:'15/4/2026, 17:55:44', jenis:'Truck/Pickup', confidence:'63.0%', sumber:'IoT Device' },
      { no:7,  waktu:'15/4/2026, 17:55:44', jenis:'Truck/Pickup', confidence:'63.0%', sumber:'IoT Device' },
      { no:8,  waktu:'15/4/2026, 17:55:44', jenis:'Mobil',        confidence:'63.0%', sumber:'IoT Device' },
      { no:9,  waktu:'15/4/2026, 17:55:44', jenis:'Mobil',        confidence:'63.0%', sumber:'IoT Device' },
      { no:10, waktu:'15/4/2026, 17:55:44', jenis:'Mobil',        confidence:'63.0%', sumber:'IoT Device' },
    ];

    function renderTable() {
      const tbody = document.getElementById('tableBody');
      tbody.innerHTML = rows.map(r => {
        const isTruck  = r.jenis === 'Truck/Pickup';
        const badgeCls = isTruck
          ? 'bg-orange/20 text-orange border border-orange/40'
          : 'bg-green/20 text-green border border-green/40';
        return `
          <tr class="border-b border-divider transition-colors cursor-default">
            <td class="text-center px-4 py-2.5 text-muted">${r.no}</td>
            <td class="text-center px-4 py-2.5 text-white/80 font-mono text-[10px]">${r.waktu}</td>
            <td class="text-center px-4 py-2.5">
              <span class="inline-block text-[10px] font-semibold px-2.5 py-0.5 rounded ${badgeCls}">
                ${r.jenis}
              </span>
            </td>
            <td class="text-center px-4 py-2.5 text-white/70">${r.confidence}</td>
            <td class="text-center px-4 py-2.5 text-muted">${r.sumber}</td>
          </tr>`;
      }).join('');
    }

    renderTable();
  </script>

</body>
</html>
