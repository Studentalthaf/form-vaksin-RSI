# 🚀 Panduan Deployment ke VPS

## 📋 Prasyarat VPS

- Ubuntu 20.04/22.04 atau CentOS 7/8
- Minimal RAM: 2GB (recommended 4GB)
- PHP 8.2 atau lebih tinggi
- MySQL 8.0 atau MariaDB 10.6+
- Composer
- Nginx atau Apache
- Git

---

## 🔧 Setup VPS (Fresh Install)

### 1️⃣ Update System & Install Dependencies

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 & Extensions
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common \
    php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl \
    php8.2-xml php8.2-bcmath php8.2-json php8.2-intl

# Install MySQL
sudo apt install -y mysql-server

# Install Nginx
sudo apt install -y nginx

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Git
sudo apt install -y git

# Install Node.js (untuk build assets)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

---

## 📦 Clone & Install Project

### 2️⃣ Clone Repository dari GitHub

```bash
# Masuk ke direktori web server
cd /var/www

# Clone repository (ganti dengan URL Anda)
sudo git clone https://github.com/Studentalthaf/form-vaksin-RSI.git
cd form-vaksin-RSI

# Set ownership ke user www-data (nginx/apache)
sudo chown -R www-data:www-data /var/www/form-vaksin-RSI
sudo chmod -R 755 /var/www/form-vaksin-RSI
```

### 3️⃣ Install Dependencies

```bash
# Install PHP dependencies
sudo -u www-data composer install --no-dev --optimize-autoloader

# Install NPM dependencies & build assets
npm install
npm run build

# Hapus node_modules setelah build (opsional, hemat space)
rm -rf node_modules
```

---

## ⚙️ Konfigurasi Environment

### 4️⃣ Setup File .env

```bash
# Copy .env.example ke .env
sudo -u www-data cp .env.example .env

# Edit .env dengan editor pilihan Anda
sudo nano .env
```

**Konfigurasi .env untuk Production:**

```env
APP_NAME="Form Vaksin RSI"
APP_ENV=production
APP_KEY=
APP_DEBUG=false                          # ⚠️ WAJIB false di production!
APP_URL=https://vaksin.yourdomain.com    # ⚠️ Ganti dengan domain/IP Anda
APP_TIMEZONE=Asia/Jakarta

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=form_vaksin_prod             # ⚠️ Ganti dengan nama database Anda
DB_USERNAME=form_vaksin_user             # ⚠️ Ganti dengan user database
DB_PASSWORD=strong_password_here_123!    # ⚠️ WAJIB ganti dengan password kuat!

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (opsional, untuk notifikasi email)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Log
LOG_CHANNEL=daily
LOG_LEVEL=error                          # ⚠️ Jangan debug di production!
```

### 5️⃣ Generate APP_KEY

```bash
# Generate application key
sudo -u www-data php artisan key:generate

# Verify .env sudah ada APP_KEY
cat .env | grep APP_KEY
```

---

## 🗄️ Setup Database

### 6️⃣ Buat Database & User

```bash
# Masuk ke MySQL
sudo mysql

# Di MySQL prompt:
```

```sql
-- Buat database
CREATE DATABASE form_vaksin_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Buat user khusus (ganti password!)
CREATE USER 'form_vaksin_user'@'localhost' IDENTIFIED BY 'strong_password_here_123!';

-- Berikan akses
GRANT ALL PRIVILEGES ON form_vaksin_prod.* TO 'form_vaksin_user'@'localhost';

-- Flush privileges
FLUSH PRIVILEGES;

-- Keluar
EXIT;
```

### 7️⃣ Jalankan Migration & Seeder

```bash
# Test koneksi database dulu
sudo -u www-data php artisan db:show

# Jalankan migration (buat tabel)
sudo -u www-data php artisan migrate --force

# Jalankan seeder (data default: admin, dokter, pertanyaan screening)
sudo -u www-data php artisan db:seed --force

# Atau migration + seed sekaligus (HATI-HATI: hapus semua data!)
# sudo -u www-data php artisan migrate:fresh --seed --force
```

---

## 🔗 Setup Storage & Permissions

### 8️⃣ Create Storage Link & Set Permissions

```bash
# Buat symbolic link untuk storage
sudo -u www-data php artisan storage:link

# Set permission yang benar
sudo chown -R www-data:www-data /var/www/form-vaksin-RSI/storage
sudo chown -R www-data:www-data /var/www/form-vaksin-RSI/bootstrap/cache
sudo chmod -R 775 /var/www/form-vaksin-RSI/storage
sudo chmod -R 775 /var/www/form-vaksin-RSI/bootstrap/cache

# Verify
ls -la storage/
ls -la bootstrap/cache/
```

---

## 🌐 Konfigurasi Web Server (Nginx)

### 9️⃣ Setup Nginx Virtual Host

```bash
# Buat file konfigurasi Nginx
sudo nano /etc/nginx/sites-available/form-vaksin
```

**Konfigurasi Nginx:**

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name vaksin.yourdomain.com;  # ⚠️ Ganti dengan domain Anda
    root /var/www/form-vaksin-RSI/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Security headers
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "no-referrer-when-downgrade";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Max upload size (untuk foto KTP/Paspor)
    client_max_body_size 10M;
}
```

**Enable site & restart Nginx:**

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/form-vaksin /etc/nginx/sites-enabled/

# Test konfigurasi
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx

# Enable Nginx auto-start
sudo systemctl enable nginx
```

---

## 🔒 Setup SSL dengan Let's Encrypt (HTTPS)

### 🔟 Install Certbot & Generate SSL

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Generate SSL certificate
sudo certbot --nginx -d vaksin.yourdomain.com

