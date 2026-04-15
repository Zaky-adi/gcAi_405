<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Keamanan: Validasi input yang masuk
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required|string' // Penting untuk mengidentifikasi perangkat
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 3. Keamanan: Cek apakah user ada dan password cocok
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // 4. FITUR SINGLE DEVICE LOGIN: 
        // Hapus semua token/sesi yang sudah ada untuk user ini sebelumnya.
        // Ini akan membuat device pertama (yang sebelumnya login) ter-logout secara otomatis
        // karena tokennya sudah tidak valid / terhapus dari database.
        $user->tokens()->delete();

        // 5. Buat token baru & catat sesi di tabel personal_access_tokens
        // Kita bisa menggabungkan nama device dengan IP atau User Agent untuk pencatatan yang lebih detail
        $tokenInfo = $request->device_name . ' (' . $request->ip() . ')';
        $token = $user->createToken($tokenInfo)->plainTextToken;

        // Return response berhasil
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.'
        ], 200);
    }
}