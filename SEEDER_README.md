# Database Seeders - Form Vaksin RSI

## Daftar Seeder yang Tersedia

Semua seeder sudah terintegrasi di `DatabaseSeeder.php` dan akan berjalan otomatis dengan satu perintah.

### 1. UserSeeder
- Membuat 3 user dengan role berbeda (admin, petugas, dokter)

### 2. ScreeningQuestionSeeder
- Membuat 13 pertanyaan screening kesehatan
- Pertanyaan dikelompokkan berdasarkan kategori

### 3. PasienSeeder ✨ (BARU)
- Membuat 10 data pasien dengan data faker
- Menggunakan locale Indonesia (`id_ID`)
- Data yang di-generate:
  - Nama sesuai jenis kelamin
  - Nomor paspor (format: 2 huruf + 6 angka, contoh: AB123456)
  - Tempat lahir (kota-kota di Indonesia)
  - Tanggal lahir (minimal umur 25 tahun)
  - Jenis kelamin (L/P)
  - Pekerjaan (15 pilihan pekerjaan umum di Indonesia)
  - Alamat (alamat Indonesia dari faker)
  - No. telepon (format: 08xxxxxxxxxx)

### 4. VaccineRequestSeeder ✨ (BARU)
- Membuat 10 permohonan vaksinasi (1 per pasien)
- Data yang di-generate:
  - Negara tujuan (15 negara populer)
  - Tanggal keberangkatan (antara 1-6 bulan dari sekarang)
  - Jenis vaksin (13 jenis vaksin internasional)
  - Nama travel agent (10 travel agent)
  - Alamat travel agent
  - Status persetujuan (70% kemungkinan disetujui)

## Cara Menjalankan Seeder

### Jalankan SEMUA seeder sekaligus:
```bash
php artisan migrate:fresh --seed
```

### Jalankan seeder tanpa reset database:
```bash
php artisan db:seed
```

### Jalankan seeder tertentu:
```bash
php artisan db:seed --class=PasienSeeder
php artisan db:seed --class=VaccineRequestSeeder
```

## Keunggulan Faker

✅ Data terlihat realistis dan profesional
✅ Otomatis generate data Indonesia (nama, alamat, dll)
✅ Data random tapi tetap valid
✅ Mudah untuk testing dan development
✅ Hemat waktu, tidak perlu input manual

## Contoh Data yang Dihasilkan

### Pasien:
- Nama: "Slamet Wahyudin" / "Rika Lestari"
- Nomor Paspor: "AB123456"
- Tempat Lahir: "Surabaya"
- Tanggal Lahir: "1995-03-15"
- Jenis Kelamin: "L"
- Pekerjaan: "Karyawan Swasta"
- Alamat: "Jl. Veteran No. 123, Jakarta Selatan"
- No. Telp: "081234567890"

### Vaccine Request:
- Negara Tujuan: "Arab Saudi"
- Tanggal Berangkat: "2025-12-15"
- Jenis Vaksin: "Meningitis + Yellow Fever"
- Nama Travel: "PT. Arminareka Perdana"
- Alamat Travel: "Jl. Thamrin No. 15, Jakarta Pusat"
- Disetujui: true/false

## File Seeder yang Dibuat

1. `database/seeders/PasienSeeder.php`
2. `database/seeders/VaccineRequestSeeder.php`
3. `database/seeders/DatabaseSeeder.php` (updated)

Semua seeder menggunakan **Faker Factory** dengan locale Indonesia untuk hasil yang lebih realistis! 🎉