# Auto-renewal test
sudo certbot renew --dry-run
```

Certbot akan otomatis update konfigurasi Nginx dengan HTTPS.

---

## ⚡ Optimasi Performance

### 1️⃣1️⃣ Cache Configuration

```bash
# Cache config, routes, dan views
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Optimize autoloader
sudo -u www-data composer dump-autoload --optimize --no-dev
```

### 1️⃣2️⃣ Setup Queue Worker (Opsional)

Jika menggunakan queue untuk email/notifikasi:

```bash
# Buat systemd service
sudo nano /etc/systemd/system/form-vaksin-worker.service
```

```ini
[Unit]
Description=Form Vaksin Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
RestartSec=5s
WorkingDirectory=/var/www/form-vaksin-RSI
ExecStart=/usr/bin/php /var/www/form-vaksin-RSI/artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```

```bash
# Enable & start queue worker
sudo systemctl enable form-vaksin-worker
sudo systemctl start form-vaksin-worker
sudo systemctl status form-vaksin-worker
```

---

## 🔐 Keamanan & Maintenance

### 1️⃣3️⃣ Setup Firewall

```bash
# Install UFW
sudo apt install -y ufw

# Allow SSH, HTTP, HTTPS
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

### 1️⃣4️⃣ Setup Backup Database (Cron Job)

```bash
# Edit crontab
sudo crontab -e

# Tambahkan baris ini (backup setiap hari jam 2 pagi)
0 2 * * * /usr/bin/mysqldump -u form_vaksin_user -p'strong_password_here_123!' form_vaksin_prod | gzip > /var/backups/form-vaksin-$(date +\%Y\%m\%d).sql.gz

# Buat direktori backup
sudo mkdir -p /var/backups
sudo chmod 700 /var/backups
```

### 1️⃣5️⃣ Setup Laravel Scheduler (Cron)

```bash
# Edit crontab untuk user www-data
sudo crontab -e -u www-data

# Tambahkan baris ini:
* * * * * cd /var/www/form-vaksin-RSI && php artisan schedule:run >> /dev/null 2>&1
```

---

## ✅ Verifikasi & Testing

### 1️⃣6️⃣ Checklist Akhir

```bash
# 1. Cek permission
ls -la storage/ bootstrap/cache/

# 2. Cek symbolic link storage
ls -la public/storage

# 3. Cek koneksi database
php artisan db:show

# 4. Cek route
php artisan route:list

# 5. Cek environment
php artisan about

# 6. Cek logs jika ada error
tail -f storage/logs/laravel.log
```

### 1️⃣7️⃣ Test Aplikasi

1. **Buka browser:** `https://vaksin.yourdomain.com`
2. **Test form publik:** Isi form permohonan vaksinasi
3. **Login Admin:** `admin@rsi.com` / `password123`
4. **Login Dokter:** `dokter@rsi.com` / `password123`
5. **Test upload file:** Upload foto KTP/Paspor
6. **Test PDF:** Cetak PDF surat persetujuan

---

## 🔄 Update Aplikasi (Setelah Push ke GitHub)

```bash
# Masuk ke direktori project
cd /var/www/form-vaksin-RSI

# Pull perubahan terbaru
sudo -u www-data git pull origin main

# Install dependencies baru (jika ada)
sudo -u www-data composer install --no-dev --optimize-autoloader

# Build assets baru (jika ada perubahan CSS/JS)
npm install && npm run build

# Jalankan migration baru (jika ada)
sudo -u www-data php artisan migrate --force

# Clear cache
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear

# Re-cache untuk performance
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Restart Queue Worker (jika pakai)
sudo systemctl restart form-vaksin-worker
```

---

## 🚨 Troubleshooting

### Error: "Permission denied" saat upload file

```bash
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/
```

### Error: "Class not found"

```bash
sudo -u www-data composer dump-autoload
sudo -u www-data php artisan clear-compiled
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"

```bash
# Cek MySQL berjalan
sudo systemctl status mysql

# Cek kredensial di .env
cat .env | grep DB_
```

### Error 500 - Internal Server Error

```bash
# Cek log Laravel
tail -f storage/logs/laravel.log

# Cek log Nginx
sudo tail -f /var/log/nginx/error.log
```

### File upload tidak muncul

```bash
# Cek symbolic link
ls -la public/storage

# Buat ulang jika perlu
sudo -u www-data php artisan storage:link
```

---

## 🎯 Kesimpulan

**Langkah Minimum untuk Deploy:**

1. ✅ Clone repository dari GitHub
2. ✅ Install dependencies (`composer install`)
3. ✅ Copy & edit `.env` (database, APP_KEY, APP_URL)
4. ✅ Setup database (create DB, user)
5. ✅ Jalankan migration & seeder
6. ✅ Setup storage link & permissions
7. ✅ Konfigurasi Nginx/Apache
8. ✅ Setup SSL (optional tapi recommended)
9. ✅ Cache config untuk performance
10. ✅ **WAJIB:** Ganti password default admin & dokter!

---

## 📞 Support

Jika ada masalah saat deployment:

1. Cek log: `storage/logs/laravel.log`
2. Cek Nginx log: `/var/log/nginx/error.log`
3. Cek PHP-FPM log: `/var/log/php8.2-fpm.log`
4. Test manual: `php artisan serve --host=0.0.0.0 --port=8000`

---

**✅ Setelah deployment berhasil, WAJIB ganti password default!**

```bash
# Login sebagai admin, lalu:
# Admin → User Management → Edit Admin → Ganti Password
# Admin → User Management → Edit Dokter → Ganti Password
```

🎉 **Aplikasi siap digunakan di production!**
