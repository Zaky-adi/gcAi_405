<?php

namespace App\GraphQL\Mutations;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Exception;

class AuthMutation
{
    public function login($_, array $args)
    {
        $auth = Firebase::auth();

        try {
            // Meneruskan request login ke server Firebase
            $signInResult = $auth->signInWithEmailAndPassword($args['email'], $args['password']);
            
            return [
                'token'   => $signInResult->idToken(), // Token JWT untuk otorisasi endpoint lain
                'uid'     => $signInResult->firebaseUserId(), // ID unik user di Firebase
                'message' => 'Login berhasil!'
            ];
            
        } catch (Exception $e) {
            // Jika email/password salah, lemparkan error ke GraphQL
            throw new Exception("Error Asli Firebase: " . $e->getMessage());
        }
    }

    public function logout($_, array $args)
    {
        $auth = Firebase::auth();

        try {
            // Keamanan Ekstra Backend: 
            // Mencabut semua refresh token milik UID ini sehingga token lama tidak bisa diperbarui lagi.
            $auth->revokeRefreshTokens($args['uid']);
            
            return "Logout berhasil dilakukan di sisi server. Pastikan Anda juga menghapus token di sisi Client/Frontend.";
            
        } catch (Exception $e) {
            throw new Exception("Gagal melakukan logout: " . $e->getMessage());
        }
    }
}