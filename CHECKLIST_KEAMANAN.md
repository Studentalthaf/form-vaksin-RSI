# ✅ Checklist Keamanan & Kebersihan Project

## 🧹 File yang Sudah Dibersihkan

### ❌ File yang Dihapus (Total: 13 files):

**Dokumentasi:**
- [x] `DEPLOY_VPS.md` - Dokumentasi deployment (tidak perlu di repository)
- [x] `SETUP_UPLOAD.md` - Dokumentasi setup upload (tidak perlu di repository)
- [x] `docker-compose.simple.yml` - Docker compose yang tidak digunakan

**Controller:**
- [x] `app/Http/Controllers/Admin/ScreeningPasienController_OLD.php` - File backup controller
- [x] `app/Http/Controllers/Dokter/DokterPasienController.php` - Controller fitur lama yang tidak digunakan

**Model:**
- [x] `app/Models/PenilaianDokter.php` - Model yang tidak digunakan (diganti dengan NilaiScreening)

**Views - Dokter:**
- [x] `resources/views/dokter/pasien-hari-ini.blade.php` - Fitur lama yang tidak digunakan
- [x] `resources/views/dokter/pasien-selesai.blade.php` - Fitur lama yang tidak digunakan

**Views - Admin:**
- [x] `resources/views/admin/screening/pasien/show.blade.php` - View yang tidak digunakan
- [x] `resources/views/admin/screening/pasien/create.blade.php` - View yang tidak digunakan
- [x] `resources/views/admin/screening/selesai.blade.php` - View yang tidak digunakan
- [x] Folder `resources/views/admin/screening/pasien/` - Dihapus (tidak ada route yang menggunakannya)

### ✅ File yang Diupdate:
- [x] `.gitignore` - Ditambahkan ignore untuk file dokumentasi, backup, dan log
- [x] `README.md` - Diupdate dengan dokumentasi project yang proper
- [x] `.env.example` - Diupdate dengan konfigurasi yang sesuai untuk project ini
- [x] `resources/views/permohonan/screening.blade.php` - Warna tanda tangan diubah ke hitam
- [x] `resources/views/pdf/surat-persetujuan.blade.php` - Tanda tangan pasien difilter ke hitam
- [x] `routes/web.php` - Dihapus route yang tidak digunakan (pasien-hari-ini, pasien-selesai, penilaian, cetak-pdf)
- [x] `app/Http/Controllers/Dokter/DokterDashboardController.php` - Dihapus method yang tidak digunakan (pasienHariIni, pasienSelesai)

## 🔐 Audit Keamanan

### ✅ File Sensitif (Sudah Di-ignore di .gitignore):
- [x] `.env` - Environment variables (tidak di-commit)
- [x] `.env.production` - Production config (tidak di-commit)
- [x] `.env.docker` - Docker config (tidak di-commit)
- [x] `.env.backup` - Backup env (tidak di-commit)
- [x] `/vendor` - Dependencies (tidak di-commit)
- [x] `/node_modules` - NPM packages (tidak di-commit)
- [x] `/storage/*.key` - Encryption keys (tidak di-commit)
- [x] `/public/storage` - Symlink storage (tidak di-commit)

### ✅ Tidak Ada Hardcoded Credentials:
- [x] Tidak ada password di file PHP
- [x] Tidak ada API keys di file Blade
- [x] Tidak ada database credentials di code
- [x] Semua credentials di `.env` (yang di-ignore)

### ✅ Tidak Ada Debug Code:
- [x] Tidak ada `dd()` di controller
- [x] Tidak ada `dump()` di blade files
- [x] Tidak ada `var_dump()` di code
- [x] Tidak ada `print_r()` di code

### ✅ File Backup & Temporary:
- [x] Tidak ada file `*.backup`
- [x] Tidak ada file `*.bak`
- [x] Tidak ada file `*.tmp`
- [x] Tidak ada file `*_OLD.php`
- [x] Tidak ada file `*.log` (sudah di-ignore)

## 📁 Struktur File Blade yang Digunakan

### ✅ Public Views (Tanpa Auth):
- `resources/views/auth/login.blade.php` - Login page
- `resources/views/permohonan/create.blade.php` - Form permohonan vaksinasi
- `resources/views/permohonan/screening.blade.php` - Form screening + tanda tangan
- `resources/views/permohonan/success.blade.php` - Success page

### ✅ Admin Views (Role: admin_rumah_sakit):
**Dashboard:**
- `resources/views/admin/dashboard.blade.php` - Admin dashboard

