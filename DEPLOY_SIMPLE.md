# Docker Deployment - SIMPLE VERSION

## Setup VPS (Manual)

### 1. Clone Project
```bash
cd /usr/src/www
git clone https://github.com/Studentalthaf/form-vaksin-RSI.git
cd form-vaksin-RSI
```

### 2. Copy Environment
```bash
cp .env.example .env
cp .env.docker.example .env.docker
```

Edit `.env.docker`:
```env
APP_PORT=8000
DB_DATABASE=form_vaksin
DB_USERNAME=formvaksin_user
DB_PASSWORD=GANTI_PASSWORD_INI
DB_ROOT_PASSWORD=GANTI_PASSWORD_INI
DB_PORT=3309
```

### 3. Build & Start
```bash
docker-compose -f docker-compose.simple.yml up -d --build
```

### 4. Setup Laravel (Manual)
```bash
# Install dependencies
docker exec formvaksin_app composer install --no-dev --no-scripts --no-interaction

# Generate key
docker exec formvaksin_app php artisan key:generate --force

# Run migrations
docker exec formvaksin_app php artisan migrate --force

# Set permissions
docker exec formvaksin_app chown -R www-data:www-data /var/www/storage
```

### 5. Akses Aplikasi
```
http://YOUR_VPS_IP:8000
```

## Commands

### Start
```bash
docker-compose -f docker-compose.simple.yml up -d
```

### Stop
```bash
docker-compose -f docker-compose.simple.yml down
```

### Logs
```bash
docker-compose -f docker-compose.simple.yml logs -f
```

### Update
```bash
git pull origin main
docker-compose -f docker-compose.simple.yml down
docker-compose -f docker-compose.simple.yml up -d --build
docker exec formvaksin_app composer install --no-dev --no-scripts
docker exec formvaksin_app php artisan migrate --force
docker exec formvaksin_app php artisan config:cache
```

## Cleanup Disk

```bash
# Hapus container lama
docker system prune -a -f

# Hapus logs besar
sudo find /var/lib/docker/containers/ -name "*-json.log" -exec truncate -s 0 {} \;

# Cek disk
df -h
```

## Resource Limits

- Nginx: 128MB RAM, 0.25 CPU
- PHP-FPM: 384MB RAM, 0.5 CPU
- MySQL: 384MB RAM, 0.5 CPU
- **Total: ~1GB RAM, 1.25 CPU**

Cocok untuk VPS 2GB RAM / 2 vCPU.
