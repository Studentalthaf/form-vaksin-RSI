# 📋 Form Vaksinasi - Rumah Sakit Islam (RSI)

Sistem manajemen form vaksinasi berbasis Laravel 11 untuk Rumah Sakit Islam.

## 🚀 Fitur Utama

### Public Form
- ✅ Form permohonan vaksinasi online
- ✅ Upload foto KTP & Paspor
- ✅ Screening kesehatan dengan tanda tangan digital
- ✅ Validasi data otomatis

### Admin Panel
- ✅ Dashboard monitoring permohonan
- ✅ Manajemen data pasien
- ✅ Input hasil pemeriksaan fisik (TD, Nadi, Suhu, BB, TB)
- ✅ Assignment pasien ke dokter
- ✅ Manajemen user (Admin & Dokter)
- ✅ Lihat permohonan terverifikasi
- ✅ Export PDF surat persetujuan

### Dokter Panel
- ✅ Dashboard pasien yang di-assign
- ✅ Review data pasien & hasil screening
- ✅ Input catatan dokter
- ✅ Tanda tangan digital dokter
- ✅ Konfirmasi persetujuan vaksinasi

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Database**: MySQL
- **PDF Generator**: DomPDF
- **Frontend**: Tailwind CSS
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage

## 📦 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM

### Setup Steps

```bash
# Clone repository
git clone https://github.com/Studentalthaf/form-vaksin-RSI.git
cd form-vaksin-RSI

# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=form_vaksin
DB_USERNAME=root
DB_PASSWORD=

# Run migrations & seeders
php artisan migrate:fresh --seed

# Create storage link
php artisan storage:link

# Build assets
npm run build

# Start development server
php artisan serve
```

## 👤 Default Login

**Admin:**
- Email: `admin@rsi.com`
- Password: `password123`

**Dokter:**
- Email: `dokter@rsi.com`
- Password: `password123`

## 🔐 Security

- ✅ CSRF Protection
- ✅ XSS Protection
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ Role-based Access Control
- ✅ File Upload Validation
- ✅ Password Hashing (Bcrypt)

⚠️ **IMPORTANT**: 
- Ubah password default setelah deployment
- Set `APP_DEBUG=false` di production
- Generate APP_KEY baru dengan `php artisan key:generate`

## 📄 License

MIT License
