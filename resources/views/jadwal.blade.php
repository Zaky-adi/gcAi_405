<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gate Control AI — Jadwal Operasional</title>
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
    input[type="time"]::-webkit-calendar-picker-indicator {
      filter: invert(0.45);
      cursor: pointer;
    }
    input[type="time"] {
      color-scheme: dark;
    }
    .day-btn {
      transition: background .15s, border-color .15s, color .15s;
    }
    .day-btn.active {
      border-color: #f97316;
      color: #f97316;
      background: rgba(249,115,22,0.12);
    }
    .day-btn:not(.active) {
      border-color: #333;
      color: #777;
      background: #2e2e2e;
    }
    .day-btn:not(.active):hover {
      border-color: #555;
      color: #aaa;
    }
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
      <p class="text-white text-base font-semibold leading-none">Jadwal Operasional</p>
      <p class="text-muted text-xs mt-0.5">Pengaturan waktu aktif sistem deteksi</p>
    </div>

    <!-- Right: standby + clock -->
    <div class="flex items-center gap-3">
      <button class="flex items-center gap-1.5 bg-[#2a1a1a] border border-red-800 text-red-400 rounded-md px-3 py-1 text-xs font-medium">
        <span class="pulse-dot"></span>
        Standby
      </button>
      <div class="text-right leading-tight">
        <p id="clock" class="text-white text-lg font-bold tracking-widest leading-none">22:15:54</p>
        <p id="dateline" class="text-muted text-[10px] mt-0.5">Rabu, 6 April 2026</p>
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
        <!-- Laporan Kendaraan -->
        <a href="{{ url('/reports') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
          </svg>
          Laporan Kendaraan
        </a>
        <!-- Jadwal Operasional — ACTIVE -->
        <a href="{{ url('/jadwal') }}" class="nav-link active flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-white">
          <svg class="w-4 h-4 text-orange flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
          Jadwal Operasional
        </a>
      </nav>

      <p class="text-muted text-[9px] font-semibold uppercase tracking-widest px-4 mt-5 mb-2">Pengaturan</p>

      <nav class="flex flex-col gap-0.5 px-2">
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
    <main class="flex-1 overflow-y-auto p-5 flex flex-col gap-4 bg-surface">

      <!-- ── PENGATURAN JADWAL CARD ── -->
      <div class="bg-card border border-divider rounded-xl p-5 flex flex-col gap-4">

        <!-- Card heading -->
        <div class="flex items-start gap-3">
          <div class="w-9 h-9 rounded-full bg-orange/20 border border-orange/30 flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-4.5 h-4.5 text-orange" style="width:18px;height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
          </div>
          <div>
            <p class="text-white text-sm font-semibold leading-none">Pengaturan Jadwal Operasional</p>
            <p class="text-muted text-[11px] mt-1 leading-snug">Sistem hanya aktif mendeteksi pada rentang waktu yang ditentukan</p>
          </div>
        </div>

        <!-- Status banner: Di Luar Jam Operasional -->
        <div class="flex items-center gap-2.5 bg-orange/10 border border-orange/30 rounded-lg px-4 py-2.5">
          <div class="w-2 h-2 rounded-full bg-orange flex-shrink-0" style="animation: pulse-anim 1.4s infinite;"></div>
          <div>
            <p class="text-orange text-xs font-semibold leading-none">Di Luar Jam Operasional (Standby)</p>
            <p class="text-orange/70 text-[10px] mt-0.5">Aktif: 06:00 – 18:00 WIB</p>
          </div>
        </div>

        <!-- Time pickers row -->
        <div class="flex flex-wrap gap-6">

          <!-- Waktu Mulai -->
          <div class="flex flex-col gap-1.5">
            <label class="text-muted text-[10px] font-semibold uppercase tracking-wider">Waktu Mulai</label>
            <div class="relative">
              <input id="waktuMulai" type="time" value="06:00"
                class="bg-inputbg border border-divider text-white text-xs font-mono rounded-lg px-3 py-2.5 pr-9 w-36 focus:outline-none focus:border-orange transition-colors" />
            </div>
          </div>

          <!-- Waktu Selesai -->
          <div class="flex flex-col gap-1.5">
            <label class="text-muted text-[10px] font-semibold uppercase tracking-wider">Waktu Selesai</label>
            <div class="relative">
              <input id="waktuSelesai" type="time" value="18:00"
                class="bg-inputbg border border-divider text-white text-xs font-mono rounded-lg px-3 py-2.5 pr-9 w-36 focus:outline-none focus:border-orange transition-colors" />
            </div>
          </div>

        </div>

        <!-- Day toggles -->
        <div class="flex flex-col gap-2">
          <label class="text-muted text-[10px] font-semibold uppercase tracking-wider">Hari Aktif</label>
          <div class="flex flex-wrap gap-2" id="dayButtons">
            <button class="day-btn active text-xs font-semibold px-4 py-1.5 rounded-lg border" data-day="senin">Senin</button>
            <button class="day-btn active text-xs font-semibold px-4 py-1.5 rounded-lg border" data-day="selasa">Selasa</button>
            <button class="day-btn active text-xs font-semibold px-4 py-1.5 rounded-lg border" data-day="rabu">Rabu</button>
            <button class="day-btn active text-xs font-semibold px-4 py-1.5 rounded-lg border" data-day="kamis">Kamis</button>
            <button class="day-btn text-xs font-semibold px-4 py-1.5 rounded-lg border"        data-day="jumat">Jumat</button>
            <button class="day-btn text-xs font-semibold px-4 py-1.5 rounded-lg border"        data-day="sabtu">Sabtu</button>
            <button class="day-btn text-xs font-semibold px-4 py-1.5 rounded-lg border"        data-day="minggu">Minggu</button>
          </div>
        </div>

        <!-- Action buttons -->
        <div class="flex gap-3 pt-1">
          <button onclick="simpanJadwal()"
            class="bg-white text-gray-900 text-xs font-semibold px-6 py-2.5 rounded-lg hover:bg-gray-100 active:bg-gray-200 transition-colors flex items-center gap-2">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            Simpan Jadwal
          </button>
          <button onclick="resetDefault()"
            class="bg-inputbg border border-divider text-muted text-xs font-semibold px-6 py-2.5 rounded-lg hover:border-orange/50 hover:text-orange transition-colors flex items-center gap-2">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="1 4 1 10 7 10"/>
              <path d="M3.51 15a9 9 0 1 0 .49-3.57"/>
            </svg>
            Reset Default
          </button>
        </div>

      </div>
      <!-- end pengaturan card -->

      <!-- ── LOG JADWAL CARD ── -->
      <div class="bg-card border border-divider rounded-xl p-4 flex flex-col gap-3">

        <p class="text-white text-sm font-semibold">Log Jadwal</p>

        <div class="flex flex-col gap-0.5" id="logList">

          <!-- Log entry 1 -->
          <div class="flex items-start justify-between gap-3 py-2.5 border-b border-divider">
            <div class="flex items-start gap-3">
              <div class="w-7 h-7 rounded-full bg-green/15 border border-green/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5 text-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <polyline points="20 6 9 17 4 12"/>
                </svg>
              </div>
              <div>
                <p class="text-white text-xs font-medium leading-none">Jadwal diperbarui</p>
                <p class="text-muted text-[10px] mt-1">06:00 – 18:00 (Sen–Jum)</p>
              </div>
            </div>
            <p class="text-muted text-[10px] flex-shrink-0 mt-0.5">Hari ini, 08:30</p>
          </div>

          <!-- Log entry 2 -->
          <div class="flex items-start justify-between gap-3 py-2.5 border-b border-divider">
            <div class="flex items-start gap-3">
              <div class="w-7 h-7 rounded-full bg-teal/15 border border-teal/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5 text-teal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <circle cx="12" cy="12" r="10"/>
                  <line x1="12" y1="8" x2="12" y2="12"/>
                  <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
              </div>
              <div>
                <p class="text-white text-xs font-medium leading-none">Sistem diaktifkan</p>
                <p class="text-muted text-[10px] mt-1">Sesuai jadwal</p>
              </div>
            </div>
            <p class="text-muted text-[10px] flex-shrink-0 mt-0.5">Hari ini, 06:00</p>
          </div>

          <!-- Log entry 3 -->
          <div class="flex items-start justify-between gap-3 py-2.5">
            <div class="flex items-start gap-3">
              <div class="w-7 h-7 rounded-full bg-muted/15 border border-muted/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <circle cx="12" cy="12" r="10"/>
                  <line x1="10" y1="15" x2="10" y2="9"/>
                  <line x1="14" y1="15" x2="14" y2="9"/>
                </svg>
              </div>
              <div>
                <p class="text-white text-xs font-medium leading-none">Sistem dinonaktifkan</p>
                <p class="text-muted text-[10px] mt-1">di luar jadwal</p>
              </div>
            </div>
            <p class="text-muted text-[10px] flex-shrink-0 mt-0.5">Kemarin, 18:00</p>
          </div>

        </div>
      </div>
      <!-- end log card -->

    </main>
  </div>

  <!-- ═══════════════════════ SCRIPTS ═══════════════════════ -->
  <script>
    // ── Live clock ──
    function updateClock() {
      const now    = new Date();
      const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
      const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      const hh = String(now.getHours()).padStart(2,'0');
      const mm = String(now.getMinutes()).padStart(2,'0');
      const ss = String(now.getSeconds()).padStart(2,'0');
      document.getElementById('clock').textContent    = `${hh}:${mm}:${ss}`;
      document.getElementById('dateline').textContent =
        `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ── Day toggle ──
    document.querySelectorAll('.day-btn').forEach(btn => {
      btn.addEventListener('click', () => btn.classList.toggle('active'));
    });

    // ── Banner sync: update active hours from inputs ──
    function syncBanner() {
      const mulai   = document.getElementById('waktuMulai').value   || '06:00';
      const selesai = document.getElementById('waktuSelesai').value || '18:00';
      document.querySelector('.banner-time').textContent = `Aktif: ${mulai} – ${selesai} WIB`;
    }

    // ── Simpan Jadwal ──
    function simpanJadwal() {
      const mulai   = document.getElementById('waktuMulai').value   || '06:00';
      const selesai = document.getElementById('waktuSelesai').value || '18:00';
      const activeDays = [...document.querySelectorAll('.day-btn.active')]
        .map(b => b.dataset.day.charAt(0).toUpperCase() + b.dataset.day.slice(0,3))
        .join('–');
      const now  = new Date();
      const hh   = String(now.getHours()).padStart(2,'0');
      const mm   = String(now.getMinutes()).padStart(2,'0');

      // Prepend new log entry
      const logList = document.getElementById('logList');
      const entry   = document.createElement('div');
      entry.className = 'flex items-start justify-between gap-3 py-2.5 border-b border-divider';
      entry.innerHTML = `
        <div class="flex items-start gap-3">
          <div class="w-7 h-7 rounded-full bg-green/15 border border-green/30 flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-3.5 h-3.5 text-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div>
            <p class="text-white text-xs font-medium leading-none">Jadwal diperbarui</p>
            <p class="text-muted text-[10px] mt-1">${mulai} – ${selesai} (${activeDays})</p>
          </div>
        </div>
        <p class="text-muted text-[10px] flex-shrink-0 mt-0.5">Hari ini, ${hh}:${mm}</p>`;
      logList.prepend(entry);

      // Flash banner green briefly
      const banner = document.querySelector('.status-banner');
      banner.classList.add('!bg-green/10', '!border-green/30');
      setTimeout(() => banner.classList.remove('!bg-green/10', '!border-green/30'), 1500);
    }

    // ── Reset Default ──
    function resetDefault() {
      document.getElementById('waktuMulai').value   = '06:00';
      document.getElementById('waktuSelesai').value = '18:00';
      const defaultActive = ['senin','selasa','rabu','kamis'];
      document.querySelectorAll('.day-btn').forEach(btn => {
        if (defaultActive.includes(btn.dataset.day)) btn.classList.add('active');
        else btn.classList.remove('active');
      });
    }
  </script>

</body>
</html>