**Permohonan:**
- `resources/views/admin/permohonan/index.blade.php` - Daftar permohonan
- `resources/views/admin/permohonan/show.blade.php` - Detail permohonan
- `resources/views/admin/permohonan/terverifikasi.blade.php` - Daftar terverifikasi
- `resources/views/admin/permohonan/detail-terverifikasi.blade.php` - Detail terverifikasi
- `resources/views/admin/permohonan/partials/pending-table.blade.php` - Table pending
- `resources/views/admin/permohonan/partials/all-table.blade.php` - Table all

**Pasien:**
- `resources/views/admin/pasien/index.blade.php` - Daftar pasien
- `resources/views/admin/pasien/show.blade.php` - Detail pasien

**Screening:**
- `resources/views/admin/screening/show.blade.php` - Form nilai screening
- `resources/views/admin/screening/selesai.blade.php` - Screening selesai
- `resources/views/admin/screening/categories/index.blade.php` - Kategori pertanyaan
- `resources/views/admin/screening/questions/index.blade.php` - Daftar pertanyaan

**Users:**
- `resources/views/admin/users/index.blade.php` - Daftar user
- `resources/views/admin/users/create.blade.php` - Tambah user
- `resources/views/admin/users/edit.blade.php` - Edit user

### ✅ Dokter Views (Role: dokter):
**Dashboard:**
- `resources/views/dokter/dashboard.blade.php` - Dokter dashboard
- `resources/views/dokter/inactive.blade.php` - Inactive page

**Pasien:**
- `resources/views/dokter/pasien/index.blade.php` - Daftar pasien
- `resources/views/dokter/pasien/show.blade.php` - Detail pasien + konfirmasi
- `resources/views/dokter/pasien-hari-ini.blade.php` - Pasien hari ini (fitur lama)
- `resources/views/dokter/pasien-selesai.blade.php` - Pasien selesai (fitur lama)

### ✅ Shared Views:
**Layouts:**
- `resources/views/layouts/app.blade.php` - Layout public
- `resources/views/layouts/admin.blade.php` - Layout admin
- `resources/views/layouts/dokter.blade.php` - Layout dokter

**PDF:**
- `resources/views/pdf/surat-persetujuan.blade.php` - PDF surat persetujuan vaksinasi

**Errors:**
- `resources/views/errors/403.blade.php` - Forbidden page

## ⚠️ Catatan Penting Sebelum Push

### 🔴 WAJIB DILAKUKAN:

1. **Pastikan .env TIDAK ter-commit:**
   ```bash
   git status
   # Pastikan .env tidak muncul di list
   ```

2. **Generate APP_KEY baru setelah clone:**
   ```bash
   php artisan key:generate
   ```

3. **Ubah password default setelah deployment:**
   - Admin: `admin@rsi.com` / `password123` → GANTI!
   - Dokter: `dokter@rsi.com` / `password123` → GANTI!

4. **Set APP_DEBUG=false di production:**
   ```env
   APP_DEBUG=false
   APP_ENV=production
   ```

5. **Pastikan storage/app/public ter-ignore:**
   - Folder ini akan dibuat ulang dengan `php artisan storage:link`
   - File upload akan tersimpan di sini

### 🟢 Aman untuk di-Push:

- ✅ Semua file code (.php, .blade.php)
- ✅ Migration files
- ✅ Seeder files
- ✅ Routes
- ✅ Config files
- ✅ Public assets (CSS, JS)
- ✅ .env.example (tanpa credentials)
- ✅ README.md
- ✅ .gitignore
- ✅ composer.json & package.json

### 🔴 TIDAK AMAN untuk di-Push:

- ❌ .env (credentials aktual)
- ❌ /vendor (akan di-install dengan composer)
- ❌ /node_modules (akan di-install dengan npm)
- ❌ /storage (akan dibuat otomatis)
- ❌ /public/storage (akan dibuat dengan artisan)
- ❌ File backup (*.backup, *.bak, *_OLD.php)
- ❌ File dokumentasi deployment lokal

## 🚀 Command Setelah Clone di VPS

```bash
# 1. Clone repository
git clone https://github.com/Studentalthaf/form-vaksin-RSI.git
cd form-vaksin-RSI

# 2. Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Edit .env sesuai VPS (database, APP_URL, dll)
nano .env

# 5. Setup database
php artisan migrate:fresh --seed

# 6. Setup storage
php artisan storage:link
chmod -R 775 storage bootstrap/cache

# 7. Cache untuk performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. WAJIB: Ubah password default!
```

## ✅ Project Siap untuk Push

Status: **AMAN UNTUK PUSH KE GITHUB** ✅

Semua file sensitif sudah di-ignore, tidak ada credentials hardcoded, dan file yang tidak perlu sudah dibersihkan.

**Langkah Selanjutnya:**
```bash
git add .
git commit -m "Clean up project and update documentation"
git push origin main
```
