<?php

namespace App\GraphQL\Mutations;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Google\Cloud\Firestore\FieldValue;
use Exception;

class UserMutation
{
    public function createUser($_, array $args)
    {
        $auth = Firebase::auth();
        $firestore = Firebase::firestore()->database();

        try {
            // 1. Buat kredensial pengguna di Firebase Authentication
            $userProperties = [
                'email'       => $args['email'],
                'password'    => $args['password'],
                'displayName' => $args['name'],
            ];
            
            $createdUser = $auth->createUser($userProperties);
            $uid = $createdUser->uid; // Dapatkan ID unik dari Firebase Auth

            // 2. Simpan profil dan role pengguna di Firestore Database
            $userData = [
                'name'       => $args['name'],
                'email'      => $args['email'],
                'role'       => $args['role'], // 'admin' atau 'operator'
                'created_at' => FieldValue::serverTimestamp(),
            ];

            // Gunakan UID dari Auth sebagai ID Dokumen di Firestore
            $firestore->collection('users')->document($uid)->set($userData);

            // 3. Kembalikan respons ke GraphQL
            $userData['id'] = $uid;
            
            return $userData;

        } catch (Exception $e) {
            // Menangkap error (misal: email sudah terdaftar, password terlalu pendek)
            throw new Exception("Gagal menambahkan pengguna: " . $e->getMessage());
        }
    }
}