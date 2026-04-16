<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi: 'device_name' dihapus karena sekarang terdeteksi otomatis
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
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
        $user->tokens()->delete();

        // 5. AUTO DETEKSI DEVICE & IP (Versi Rapi)
        $agent = new Agent();
        
        // Mengambil nama OS (Windows, OS X, Android, dll) dan Browser (Chrome, Safari, dll)
        $platform = $agent->platform() ?: 'Unknown OS';
        $browser = $agent->browser() ?: 'Unknown Browser';
        
        // Menggabungkan nama OS dan Browser
        $cleanDeviceName = $platform . ' - ' . $browser; 
        $ipAddress = $request->ip();

        // Nama token yang akan disimpan di database (Contoh: "Windows - Chrome (IP: 192.168.1.1)")
        $tokenInfo = $cleanDeviceName . ' (IP: ' . $ipAddress . ')';
        
        // Buat token baru
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
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.'
        ], 200);
    }
}