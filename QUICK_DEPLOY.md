# ⚡ QUICK DEPLOYMENT COMMANDS

Cheat sheet untuk deployment cepat ke VPS.

---

## 🚀 Fresh Deployment (Dari Nol)

```bash
# 1. SSH ke VPS
ssh user@103.16.116.182

# 2. Hapus deployment lama
cd /var/www/form-vaksin-RSI
docker-compose down
docker rm -f formvaksin_app formvaksin_db 2>/dev/null || true
docker volume rm form-vaksin-rsi_dbdata 2>/dev/null || true
cd .. && rm -rf form-vaksin-RSI

# 3. Clone fresh dari GitHub
git clone https://github.com/YOUR_USERNAME/form-vaksin.git form-vaksin-RSI
cd form-vaksin-RSI

# 4. Setup .env
cp .env.production .env
nano .env  # Edit DB passwords dan reCAPTCHA keys

# 5. Build & Run
docker-compose build --no-cache
docker-compose up -d

# 6. Setup Laravel
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan storage:link

# 7. Test
curl http://localhost
# Browser: http://103.16.116.182:8080
```

---

## 🔄 Update Deployment (Pull Changes)

```bash
cd /var/www/form-vaksin-RSI
git pull origin main
docker-compose build --no-cache
docker-compose down && docker-compose up -d
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan cache:clear
```

---

## 🐛 Quick Debug Commands

```bash
# Cek status containers
docker-compose ps

# Lihat logs
docker-compose logs -f app
docker-compose logs -f db

# Masuk ke container
docker-compose exec app sh
docker-compose exec db mysql -uroot -prootpassword

# Restart containers
docker-compose restart app
docker-compose restart db

# Rebuild dari nol
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

---

## 🩺 Health Check

```bash
# Test Nginx
curl http://localhost
curl -I http://localhost

# Test PHP-FPM
docker-compose exec app ps aux | grep php-fpm

# Test Database
docker-compose exec db mysqladmin ping -h localhost -uroot -prootpassword

# Test Storage
curl http://103.16.116.182:8080/storage/test.jpg

# Test reCAPTCHA (browser)
# http://103.16.116.182:8080/permohonan/create
```

---

## 🔧 Fix Common Issues

```bash
# Fix: Container unhealthy
docker-compose restart app

# Fix: Photos 404
docker-compose exec app php artisan storage:link
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R 775 /var/www/storage

# Fix: Permission denied
docker-compose exec app chown -R www-data:www-data /var/www
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Fix: Database connection refused
# Edit .env: DB_HOST=db (bukan localhost)
docker-compose restart app

# Fix: Config cached
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
```

---

## 📝 .env Production Template

```env
APP_NAME="Form Vaksin RSI"
APP_ENV=production
APP_KEY=  # Generate dengan: php artisan key:generate
APP_DEBUG=false
APP_URL=http://103.16.116.182:8080
APP_PORT=8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=form_vaksin
DB_USERNAME=formvaksin_user
DB_PASSWORD=YOUR_STRONG_PASSWORD_HERE
DB_ROOT_PASSWORD=YOUR_STRONG_ROOT_PASSWORD_HERE

RECAPTCHA_SITE_KEY=YOUR_PRODUCTION_SITE_KEY
RECAPTCHA_SECRET_KEY=YOUR_PRODUCTION_SECRET_KEY
```

---

## 🔍 Monitoring Commands

```bash
# Resource usage
docker stats

# Disk usage
docker system df

# Container logs (last 50 lines)
docker-compose logs --tail=50

# Nginx access logs
docker-compose exec app tail -f /var/log/nginx/access.log

# Nginx error logs
docker-compose exec app tail -f /var/log/nginx/error.log

# Laravel logs
docker-compose exec app tail -f /var/www/storage/logs/laravel.log
```

---

## 🗄️ Database Management

```bash
# Backup database
docker-compose exec db mysqldump -u root -prootpassword form_vaksin > backup_$(date +%Y%m%d).sql

# Restore database
cat backup_20240101.sql | docker-compose exec -T db mysql -u root -prootpassword form_vaksin

# Access MySQL CLI
docker-compose exec db mysql -u root -prootpassword form_vaksin

# Show tables
docker-compose exec db mysql -uroot -prootpassword -e "USE form_vaksin; SHOW TABLES;"

# Drop and recreate (HATI-HATI!)
docker-compose exec app php artisan migrate:fresh --force
```

---

## 🛑 Cleanup Commands

```bash
# Stop containers
docker-compose stop

# Remove containers (keep data)
docker-compose down

# Remove containers + volumes (DELETE DATA!)
docker-compose down -v

# Clean all Docker resources
docker system prune -af --volumes

# Remove specific volume
docker volume rm form-vaksin-rsi_dbdata
```

---

## 📦 Backup & Restore

```bash
# Full backup (code + database + uploads)
cd /var/www
tar -czf backup_$(date +%Y%m%d).tar.gz \
  form-vaksin-RSI/.env \
  form-vaksin-RSI/storage/app/public

docker-compose exec db mysqldump -u root -prootpassword form_vaksin > db_backup_$(date +%Y%m%d).sql

# Restore
# 1. Extract files
tar -xzf backup_20240101.tar.gz

# 2. Restore database
cat db_backup_20240101.sql | docker-compose exec -T db mysql -u root -prootpassword form_vaksin
```

---

## ⚡ Performance Tuning

```bash
# Clear all caches
docker-compose exec app php artisan optimize:clear

# Optimize for production
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Check optimization status
docker-compose exec app php artisan optimize
```

---

**📞 Emergency Contact:**
- Documentation: `/var/www/form-vaksin-RSI/DEPLOYMENT_PRODUCTION.md`
- Logs: `docker-compose logs -f`
- Status: `docker-compose ps`
