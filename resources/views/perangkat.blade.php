<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gate Control AI — Perangkat IoT</title>
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
            blue:     '#3b82f6',
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
    @keyframes blink {
      0%,100% { opacity: 1; }
      50%      { opacity: 0; }
    }
    .blink { animation: blink 1s step-end infinite; }
    .mqtt-log { font-family: 'Courier New', Courier, monospace; }
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
      <p class="text-white text-base font-semibold leading-none">Perangkat IoT</p>
      <p class="text-muted text-xs mt-0.5">Status perangkat keras dan sensor</p>
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
        <a href="{{ url('/liveview') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
          Live view
        </a>
        <a href="{{ url('/reports') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          Laporan Kendaraan
        </a>
        <a href="{{ url('/jadwal') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 16 14"/></svg>
          Jadwal Operasional
        </a>
      </nav>

      <p class="text-muted text-[9px] font-semibold uppercase tracking-widest px-4 mt-5 mb-2">Pengaturan</p>

      <nav class="flex flex-col gap-0.5 px-2">
        <a href="{{ url('/perangkat') }}" class="nav-link active flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-white">
          <svg class="w-4 h-4 text-orange flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>
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

    <main class="flex-1 overflow-y-auto p-4 sm:p-5 flex flex-col gap-4 bg-surface md:ml-0">

      <div id="deviceContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
         <p class="text-muted text-sm italic col-span-3 text-center py-10">Memuat daftar perangkat dari database...</p>
      </div>

      <div class="bg-card border border-divider rounded-xl p-4 flex flex-col gap-3 mt-1">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
          <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-green" style="animation: pulse-anim 1.4s infinite;"></div>
            <p class="text-white text-sm font-semibold">Log Komunikasi MQTT</p>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-muted text-[10px]">broker: localhost:1883</span>
            <button onclick="clearLog()" class="text-muted text-[10px] hover:text-orange transition-colors border border-divider rounded px-2 py-0.5">Clear</button>
          </div>
        </div>

        <div id="mqttLog" class="mqtt-log bg-[#111] border border-divider rounded-lg p-3 h-32 overflow-y-auto flex flex-col gap-1 text-[11px] w-full break-all sm:break-normal">
          <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-2">
            <span class="text-muted flex-shrink-0">[00:00:01]</span>
            <span class="text-orange font-semibold flex-shrink-0">CONNECT</span>
            <span class="text-[#aaa]">— Raspberry Pi terhubung ke broker</span>
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
    if (!token) window.location.href = "{{ url('/login') }}";

    function logout() {
        localStorage.removeItem('firebase_token');
        localStorage.removeItem('firebase_uid');
        window.location.href = "{{ url('/login') }}";
    }

    // ── Live clock ──
    function updateClock() {
      const now    = new Date();
      const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
      const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      const hh = String(now.getHours()).padStart(2,'0');
      const mm = String(now.getMinutes()).padStart(2,'0');
      const ss = String(now.getSeconds()).padStart(2,'0');
      document.getElementById('clock').textContent    = `${hh}:${mm}:${ss}`;
      document.getElementById('dateline').textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ── FETCH DEVICES FROM GRAPHQL API ──
    async function fetchDevices() {
        const query = `
            query {
                devices {
                    id
                    device_name
                    location
                    is_active
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
            if (result.errors) throw new Error(result.errors[0].message);

            const devices = result.data.devices;
            const container = document.getElementById('deviceContainer');
            container.innerHTML = '';

            if (devices.length === 0) {
                container.innerHTML = `<p class="text-muted text-sm italic col-span-3 text-center py-10">Belum ada perangkat yang terdaftar di database.</p>`;
                return;
            }

            devices.forEach(dev => {
                const nameLower = dev.device_name.toLowerCase();
                const isOnline = dev.is_active;

                // Set Badge Status Aktif/Tidak
                const badgeHTML = isOnline
                    ? `<span class="flex items-center gap-1 bg-green/15 border border-green/30 text-green text-[9px] font-semibold px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-green inline-block" style="animation: pulse-anim 1.4s infinite;"></span>Online</span>`
                    : `<span class="flex items-center gap-1 bg-red-500/15 border border-red-500/30 text-red-400 text-[9px] font-semibold px-2 py-0.5 rounded-full">Offline</span>`;

                let cardHTML = '';

                // DETEKSI OTOMATIS TEMA KARTU BERDASARKAN NAMA PERANGKAT
                if (nameLower.includes('pi') || nameLower.includes('edge') || nameLower.includes('server')) {
                    // TEMA 1: EDGE DEVICE / CPU (Warna Orange)
                    cardHTML = `
                    <div class="bg-card border border-divider rounded-xl p-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-orange/20 border border-orange/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-orange" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>
                                </div>
                                <div>
                                    <p class="text-white text-xs font-semibold leading-none truncate w-32">${dev.device_name}</p>
                                    <p class="text-muted text-[9px] mt-0.5 truncate w-32">${dev.location}</p>
                                </div>
                            </div>
                            ${badgeHTML}
                        </div>
                        <div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[10px]">
                            <div><p class="text-muted leading-none">CPU Temp</p><p class="text-white font-medium mt-0.5" data-sensor="cpu-temp">52.3°C</p></div>
                            <div><p class="text-muted leading-none">CPU Usage</p><p class="text-white font-medium mt-0.5">67%</p></div>
                            <div><p class="text-muted leading-none">RAM</p><p class="text-white font-medium mt-0.5">2.1 / 4 GB</p></div>
                            <div class="col-span-2"><p class="text-muted leading-none">ID Sistem</p><p class="text-white font-mono text-[9px] mt-0.5">${dev.id}</p></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-[9px] text-muted mb-1">
                                <span>CPU Temp</span>
                                <span class="text-orange" data-sensor="cpu-temp">52.3°C</span>
                            </div>
                            <div class="w-full h-1 bg-inputbg rounded-full overflow-hidden">
                                <div class="h-full bg-orange rounded-full transition-all duration-500" data-bar="cpu" style="width: 52%"></div>
                            </div>
                        </div>
                    </div>`;
                } else if (nameLower.includes('cam') || nameLower.includes('cctv') || nameLower.includes('kamera')) {
                    // TEMA 2: KAMERA CCTV (Warna Biru)
                    cardHTML = `
                    <div class="bg-card border border-divider rounded-xl p-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue/20 border border-blue/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                </div>
                                <div>
                                    <p class="text-white text-xs font-semibold leading-none truncate w-32">${dev.device_name}</p>
                                    <p class="text-muted text-[9px] mt-0.5 truncate w-32">${dev.location}</p>
                                </div>
                            </div>
                            ${badgeHTML}
                        </div>
                        <div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[10px]">
                            <div><p class="text-muted leading-none">Resolusi</p><p class="text-white font-medium mt-0.5">1920×1080</p></div>
                            <div><p class="text-muted leading-none">FPS</p><p class="text-white font-medium mt-0.5">30 fps</p></div>
                            <div><p class="text-muted leading-none">ID Sistem</p><p class="text-white font-mono text-[9px] mt-0.5">${dev.id}</p></div>
                            <div class="col-span-2"><p class="text-muted leading-none">Status</p><p class="${isOnline ? 'text-green' : 'text-red-400'} font-medium mt-0.5">${isOnline ? 'Streaming aktif' : 'Terputus'}</p></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-[9px] text-muted mb-1">
                                <span>Frame Rate</span>
                                <span class="text-blue">${isOnline ? '30 / 30 fps' : '0 fps'}</span>
                            </div>
                            <div class="w-full h-1 bg-inputbg rounded-full overflow-hidden">
                                <div class="h-full bg-blue rounded-full" style="width: ${isOnline ? '100%' : '0%'}"></div>
                            </div>
                        </div>
                    </div>`;
                } else {
                    // TEMA 3: SENSOR / GENERAL (Warna Hijau)
                    cardHTML = `
                    <div class="bg-card border border-divider rounded-xl p-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-green/20 border border-green/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                                </div>
                                <div>
                                    <p class="text-white text-xs font-semibold leading-none truncate w-32">${dev.device_name}</p>
                                    <p class="text-muted text-[9px] mt-0.5 truncate w-32">${dev.location}</p>
                                </div>
                            </div>
                            ${badgeHTML}
                        </div>
                        <div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[10px]">
                            <div><p class="text-muted leading-none">Sensor LDR</p><p class="text-white font-medium mt-0.5" data-sensor="ldr">742 lux</p></div>
                            <div><p class="text-muted leading-none">Indikator</p><p class="text-white font-medium mt-0.5">${isOnline ? 'Aktif' : 'Mati'}</p></div>
                            <div><p class="text-muted leading-none">ID Sistem</p><p class="text-white font-mono text-[9px] mt-0.5">${dev.id}</p></div>
                            <div class="col-span-2"><p class="text-muted leading-none">Protokol</p><p class="text-white font-medium mt-0.5">MQTT</p></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-[9px] text-muted mb-1">
                                <span>Intensitas Cahaya</span>
                                <span class="text-green" data-sensor="ldr">${isOnline ? '742 lux' : '0 lux'}</span>
                            </div>
                            <div class="w-full h-1 bg-inputbg rounded-full overflow-hidden">
                                <div class="h-full bg-green rounded-full transition-all duration-500" data-bar="ldr" style="width: ${isOnline ? '74%' : '0%'}"></div>
                            </div>
                        </div>
                    </div>`;
                }

                container.insertAdjacentHTML('beforeend', cardHTML);
            });

        } catch (error) {
            console.error("Gagal memuat perangkat:", error);
            document.getElementById('deviceContainer').innerHTML = `<p class="text-red-400 text-sm italic col-span-3 text-center py-10">Gagal terhubung ke server untuk memuat perangkat.</p>`;
        }
    }

    // Panggil fetch data saat halaman dimuat
    document.addEventListener('DOMContentLoaded', fetchDevices);

    // ── Simulated MQTT log stream ──
    const mqttMessages = [
      { type: 'PUBLISH', color: 'text-green',  msg: '— gatecontrol/vehicle/data' },
      { type: 'PUBLISH', color: 'text-green',  msg: '— gatecontrol/sensor/ldr' },
      { type: 'PUBLISH', color: 'text-green',  msg: '— gatecontrol/status/ping' },
      { type: 'SUBSCRIBE', color: 'text-blue', msg: '— gatecontrol/command/gate' },
      { type: 'SUBSCRIBE', color: 'text-blue', msg: '— gatecontrol/command/buzzer' },
      { type: 'DISCONNECT', color: 'text-red-400', msg: '— Sensor LDR timeout (reconnecting…)' },
      { type: 'CONNECT', color: 'text-orange', msg: '— Sensor LDR terhubung kembali' },
    ];
    let msgIdx = 0;

    function appendMqttLog() {
      const log  = document.getElementById('mqttLog');
      const item = mqttMessages[msgIdx % mqttMessages.length];
      msgIdx++;

      const now  = new Date();
      const hh   = String(now.getHours()).padStart(2,'0');
      const mm   = String(now.getMinutes()).padStart(2,'0');
      const ss   = String(now.getSeconds()).padStart(2,'0');
      const time = `[${hh}:${mm}:${ss}]`;

      const row = document.createElement('div');
      row.className = 'flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-2';
      row.innerHTML = `
        <span class="text-muted flex-shrink-0">${time}</span>
        <span class="${item.color} font-semibold flex-shrink-0">${item.type}</span>
        <span class="text-[#aaa]">${item.msg}</span>`;
      log.appendChild(row);
      log.scrollTop = log.scrollHeight;
    }
    setInterval(appendMqttLog, 2500);

    // ── Clear log ──
    function clearLog() { document.getElementById('mqttLog').innerHTML = ''; }

    // ── Live sensor value fluctuation ──
    function fluctuateSensors() {
      const ldrBase = 742;
      const ldrVal  = ldrBase + Math.floor((Math.random() - 0.5) * 30);
      const cpuTemp = (52.3 + (Math.random() - 0.5) * 2).toFixed(1);

      const cpuTempEls = document.querySelectorAll('[data-sensor="cpu-temp"]');
      cpuTempEls.forEach(el => el.textContent = `${cpuTemp}°C`);
      const cpuBar = document.querySelector('[data-bar="cpu"]');
      if (cpuBar) cpuBar.style.width = `${cpuTemp}%`;

      const ldrEls = document.querySelectorAll('[data-sensor="ldr"]');
      ldrEls.forEach(el => el.textContent = `${ldrVal} lux`);
      const ldrBar = document.querySelector('[data-bar="ldr"]');
      if (ldrBar) ldrBar.style.width = `${Math.min(100, (ldrVal / 1000) * 100).toFixed(0)}%`;
    }
    setInterval(fluctuateSensors, 3000);
  </script>
</body>
</html>
