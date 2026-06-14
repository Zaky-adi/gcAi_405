<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gate Control AI</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-background text-foreground font-sans flex items-center justify-center p-4" style="background-color: oklch(0.16 0 0);">

    <div x-data="loginForm()" 
         class="w-full max-w-[340px] rounded-2xl overflow-hidden shadow-2xl"
         style="background: oklch(0.18 0 0)">
         
      <div class="flex flex-col items-center pt-8 pb-5 px-6">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 shadow-lg"
             style="background: oklch(0.65 0.18 40)"
             aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white"><path d="m16 13 5.223 3.482a.5.5 0 0 0 .777-.416V7.87a.5.5 0 0 0-.752-.432L16 10.5"/><rect x="2" y="6" width="14" height="12" rx="2"/></svg>
        </div>

        <h1 class="text-foreground font-bold text-xl tracking-tight leading-tight text-center">
          Gate Control <span style="color: oklch(0.65 0.18 40)">AI</span>
        </h1>

        <p class="text-center text-xs leading-relaxed mt-1" style="color: oklch(0.55 0 0)">
          Sistem Monitoring &amp; Pengendalian Gerbang<br />
          Portal Gerbang Politeknik Negeri Bukittinggi
        </p>
      </div>

      <div style="border-top: 1px solid oklch(1 0 0 / 8%)"></div>

      <div class="px-6 py-5">
        <h2 class="text-center text-sm font-semibold mb-4" style="color: oklch(0.8 0 0)">
          Masuk ke Dashboard
        </h2>

        <form @submit.prevent="handleSubmit" novalidate>
            
          <div class="mb-3">
            <label for="username" class="block text-xs font-semibold uppercase tracking-wider mb-1" style="color: oklch(0.65 0 0)">
              Username
            </label>
            <div class="flex items-center rounded-md px-3 gap-2"
                 style="background: oklch(0.24 0 0); border: 1px solid oklch(1 0 0 / 10%)">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 shrink-0" style="color: oklch(0.55 0 0)"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              <input id="username" type="text" x-model="username" placeholder="admin" autocomplete="username"
                     class="w-full bg-transparent py-2.5 text-sm outline-none placeholder:text-[oklch(0.4_0_0)] text-foreground" />
            </div>
          </div>

          <div class="mb-4">
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider mb-1" style="color: oklch(0.65 0 0)">
              Password
            </label>
            <div class="flex items-center rounded-md px-3 gap-2"
                 style="background: oklch(0.24 0 0); border: 1px solid oklch(1 0 0 / 10%)">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 shrink-0" style="color: oklch(0.55 0 0)"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              
              <input id="password" :type="showPassword ? 'text' : 'password'" x-model="password" placeholder="Masukkan password" autocomplete="current-password"
                     class="w-full bg-transparent py-2.5 text-sm outline-none placeholder:text-[oklch(0.4_0_0)] text-foreground" />
              
              <button type="button" @click="showPassword = !showPassword" class="shrink-0 focus:outline-none"
                      :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" style="color: oklch(0.55 0 0)"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.579 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" style="color: oklch(0.55 0 0)"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>

          <template x-if="error">
            <p x-text="error" class="text-xs mb-3 text-center" style="color: oklch(0.7 0.18 25)"></p>
          </template>

          <button type="submit" :disabled="loading"
                  class="w-full rounded-md py-2.5 text-sm font-semibold transition-opacity hover:opacity-90 active:opacity-80 disabled:opacity-60 cursor-pointer"
                  style="background: oklch(0.98 0 0); color: oklch(0.12 0 0)">
            <span x-show="!loading">Masuk</span>
            <span x-show="loading" x-cloak>Memproses...</span>
          </button>

        </form>
      </div>

      <div class="text-center py-3 px-6" style="border-top: 1px solid oklch(1 0 0 / 8%)">
        <p class="text-xs" style="color: oklch(0.4 0 0)">
          Hak Cipta &copy; Politeknik Negeri Bukittinggi
        </p>
      </div>
    </div>

    <script>
      function loginForm() {
        return {
          showPassword: false,
          username: '',
          password: '',
          loading: false,
          error: '',
          
          async handleSubmit() {
            this.error = '';

            if (!this.username || !this.password) {
              this.error = 'Username dan password wajib diisi.';
              return;
            }

            this.loading = true;
            
            await new Promise(resolve => setTimeout(resolve, 1200));
            
            this.loading = false;
            this.error = 'Username atau password salah.';
          }
        }
      }
    </script>
</body>
</html>