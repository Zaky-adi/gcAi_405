<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// 1. Route untuk menampilkan halaman form login (Diberi nama 'login')
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// 2. Route halaman utama (/) yang langsung me-redirect ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// 3. Route POST sementara untuk menangkap klik tombol "Masuk"
Route::post('/login', function (Request $request) {
    // Di sini nanti tempat Anda menaruh logika autentikasi (misal: cek ke Supabase)

    // Untuk sementara, kita kembalikan ke halaman login beserta pesan error bohongan
    // agar Anda bisa melihat tampilan error statis (warna merah) berfungsi
    return back()->withErrors([
        'username' => 'Sistem backend belum terhubung.',
    ])->withInput($request->only('username'));
});
