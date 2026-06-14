<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gate Control AI — Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            surface: '#1e1e1e',
            card:    '#2a2a2a',
            input:   '#333333',
            border:  '#3d3d3d',
            muted:   '#888888',
            orange:  '#f97316',
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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    /* Remove default browser focus ring; use custom */
    input:focus { outline: none; }

    /* Eye toggle button — remove default browser appearance */
    .eye-btn { background: none; border: none; cursor: pointer; padding: 0; }

    /* Subtle inner shadow on card */
    .login-card {
      box-shadow:
        0 0 0 1px rgba(255,255,255,0.06),
        0 8px 40px rgba(0,0,0,0.6);
    }
  </style>
</head>
<body class="min-h-screen bg-surface font-sans flex flex-col">

  <!-- Minimal top bar (browser tab label area) -->
  <div class="bg-[#2c2c2c] border-b border-border px-4 py-2 flex items-center gap-2">
    <span class="text-sm text-muted">login</span>
  </div>

  <!-- Main centered content -->
  <main class="flex-1 flex items-center justify-center px-4 py-12">
    <div class="login-card bg-card rounded-2xl w-full max-w-sm p-8">

      <!-- Brand header -->
      <div class="flex flex-col items-center gap-3 mb-7">
        <!-- Orange camera icon -->
        <div class="w-14 h-14 bg-orange rounded-xl flex items-center justify-center shadow-lg">
          <!-- Camera SVG -->
          <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
            <circle cx="12" cy="13" r="3"/>
          </svg>
        </div>

        <!-- App name -->
        <div class="text-center">
          <h1 class="text-white text-xl font-bold leading-tight">
            Gate Control <span class="text-orange">AI</span>
          </h1>
          <p class="text-muted text-xs mt-1 leading-relaxed">
            Sistem Monitoring &amp; Pengendalian Gerbang<br />
            Portal Gerbang Politeknik Negeri Batam
          </p>
        </div>
      </div>

      <!-- Login form card inner -->
      <div class="bg-[#323232] rounded-xl px-5 py-5">
        <h2 class="text-white text-sm font-semibold text-center mb-5">
          Masuk ke Dashboard
        </h2>

        <form action="#" method="POST" novalidate>

          <!-- Username -->
          <div class="mb-4">
            <label for="username" class="block text-[11px] font-semibold tracking-widest text-muted uppercase mb-2">
              Username
            </label>
            <div class="flex items-center gap-2 bg-input border border-border rounded-lg px-3 py-2.5 focus-within:border-orange focus-within:ring-1 focus-within:ring-orange/40 transition-colors">
              <!-- User icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-muted flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="8" r="4"/>
                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
              </svg>
              <input
                id="username"
                name="username"
                type="text"
                placeholder="admin"
                autocomplete="username"
                class="flex-1 bg-transparent text-white text-sm placeholder-muted caret-orange w-full"
              />
            </div>
          </div>

          <!-- Password -->
          <div class="mb-5">
            <label for="password" class="block text-[11px] font-semibold tracking-widest text-muted uppercase mb-2">
              Password
            </label>
            <div class="flex items-center gap-2 bg-input border border-border rounded-lg px-3 py-2.5 focus-within:border-orange focus-within:ring-1 focus-within:ring-orange/40 transition-colors">
              <!-- Lock icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-muted flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
              <input
                id="password"
                name="password"
                type="password"
                placeholder="Masukkan password"
                autocomplete="current-password"
                class="flex-1 bg-transparent text-white text-sm placeholder-muted caret-orange w-full"
              />
              <!-- Eye toggle -->
              <button
                type="button"
                class="eye-btn text-muted hover:text-white transition-colors flex-shrink-0"
                aria-label="Tampilkan/sembunyikan password"
                onclick="togglePassword()"
              >
                <!-- Eye open -->
                <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
                <!-- Eye closed (hidden initially) -->
                <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                  <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                  <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Submit button -->
          <button
            type="submit"
            class="w-full bg-white hover:bg-gray-100 active:bg-gray-200 text-gray-900 text-sm font-semibold rounded-lg py-2.5 transition-colors duration-150 shadow"
          >
            Masuk
          </button>

        </form>
      </div>

      <!-- Footer credit -->
      <p class="text-center text-[11px] text-muted mt-4">
        HKI, TIKo IoT, Politeknik Negeri Batam
      </p>

    </div>
  </main>

  <script>
    function togglePassword() {
      const input    = document.getElementById('password');
      const eyeOpen  = document.getElementById('eye-open');
      const eyeClose = document.getElementById('eye-closed');
      const isHidden = input.type === 'password';

      input.type     = isHidden ? 'text' : 'password';
      eyeOpen.classList.toggle('hidden',  isHidden);
      eyeClose.classList.toggle('hidden', !isHidden);
    }
  </script>

</body>
</html>

Gate Control AI
