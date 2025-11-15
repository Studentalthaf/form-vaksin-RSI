<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek apakah ada index unique pada kolom email
        try {
            $indexes = DB::select("SHOW INDEX FROM `pasiens` WHERE Column_name = 'email' AND Non_unique = 0");
            
            if (!empty($indexes)) {
                // Ambil nama index yang pertama ditemukan
                $indexName = $indexes[0]->Key_name;
                
                // Hapus index menggunakan raw SQL
                DB::statement("ALTER TABLE `pasiens` DROP INDEX `{$indexName}`");
            }
        } catch (\Exception $e) {
            // Jika gagal dengan query SHOW INDEX, coba dengan dropUnique (Laravel akan mencari nama index otomatis)
            try {
                Schema::table('pasiens', function (Blueprint $table) {
                    $table->dropUnique(['email']);
                });
            } catch (\Exception $e2) {
                // Index mungkin sudah tidak ada atau nama berbeda, coba beberapa nama umum
                $commonIndexNames = ['pasiens_email_unique', 'pasiens_email_unique_index', 'email_unique'];
                
                foreach ($commonIndexNames as $indexName) {
                    try {
                        // Cek apakah index ada sebelum menghapus
                        $indexExists = DB::select("SHOW INDEX FROM `pasiens` WHERE Key_name = ?", [$indexName]);
                        if (!empty($indexExists)) {
                            DB::statement("ALTER TABLE `pasiens` DROP INDEX `{$indexName}`");
                            break; // Jika berhasil, keluar dari loop
                        }
                    } catch (\Exception $e3) {
                        // Lanjutkan ke nama index berikutnya
                        continue;
                    }
                }
                
                // Jika semua gagal, abaikan (index mungkin sudah tidak ada)
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cek apakah index unique pada email sudah ada, jika belum tambahkan
        $indexExists = DB::select("SHOW INDEX FROM `pasiens` WHERE Key_name = 'pasiens_email_unique'");
        
        if (empty($indexExists)) {
            Schema::table('pasiens', function (Blueprint $table) {
                $table->unique('email');
            });
        }
    }
};
