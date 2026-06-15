<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () { return redirect()->route('login'); });
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', function (Request $request) { return redirect('/dashboard'); });

Route::get('/dashboard', function () { return view('dashboard'); });
Route::get('/liveview', function () { return view('liveview'); });
Route::get('/reports', function () { return view('reports'); });
Route::get('/jadwal', function () { return view('jadwal'); });
Route::get('/perangkat', function () { return view('perangkat'); });