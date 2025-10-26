# 🚀 Form Vaksin RSI - Docker Deployment Guide

Panduan deployment aplikasi Form Vaksin RSI menggunakan Docker di VPS.

## 📋 Prerequisites

- VPS dengan Ubuntu 20.04+ / Debian 11+
- Minimal 2GB RAM
- Minimal 20GB Storage
- Akses SSH ke VPS
- Domain (optional)

## 🔧 Setup di VPS

### 1. Clone Repository

```bash
# Login ke VPS
ssh user@your-vps-ip

# Clone repository
git clone https://github.com/Studentalthaf/form-vaksin-RSI.git
cd form-vaksin-RSI
```

### 2. Konfigurasi Environment

```bash
# Copy .env.example ke .env
cp .env.example .env

# Edit .env dan sesuaikan:
nano .env
```

**Penting! Update nilai berikut di `.env`:**

```env
APP_NAME="Form Vaksin RSI"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://YOUR_VPS_IP:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=form_vaksin
DB_USERNAME=formvaksin_user
DB_PASSWORD=YOUR_SECURE_PASSWORD

# ... setting lainnya
```

### 3. Konfigurasi Docker Environment

```bash
# Edit .env.docker untuk password database
nano .env.docker
```

Update dengan password yang kuat:

```env
APP_PORT=8000
DB_DATABASE=form_vaksin
DB_USERNAME=formvaksin_user
DB_PASSWORD=YourSecurePassword123!
DB_ROOT_PASSWORD=YourRootPassword123!
DB_PORT=3306
PMA_PORT=8080
```

### 4. Jalankan Deployment Script

```bash
# Beri permission execute
chmod +x deploy.sh

# Jalankan script
./deploy.sh
```

Script akan otomatis:
- ✅ Install Docker & Docker Compose (jika belum ada)
- ✅ Setup environment files
- ✅ Build Docker images
- ✅ Start semua containers
- ✅ Setup database
- ✅ Run migrations
- ✅ Optimize Laravel

### 5. Verifikasi Deployment

```bash
# Check container status
docker ps

# Lihat logs
docker compose logs -f formvaksin_app
```

### 6. Akses Aplikasi

- **Aplikasi:** `http://YOUR_VPS_IP:8000`
- **PHPMyAdmin:** `http://YOUR_VPS_IP:8080`

## 🔄 Update Aplikasi

```bash
# Pull latest code
git pull origin main

# Rebuild dan restart
docker compose down
docker compose --env-file .env.docker up -d --build

# Run migrations (jika ada)
docker exec formvaksin_app php artisan migrate --force

# Clear cache
docker exec formvaksin_app php artisan config:cache
docker exec formvaksin_app php artisan route:cache
docker exec formvaksin_app php artisan view:cache
```

## 🛠️ Perintah Berguna

### Container Management

```bash
# Lihat container yang berjalan
docker ps

# Lihat semua container (termasuk stopped)
docker ps -a

# Stop semua container
docker compose down

# Start containers
docker compose --env-file .env.docker up -d

# Restart containers
docker compose restart

# Lihat logs real-time
docker compose logs -f

# Lihat logs specific container
docker logs -f formvaksin_app
```

### Laravel Commands

```bash
# Masuk ke container
docker exec -it formvaksin_app bash

# Run artisan commands
docker exec formvaksin_app php artisan migrate
docker exec formvaksin_app php artisan db:seed
docker exec formvaksin_app php artisan cache:clear
docker exec formvaksin_app php artisan config:clear
docker exec formvaksin_app php artisan route:cache
docker exec formvaksin_app php artisan view:cache

# Composer commands
docker exec formvaksin_app composer install
docker exec formvaksin_app composer update
```

### Database Management

```bash
# Backup database
docker exec formvaksin_db mysqladmin -u root -p dump form_vaksin > backup-$(date +%Y%m%d).sql

# Restore database
docker exec -i formvaksin_db mysql -u root -p form_vaksin < backup.sql

# Akses MySQL CLI
docker exec -it formvaksin_db mysql -u root -p
```

## 🔒 Security Best Practices

### 1. Firewall Setup

```bash
# Install UFW
sudo apt install ufw

# Allow SSH
sudo ufw allow 22/tcp

# Allow Application port
sudo ufw allow 8000/tcp

# Allow HTTPS (jika pakai SSL)
sudo ufw allow 443/tcp

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

### 2. Disable PHPMyAdmin di Production

Edit `docker-compose.yml` dan comment/hapus section PHPMyAdmin:

```yaml
# phpmyadmin:
#   image: phpmyadmin/phpmyadmin
#   ...
```

### 3. SSL/HTTPS dengan Let's Encrypt

Jika punya domain, install SSL:

```bash
# Install Certbot
sudo apt install certbot

# Get certificate
sudo certbot certonly --standalone -d yourdomain.com

# Update Nginx config di docker/nginx.conf
# Tambah SSL configuration
```

### 4. Regular Backups

Buat cronjob untuk backup otomatis:

```bash
# Edit crontab
crontab -e

# Tambahkan (backup setiap hari jam 2 pagi)
0 2 * * * docker exec formvaksin_db mysqladmin -u root -pYourPassword dump form_vaksin > /backup/db-$(date +\%Y\%m\%d).sql
```

## 📊 Monitoring

### Resource Usage

```bash
# Monitor container resources
docker stats

# Disk usage
docker system df

# Logs size
du -sh /var/lib/docker/containers/*/*-json.log
```

### Health Checks

```bash
# Check container health
docker ps --format "table {{.Names}}\t{{.Status}}"

# Test application endpoint
curl http://localhost:8000

# Check database connectivity
docker exec formvaksin_app php artisan migrate:status
```

## 🐛 Troubleshooting

### Container tidak start

```bash
# Lihat logs lengkap
docker compose logs

# Rebuild tanpa cache
docker compose build --no-cache

# Check port conflict
sudo netstat -tulpn | grep :8000
```

### Database connection error

```bash
# Verifikasi database container
docker exec formvaksin_db mysql -u root -p -e "SHOW DATABASES;"

# Check .env.docker credentials match .env
cat .env | grep DB_
cat .env.docker | grep DB_
```

### Permission errors

```bash
# Fix storage permissions
docker exec formvaksin_app chmod -R 775 storage bootstrap/cache
docker exec formvaksin_app chown -R www-data:www-data storage bootstrap/cache
```

### Application slow/timeout

```bash
# Increase PHP timeout di docker/php.ini
max_execution_time = 600

# Rebuild container
docker compose down
docker compose up -d --build
```

## 🔧 Performance Tuning

### Optimize Laravel

```bash
# Cache everything
docker exec formvaksin_app php artisan config:cache
docker exec formvaksin_app php artisan route:cache
docker exec formvaksin_app php artisan view:cache

# Optimize composer autoload
docker exec formvaksin_app composer dump-autoload --optimize
```

### Scale Containers (jika traffic tinggi)

```bash
# Scale application container
docker compose up -d --scale app=3
```

## 📞 Support

Jika ada masalah:
1. Check logs: `docker compose logs`
2. Check container status: `docker ps -a`
3. Verify .env configuration
4. Check database connection

## 📝 Notes

- **Restart Policy:** Containers akan auto-restart jika crash (restart: always)
- **Data Persistence:** Database data disimpan di Docker volume (dbdata)
- **Port Default:** 
  - App: 8000
  - Database: 3306
  - PHPMyAdmin: 8080
  
Ganti port di `.env.docker` jika ada conflict.

---

**Developed by:** RSI IT Team  
**Version:** 1.0  
**Last Updated:** October 2025
