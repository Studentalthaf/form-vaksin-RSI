<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus user lama jika ada (hanya untuk development)
        User::truncate();
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Admin Rumah Sakit
        User::create([
            'nama' => 'Admin Rumah Sakit',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password123'),
            'role' => 'admin_rumah_sakit',
            'no_telp' => '081234567890',
        ]);

        // Dokter
        User::create([
            'nama' => 'Dr. Ahmad Fauzi',
            'email' => 'dokter@hospital.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
            'no_telp' => '081234567891',
        ]);

        // Dokter 2
        User::create([
            'nama' => 'Dr. Siti Nurhaliza',
            'email' => 'siti@hospital.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
            'no_telp' => '081234567892',
        ]);

        echo "✓ User seeder berhasil! 3 user dibuat.\n";
    }
}
