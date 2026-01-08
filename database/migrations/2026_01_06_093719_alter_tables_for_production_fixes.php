<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        // 1. Drop kolom 'pilihan_jawaban' dari tabel 'screening_questions'
        if (Schema::hasColumn('screening_questions', 'pilihan_jawaban')) {
            Schema::table('screening_questions', function (Blueprint $table) {
                $table->dropColumn('pilihan_jawaban');
            });
        }

        // 2. Rename kolom di tabel 'nilai_screening'
        Schema::table('nilai_screening', function (Blueprint $table) {
            // Rename tanggal_berangkat_umroh menjadi tanggal_berangkat
            if (Schema::hasColumn('nilai_screening', 'tanggal_berangkat_umroh')) {
                $table->renameColumn('tanggal_berangkat_umroh', 'tanggal_berangkat');
            }
            
            // Rename td menjadi tekanan_darah
            if (Schema::hasColumn('nilai_screening', 'td')) {
                $table->renameColumn('td', 'tekanan_darah');
            }
            
            // Rename suhu menjadi suhu_badan
            if (Schema::hasColumn('nilai_screening', 'suhu')) {
                $table->renameColumn('suhu', 'suhu_badan');
            }
            
            // Rename tb menjadi tinggi_badan
            if (Schema::hasColumn('nilai_screening', 'tb')) {
                $table->renameColumn('tb', 'tinggi_badan');
            }
            
            // Rename bb menjadi berat_badan
            if (Schema::hasColumn('nilai_screening', 'bb')) {
                $table->renameColumn('bb', 'berat_badan');
            }
            if (Schema::hasColumn('nilai_screening', 'kapan')) {
                $table->renameColumn('kapan', 'tanggal_vaksin_pasien');
            }
            if (Schema::hasColumn('nilai_screening', 'dimana')) {
                $table->renameColumn('dimana', 'tempat_vaksin_pasien');
            }

            // Drop kolom redundant yang datanya sudah ada di tabel vaccine_requests
            $redundantColumns = ['jenis_vaksin', 'negara_tujuan', 'tanggal_berangkat'];
            foreach ($redundantColumns as $column) {
                if (Schema::hasColumn('nilai_screening', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // 3. Drop kolom pemeriksaan fisik dari tabel 'screenings'
        // Kolom-kolom ini duplikat karena data sudah ada di tabel 'nilai_screenings'
        Schema::table('screenings', function (Blueprint $table) {
            $columnsToRemove = [
                'tekanan_darah_sistol',
                'tekanan_darah_diastol',
                'nadi',
                'suhu', 
                'berat_badan',
                'tinggi_badan',
                'saturasi_oksigen',
                'catatan_pemeriksaan', 
            ];

            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('screenings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // 4. Tambahkan soft delete dan tracking untuk tabel 'vaksins'
        Schema::table('vaksins', function (Blueprint $table) {
            // Soft delete
            if (!Schema::hasColumn('vaksins', 'deleted_at')) {
                $table->softDeletes();
            }
            
            // Tracking siapa yang membuat
            if (!Schema::hasColumn('vaksins', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('aktif');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // Tracking siapa yang mengedit
            if (!Schema::hasColumn('vaksins', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // Tracking siapa yang menghapus
            if (!Schema::hasColumn('vaksins', 'deleted_by')) {
                $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
                $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            }
        });

    }

    /**
     * Reverse the migrations.
     * 
     * Rollback untuk mengembalikan perubahan jika terjadi masalah
     */
    public function down(): void
    {
        // 1. Kembalikan kolom 'pilihan_jawaban' ke tabel 'screening_questions'
        if (!Schema::hasColumn('screening_questions', 'pilihan_jawaban')) {
            Schema::table('screening_questions', function (Blueprint $table) {
                $table->json('pilihan_jawaban')->nullable()->after('pertanyaan');
            });
        }

        // 2. Kembalikan nama kolom di tabel 'nilai_screening'
        Schema::table('nilai_screening', function (Blueprint $table) {
            // Kembalikan tanggal_berangkat menjadi tanggal_berangkat_umroh
            if (Schema::hasColumn('nilai_screening', 'tanggal_berangkat')) {
                $table->renameColumn('tanggal_berangkat', 'tanggal_berangkat_umroh');
            }
            
            // Kembalikan tekanan_darah menjadi td
            if (Schema::hasColumn('nilai_screening', 'tekanan_darah')) {
                $table->renameColumn('tekanan_darah', 'td');
            }
            
            // Kembalikan suhu_badan menjadi suhu
            if (Schema::hasColumn('nilai_screening', 'suhu_badan')) {
                $table->renameColumn('suhu_badan', 'suhu');
            }
            
            // Kembalikan tinggi_badan menjadi tb
            if (Schema::hasColumn('nilai_screening', 'tinggi_badan')) {
                $table->renameColumn('tinggi_badan', 'tb');
            }
            
            // Kembalikan berat_badan menjadi bb
            if (Schema::hasColumn('nilai_screening', 'berat_badan')) {
                $table->renameColumn('berat_badan', 'bb');
            }

            // Kembalikan tanggal_vaksin_pasien menjadi kapan
            if (Schema::hasColumn('nilai_screening', 'tanggal_vaksin_pasien')) {
                $table->renameColumn('tanggal_vaksin_pasien', 'kapan');
            }

            // Kembalikan tempat_vaksin_pasien menjadi dimana
            if (Schema::hasColumn('nilai_screening', 'tempat_vaksin_pasien')) {
                $table->renameColumn('tempat_vaksin_pasien', 'dimana');
            }

            // Kembalikan kolom redundant yang dihapus
            if (!Schema::hasColumn('nilai_screening', 'jenis_vaksin')) {
                $table->string('jenis_vaksin')->nullable();
            }
            if (!Schema::hasColumn('nilai_screening', 'negara_tujuan')) {
                $table->string('negara_tujuan')->nullable();
            }
            if (!Schema::hasColumn('nilai_screening', 'tanggal_berangkat')) {
                $table->date('tanggal_berangkat')->nullable();
            }
        });

        // 3. Kembalikan kolom pemeriksaan fisik ke tabel 'screenings'
        Schema::table('screenings', function (Blueprint $table) {
            if (!Schema::hasColumn('screenings', 'tekanan_darah_sistol')) {
                $table->integer('tekanan_darah_sistol')->nullable();
            }
            if (!Schema::hasColumn('screenings', 'tekanan_darah_diastol')) {
                $table->integer('tekanan_darah_diastol')->nullable();
            }
            if (!Schema::hasColumn('screenings', 'nadi')) {
                $table->integer('nadi')->nullable();
            }
            if (!Schema::hasColumn('screenings', 'suhu')) {
                $table->decimal('suhu', 4, 1)->nullable();
            }
            if (!Schema::hasColumn('screenings', 'berat_badan')) {
                $table->decimal('berat_badan', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('screenings', 'tinggi_badan')) {
                $table->decimal('tinggi_badan', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('screenings', 'saturasi_oksigen')) {
                $table->integer('saturasi_oksigen')->nullable();
            }
            if (!Schema::hasColumn('screenings', 'catatan_pemeriksaan')) {
                $table->text('catatan_pemeriksaan')->nullable();
            }
            if (!Schema::hasColumn('screenings', 'catatan_dokter')) {
                $table->text('catatan_dokter')->nullable();
            }
        });

        // 4. Kembalikan kolom 'disetujui' ke tabel 'vaccine_requests'
        if (!Schema::hasColumn('vaccine_requests', 'disetujui')) {
            Schema::table('vaccine_requests', function (Blueprint $table) {
                $table->boolean('disetujui')->default(false)->nullable();
            });
        }

        // 5. Hapus soft delete dan tracking dari tabel 'vaksins'
        Schema::table('vaksins', function (Blueprint $table) {
            // Drop foreign keys terlebih dahulu
            if (Schema::hasColumn('vaksins', 'created_by')) {
                $table->dropForeign(['created_by']);
            }
            if (Schema::hasColumn('vaksins', 'updated_by')) {
                $table->dropForeign(['updated_by']);
            }
            if (Schema::hasColumn('vaksins', 'deleted_by')) {
                $table->dropForeign(['deleted_by']);
            }
            
            // Drop columns
            $columnsToRemove = ['deleted_at', 'created_by', 'updated_by', 'deleted_by'];
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('vaksins', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
