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
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
          },
        },
      },
    }
  </script>
  <style>
    html, body { height: 100%; overflow: hidden; }
    /* Scrollbar minimalis untuk tabel */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #1a1a1a; }
    ::-webkit-scrollbar-thumb { background: #444; border-radius: 6px; }
    ::-webkit-scrollbar-thumb:hover { background: #555; }
    
    .nav-link { transition: background .15s, color .15s; }
    .nav-link:hover { background: rgba(255,255,255,.06); }
    .nav-link.active { background: #333; color: #fff; }
    input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(0.5); cursor: pointer; }
    tbody tr:hover { background: rgba(255,255,255,.04); }
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
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
        </div>
        <div class="leading-tight hidden sm:block">
          <p class="text-white text-sm font-bold leading-none">Gate Control <span class="text-orange">AI</span></p>
          <p class="text-muted text-[9px] mt-0.5 leading-tight max-w-[160px]">Politeknik Negeri Batam</p>
        </div>
      </div>
    </div>

    <div class="hidden lg:block text-center absolute left-1/2 transform -translate-x-1/2">
      <p class="text-white text-base font-semibold leading-none">Laporan Kendaraan</p>
      <p class="text-muted text-xs mt-0.5">Rekap data kendaraan masuk</p>
    </div>

    <div class="flex items-center gap-3">
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
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          Dashboard
        </a>
        <a href="{{ url('/liveview') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
          Live view
        </a>
        <a href="{{ url('/reports') }}" class="nav-link active flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-white">
          <svg class="w-4 h-4 text-orange flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          Laporan Kendaraan
        </a>
      </nav>

      <p class="text-muted text-[9px] font-semibold uppercase tracking-widest px-4 mt-5 mb-2">Pengaturan</p>
      <nav class="flex flex-col gap-0.5 px-2">
        <a href="{{ url('/jadwal') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Jadwal Operasional
        </a>
        <a href="{{ url('/perangkat') }}" class="nav-link flex items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-muted">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>
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

      <div class="flex flex-col sm:flex-row flex-wrap items-start sm:items-end gap-3 flex-shrink-0">
        <div class="flex flex-col gap-1 w-full sm:w-auto">
          <label class="text-muted text-[10px] font-semibold uppercase tracking-wider">Dari Tanggal</label>
          <input type="date" id="startDate" class="bg-inputbg w-full sm:w-36 border border-divider text-white text-xs rounded-md px-3 py-2 focus:outline-none focus:border-orange transition-colors" style="color-scheme: dark;" />
        </div>
        <div class="flex flex-col gap-1 w-full sm:w-auto">
          <label class="text-muted text-[10px] font-semibold uppercase tracking-wider">Sampai Tanggal</label>
          <input type="date" id="endDate" class="bg-inputbg w-full sm:w-36 border border-divider text-white text-xs rounded-md px-3 py-2 focus:outline-none focus:border-orange transition-colors" style="color-scheme: dark;" />
        </div>
        <div class="flex flex-col gap-1 w-full sm:w-auto">
          <label class="text-muted text-[10px] font-semibold uppercase tracking-wider">Jenis Kendaraan</label>
          <select id="vehicleType" class="bg-inputbg w-full sm:w-32 border border-divider text-white text-xs rounded-md px-3 py-2 focus:outline-none focus:border-orange transition-colors cursor-pointer" style="color-scheme: dark;">
            <option value="semua" selected>Semua</option>
            <option value="mobil">Mobil</option>
            <option value="truck">Truck/Pickup</option>
          </select>
        </div>
        
        <div class="flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">
            <button id="btnFilter" class="flex-1 sm:flex-none flex justify-center items-center gap-2 bg-inputbg border border-divider text-white text-xs font-medium rounded-md px-4 py-2 hover:border-orange hover:text-orange transition-colors">
            Filter
            </button>
            <button onclick="exportToCSV()" class="flex-1 sm:flex-none flex justify-center items-center gap-2 bg-inputbg border border-divider text-white text-xs font-medium rounded-md px-4 py-2 hover:border-green hover:text-green transition-colors">
            Export CSV
            </button>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-shrink-0">
        <div class="bg-card border border-divider rounded-xl p-4 flex flex-col gap-1">
          <p class="text-muted text-[10px] font-semibold uppercase tracking-wider">Total Filter</p>
          <p id="summaryTotal" class="text-white text-3xl font-bold leading-none mt-1">...</p>
        </div>
        <div class="bg-card border border-divider rounded-xl p-4 flex flex-col gap-1">
          <p class="text-muted text-[10px] font-semibold uppercase tracking-wider">Rata-rata/Hari</p>
          <p id="summaryAvg" class="text-white text-3xl font-bold leading-none mt-1">...</p>
        </div>
        <div class="bg-card border border-divider rounded-xl p-4 flex flex-col gap-1">
          <p class="text-muted text-[10px] font-semibold uppercase tracking-wider">Puncak Tertinggi</p>
          <p id="summaryPeak" class="text-white text-3xl font-bold leading-none mt-1">--:--</p>
        </div>
      </div>

      <div class="bg-card border border-divider rounded-xl flex-1 flex flex-col overflow-hidden min-h-[400px] shadow-sm relative">
        <div class="flex-1 overflow-auto w-full h-full relative table-scroll">
          <table class="w-full text-[11px] min-w-[600px]">
            <thead class="sticky top-0 bg-card z-10">
              <tr class="text-muted uppercase tracking-wider">
                <th class="text-center px-4 py-3 font-semibold w-14 border-b border-divider">No</th>
                <th class="text-center px-4 py-3 font-semibold w-1/4 border-b border-divider">Waktu Masuk</th>
                <th class="text-center px-4 py-3 font-semibold w-1/4 border-b border-divider">Jenis Kendaraan</th>
                <th class="text-center px-4 py-3 font-semibold w-1/4 border-b border-divider">Confidence</th>
                <th class="text-center px-4 py-3 font-semibold w-1/4 border-b border-divider">Sumber</th>
              </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-divider/50">
              <tr><td colspan="5" class="text-center py-6 text-muted">Memuat data laporan...</td></tr>
            </tbody>
          </table>
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

    // ── Inisialisasi Tanggal Hari Ini ──
    function setDateInputs() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('startDate').value = today;
        document.getElementById('endDate').value = today;
    }
    setDateInputs();

    let currentTableData = [];

    // ── Fetch GraphQL Data ──
    async function fetchLaporan() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const vehicleType = document.getElementById('vehicleType').value;

        if (!startDate || !endDate) {
            alert('Silakan pilih rentang tanggal.');
            return;
        }

        const btnFilter = document.getElementById('btnFilter');
        const oldText = btnFilter.innerHTML;
        btnFilter.innerHTML = `Memproses...`;
        btnFilter.disabled = true;

        const query = `
            query GetLaporan($startDate: String!, $endDate: String!, $vehicleType: String) {
                laporanKendaraan(startDate: $startDate, endDate: $endDate, vehicleType: $vehicleType) {
                    ringkasan { total rataRataPerHari puncakTertinggi { jam jumlah } }
                    data { id vehicle_type confidence_score detected_at device_id }
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
                body: JSON.stringify({
                    query: query,
                    variables: { startDate, endDate, vehicleType }
                })
            });

            const result = await response.json();
            if (result.errors) throw new Error(result.errors[0].message);

            const report = result.data.laporanKendaraan;
            
            // 1. Update Stat Cards
            document.getElementById('summaryTotal').innerText = report.ringkasan.total;
            document.getElementById('summaryAvg').innerText = report.ringkasan.rataRataPerHari;
            
            const peak = report.ringkasan.puncakTertinggi;
            if(peak && peak.jam !== '-') {
                document.getElementById('summaryPeak').innerHTML = `${peak.jam} <span class="text-xl text-muted font-semibold">(${peak.jumlah})</span>`;
            } else {
                document.getElementById('summaryPeak').innerHTML = `--:-- <span class="text-xl text-muted font-semibold">(0)</span>`;
            }

            // 2. Update Table
            currentTableData = report.data; 
            const tbody = document.getElementById('tableBody');
            
            if (currentTableData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-6 text-muted italic">Tidak ada data kendaraan ditemukan pada rentang tanggal ini.</td></tr>`;
            } else {
                tbody.innerHTML = currentTableData.map((log, index) => {
                    const dateObj = new Date(log.detected_at);
                    const tgl = `${dateObj.getDate()}/${dateObj.getMonth()+1}/${dateObj.getFullYear()}`;
                    const jam = `${String(dateObj.getHours()).padStart(2,'0')}:${String(dateObj.getMinutes()).padStart(2,'0')}:${String(dateObj.getSeconds()).padStart(2,'0')}`;
                    const formattedDate = `${tgl}, ${jam}`;

                    const vType = log.vehicle_type ? log.vehicle_type.toLowerCase() : '';
                    const isTruck = vType === 'truk' || vType === 'truck' || vType === 'pickup';
                    const displayJenis = isTruck ? 'Truck/Pickup' : 'Mobil';
                    
                    const badgeCls = isTruck
                        ? 'bg-orange/20 text-orange border border-orange/40'
                        : 'bg-green/20 text-green border border-green/40';

                    let conf = log.confidence_score;
                    if(conf <= 1 && conf > 0) conf = conf * 100;
                    const confidenceText = conf.toFixed(1) + '%';

                    return `
                    <tr class="transition-colors cursor-default">
                        <td class="text-center px-4 py-2.5 text-muted">${index + 1}</td>
                        <td class="text-center px-4 py-2.5 text-white/80 font-mono text-[10px]">${formattedDate}</td>
                        <td class="text-center px-4 py-2.5">
                            <span class="inline-block text-[10px] font-semibold px-2.5 py-0.5 rounded capitalize ${badgeCls}">${displayJenis}</span>
                        </td>
                        <td class="text-center px-4 py-2.5 text-white/70">${confidenceText}</td>
                        <td class="text-center px-4 py-2.5 text-muted">${log.device_id || 'IoT Device'}</td>
                    </tr>`;
                }).join('');
            }

        } catch (error) {
            console.error("Gagal mengambil laporan:", error);
            document.getElementById('tableBody').innerHTML = `<tr><td colspan="5" class="text-center py-6 text-red-400">Gagal memuat data. Periksa koneksi ke server.</td></tr>`;
        } finally {
            btnFilter.innerHTML = `Filter`;
            btnFilter.disabled = false;
        }
    }

    // ── Export CSV Logic ──
    function exportToCSV() {
        if (currentTableData.length === 0) {
            alert('Tidak ada data untuk diexport.');
            return;
        }

        let csvContent = "No,Waktu Masuk,Jenis Kendaraan,Confidence,Sumber\n";
        
        currentTableData.forEach((log, index) => {
            const dateObj = new Date(log.detected_at);
            const dateStr = `${dateObj.getDate()}/${dateObj.getMonth()+1}/${dateObj.getFullYear()} ${dateObj.getHours()}:${dateObj.getMinutes()}:${dateObj.getSeconds()}`;
            
            let conf = log.confidence_score;
            if(conf <= 1 && conf > 0) conf = conf * 100;
            
            csvContent += `${index + 1},"${dateStr}","${log.vehicle_type}","${conf.toFixed(1)}%","${log.device_id}"\n`;
        });

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", `Laporan_Kendaraan_${document.getElementById('startDate').value}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    document.getElementById('btnFilter').addEventListener('click', fetchLaporan);
    document.addEventListener('DOMContentLoaded', fetchLaporan); 
  </script>

</body>
</html>