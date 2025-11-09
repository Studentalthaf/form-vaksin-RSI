# 💉 Form Vaksinasi - Rumah Sakit Islam (RSI)# 📋 Form Vaksinasi - Rumah Sakit Islam (RSI)



> Sistem manajemen permohonan vaksinasi digital untuk Rumah Sakit Islam dengan fitur screening kesehatan, verifikasi dokter, dan dokumentasi digital lengkap.Sistem manajemen form vaksinasi berbasis Laravel 11 untuk Rumah Sakit Islam.



[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)## 🚀 Fitur Utama

[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)

[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)### Public Form

- ✅ Form permohonan vaksinasi online

---- ✅ Upload foto KTP & Paspor

- ✅ Screening kesehatan dengan tanda tangan digital

## 📋 Daftar Isi- ✅ Validasi data otomatis



- [Fitur Utama](#-fitur-utama)### Admin Panel

- [Teknologi](#-teknologi)- ✅ Dashboard monitoring permohonan

- [Requirements](#-requirements)- ✅ Manajemen data pasien

- [Instalasi Lokal](#-instalasi-lokal)- ✅ Input hasil pemeriksaan fisik (TD, Nadi, Suhu, BB, TB)

- [Deployment ke VPS](#-deployment-ke-vps)- ✅ Assignment pasien ke dokter

- [Penggunaan](#-penggunaan)- ✅ Manajemen user (Admin & Dokter)

- [User Default](#-user-default)- ✅ Lihat permohonan terverifikasi

- [Struktur Database](#-struktur-database)- ✅ Export PDF surat persetujuan

- [Troubleshooting](#-troubleshooting)

### Dokter Panel

---- ✅ Dashboard pasien yang di-assign

- ✅ Review data pasien & hasil screening

## ✨ Fitur Utama- ✅ Input catatan dokter

- ✅ Tanda tangan digital dokter

### 🌐 **Public Form (Tanpa Login)**- ✅ Konfirmasi persetujuan vaksinasi

- ✅ Form permohonan vaksinasi online

- ✅ Upload foto KTP & Paspor## 🛠️ Tech Stack

- ✅ Screening kesehatan mandiri dengan pertanyaan dinamis

- ✅ Digital signature pasien menggunakan canvas- **Framework**: Laravel 11

- ✅ Support vaksinasi perjalanan luar negeri (Yellow Fever, dll)- **Database**: MySQL

- **PDF Generator**: DomPDF

### 👨‍⚕️ **Dashboard Dokter**- **Frontend**: Tailwind CSS

- ✅ Daftar pasien yang telah di-assign oleh admin- **Authentication**: Laravel Sanctum

- ✅ Review data pasien & hasil screening- **File Storage**: Laravel Storage

- ✅ Verifikasi kesehatan & digital signature dokter

- ✅ Catatan dokter untuk setiap pasien## 📦 Installation

- ✅ Status konfirmasi real-time

### Prerequisites

### 👩‍💼 **Dashboard Admin**- PHP >= 8.2

- ✅ Management permohonan pasien masuk- Composer

- ✅ Input data pemeriksaan fisik (TD, Nadi, Suhu, BB, TB)- MySQL

- ✅ Assign pasien ke dokter- Node.js & NPM

- ✅ Daftar permohonan terverifikasi

- ✅ Cetak PDF surat persetujuan vaksinasi### Setup Steps

- ✅ Management user (Admin & Dokter)

- ✅ Management pertanyaan screening dinamis```bash

- ✅ Data master pasien dengan nomor rekam medis# Clone repository

git clone https://github.com/Studentalthaf/form-vaksin-RSI.git

### 📄 **PDF Generation**cd form-vaksin-RSI

- ✅ Surat persetujuan vaksinasi lengkap

- ✅ Include digital signature pasien & dokter# Install dependencies

- ✅ Data pemeriksaan fisikcomposer install

- ✅ Hasil screening kesehatannpm install

- ✅ Format professional 3 halaman

# Copy environment file

---cp .env.example .env



## 🛠️ Teknologi# Generate application key

php artisan key:generate

### Backend

- **Laravel 11.x** - PHP Framework# Configure database in .env

- **MySQL 8.0** - DatabaseDB_CONNECTION=mysql

- **PHP 8.2+** - Programming LanguageDB_HOST=127.0.0.1

DB_PORT=3306

### FrontendDB_DATABASE=form_vaksin

- **Tailwind CSS 3.x** - CSS FrameworkDB_USERNAME=root

- **Vite** - Asset BundlerDB_PASSWORD=

- **Canvas API** - Digital Signature

# Run migrations & seeders

### Librariesphp artisan migrate:fresh --seed

- **DomPDF** - PDF Generation

- **Laravel Breeze** (optional) - Authentication# Create storage link

php artisan storage:link

---

# Build assets

## 📦 Requirementsnpm run build



### Development (Local)# Start development server

- PHP >= 8.2php artisan serve

- Composer```

- Node.js >= 18.x & NPM

- MySQL >= 8.0 atau MariaDB >= 10.6## 👤 Default Login

- Laravel Valet / Laragon / XAMPP

**Admin:**

### Production (VPS)- Email: `admin@rsi.com`

- Ubuntu 20.04/22.04- Password: `password123`

- PHP 8.2 + Extensions (mysql, zip, gd, mbstring, curl, xml, bcmath)

- MySQL 8.0 / MariaDB 10.6+**Dokter:**

- Nginx atau Apache- Email: `dokter@rsi.com`

- Composer & Git- Password: `password123`

- SSL Certificate (Let's Encrypt recommended)

## 🔐 Security

---

- ✅ CSRF Protection

## 🚀 Instalasi Lokal- ✅ XSS Protection

- ✅ SQL Injection Prevention (Eloquent ORM)

### 1. Clone Repository- ✅ Role-based Access Control

- ✅ File Upload Validation

```bash- ✅ Password Hashing (Bcrypt)

git clone https://github.com/Studentalthaf/form-vaksin-RSI.git

cd form-vaksin-RSI⚠️ **IMPORTANT**: 

```- Ubah password default setelah deployment

- Set `APP_DEBUG=false` di production

### 2. Install Dependencies- Generate APP_KEY baru dengan `php artisan key:generate`



```bash## 📄 License

# Install PHP dependencies

composer installMIT License


# Install NPM dependencies
npm install
```

### 3. Setup Environment

```bash
# Copy .env.example ke .env
cp .env.example .env

# Generate APP_KEY
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=form_vaksin
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Setup Database

```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder (data default)
php artisan db:seed
```

### 6. Setup Storage

```bash
# Buat symbolic link
php artisan storage:link

# Set permission (jika di Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

### 7. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Jalankan Aplikasi

```bash
# Via Laravel serve
php artisan serve

# Buka browser: http://localhost:8000
```

---

## 🌐 Deployment ke VPS

Lihat panduan lengkap di **[DEPLOYMENT.md](DEPLOYMENT.md)**

**Ringkasan singkat:**

```bash
# 1. Clone dari GitHub
git clone https://github.com/Studentalthaf/form-vaksin-RSI.git
cd form-vaksin-RSI

# 2. Install dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# 3. Setup .env & generate key
cp .env.example .env
php artisan key:generate
# Edit .env (database, APP_URL, set APP_DEBUG=false)

# 4. Setup database
php artisan migrate --force
php artisan db:seed --force

# 5. Setup storage & permissions
php artisan storage:link
chmod -R 775 storage bootstrap/cache

# 6. Cache untuk performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Lihat file [DEPLOYMENT.md](DEPLOYMENT.md) untuk:**
- Setup Nginx/Apache
- SSL Certificate (HTTPS)
- Firewall & Security
- Database Backup
- Update aplikasi

---

## 📖 Penggunaan

### Alur Kerja Aplikasi

```
1. PASIEN (Public)
   └─> Isi form permohonan
   └─> Upload KTP/Paspor
   └─> Jawab screening kesehatan
   └─> Tanda tangan digital
   └─> Submit

2. ADMIN
   └─> Review permohonan masuk
   └─> Input data pemeriksaan fisik (TD, Nadi, Suhu, BB, TB)
   └─> Assign ke dokter

3. DOKTER
   └─> Lihat daftar pasien yang di-assign
   └─> Review data & hasil pemeriksaan
   └─> Input catatan dokter
   └─> Tanda tangan digital
   └─> Konfirmasi pasien

4. ADMIN (Selesai)
   └─> Lihat "Permohonan Terverifikasi"
   └─> Cetak PDF surat persetujuan lengkap
```

---

## 🔑 User Default

Setelah running seeder:

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| **Admin** | admin@rsi.com | password123 | Dashboard Admin |
| **Dokter** | dokter@rsi.com | password123 | Dashboard Dokter |

⚠️ **WAJIB ganti password setelah deployment production!**

---

## 🗄️ Struktur Database

### Tabel Utama

- **users** - User (Admin & Dokter)
- **pasiens** - Data pasien
- **vaccine_requests** - Permohonan vaksinasi
- **screenings** - Data screening & konfirmasi
- **nilai_screening** - Hasil pemeriksaan admin
- **screening_questions** - Pertanyaan screening dinamis
- **screening_question_categories** - Kategori pertanyaan
- **screening_answers** - Jawaban pasien

### Relasi

```
vaccine_requests
    └─> hasOne: screenings
    └─> belongsTo: pasiens

screenings
    └─> hasOne: nilai_screening
    └─> hasMany: screening_answers
    └─> belongsTo: dokter (users)
    └─> belongsTo: admin (users)
```

---

## 🔧 Troubleshooting

### Error: "Class not found"

```bash
composer dump-autoload
php artisan clear-compiled
```

### Error: "Permission denied" saat upload

```bash
chmod -R 775 storage bootstrap/cache
```

### Error: "SQLSTATE[HY000] [2002]"

- Pastikan MySQL/MariaDB running
- Cek kredensial database di `.env`
- Test koneksi: `php artisan db:show`

### PDF tidak generate

- Pastikan PHP extension `mbstring` & `gd` installed
- Clear cache: `php artisan view:clear`
- Cek log: `storage/logs/laravel.log`

### Tanda tangan tidak tersimpan

- Pastikan `storage/app/public/signatures` writable
- Cek symbolic link: `php artisan storage:link`

---

## 📞 Contact & Support

- **Developer:** Studentalthaf
- **Repository:** [github.com/Studentalthaf/form-vaksin-RSI](https://github.com/Studentalthaf/form-vaksin-RSI)
- **Issues:** [Report Bug](https://github.com/Studentalthaf/form-vaksin-RSI/issues)

---

## 📝 Changelog

### Version 1.0.0 (2025-11-09)
- ✅ Initial release
- ✅ Public form permohonan vaksinasi
- ✅ Digital signature (pasien & dokter)
- ✅ Admin & Dokter dashboard
- ✅ PDF generation
- ✅ Upload foto KTP/Paspor
- ✅ Screening kesehatan dinamis

---

## 📄 License

This project is licensed under the MIT License.

---

**Made with ❤️ for Rumah Sakit Islam**
