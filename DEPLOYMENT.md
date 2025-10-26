# Docker VPS Deployment Guide

Panduan lengkap untuk deploy Laravel project ke VPS menggunakan Docker dan GitHub Actions.

## Prerequisites

### 1. VPS Requirements
- Ubuntu 20.04 atau lebih baru
- Minimal 1GB RAM
- Docker & Docker Compose terinstall
- SSH access

### 2. GitHub Secrets Setup
Tambahkan secrets berikut di GitHub Repository Settings → Secrets and variables → Actions:

#### VPS Connection
- `VPS_HOST`: IP address atau domain VPS (contoh: `192.168.1.100` atau `myapp.com`)
- `VPS_USER`: SSH username (contoh: `root` atau `ubuntu`)
- `VPS_SSH_KEY`: Private SSH key untuk akses VPS
- `VPS_PATH`: Path di VPS untuk clone repository (contoh: `/var/www`)
- `LARAVEL_DIR`: Nama folder project (contoh: `form-vaksin`)

#### GitHub
- `GIT_TOKEN`: Personal Access Token dari GitHub untuk clone private repo

#### Application
- `APP_PORT`: Port untuk akses aplikasi (contoh: `8080`)
- `LARAVEL_ENV`: Isi file `.env` untuk production (lihat contoh di bawah)

#### Database
- `DB_ROOT_PASSWORD`: Password untuk MySQL root user
- `DB_HOST`: Hostname database (gunakan: `db`)
- `DB_NAME`: Nama database (contoh: `form_vaksin`)
- `DB_USERNAME`: Username database (contoh: `laravel_user`)
- `DB_PASSWORD`: Password database user

## LARAVEL_ENV Example

```env
APP_NAME="Form Vaksin"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://your-domain.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=3306
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

## VPS Setup

### 1. Connect to VPS
```bash
ssh user@your-vps-ip
```

### 2. Install Docker & Docker Compose
```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verify installation
docker --version
docker-compose --version
```

### 3. Setup SSH Key for GitHub Actions
```bash
# Generate SSH key jika belum ada
ssh-keygen -t rsa -b 4096 -C "github-actions"

# Copy private key ke GitHub Secrets (VPS_SSH_KEY)
cat ~/.ssh/id_rsa

# Add public key to authorized_keys
cat ~/.ssh/id_rsa.pub >> ~/.ssh/authorized_keys
```

## Deployment Process

### 1. Push to GitHub
```bash
git add .
git commit -m "Setup Docker deployment"
git push origin main
```

### 2. GitHub Actions akan otomatis:
- Connect ke VPS via SSH
- Install Docker & Docker Compose jika belum ada
- Clone/pull repository
- Create `.env` dan `.env.deploy` files
- Build Docker images
- Start containers (app + database)
- Install dependencies: `composer install`
- Generate app key: `php artisan key:generate`
- Run migrations: `php artisan migrate --force`

### 3. Akses Aplikasi
Buka browser dan akses: `http://your-vps-ip:8080`
(Ganti 8080 dengan nilai `APP_PORT` yang Anda set)

## Docker Commands

### View Containers
```bash
docker ps
```

### View Logs
```bash
# Application logs
docker logs laravel_app

# Database logs
docker logs laravel_mysql

# Follow logs (real-time)
docker logs -f laravel_app
```

### Access Container Shell
```bash
# Laravel app container
docker exec -it laravel_app bash

# MySQL container
docker exec -it laravel_mysql mysql -u root -p
```

### Restart Containers
```bash
cd /var/www/form-vaksin
docker-compose restart
```

### Stop Containers
```bash
docker-compose down
```

### Rebuild Containers
```bash
docker-compose down
docker-compose up -d --build
```

### Clear Cache
```bash
docker exec -it laravel_app php artisan cache:clear
docker exec -it laravel_app php artisan config:clear
docker exec -it laravel_app php artisan route:clear
docker exec -it laravel_app php artisan view:clear
```

## Database Management

### Backup Database
```bash
docker exec laravel_mysql mysqldump -u root -p${DB_ROOT_PASSWORD} ${DB_NAME} > backup-$(date +%Y%m%d).sql
```

### Restore Database
```bash
docker exec -i laravel_mysql mysql -u root -p${DB_ROOT_PASSWORD} ${DB_NAME} < backup.sql
```

### Access MySQL CLI
```bash
docker exec -it laravel_mysql mysql -u root -p
```

## Troubleshooting

### Container tidak start
```bash
# Check logs
docker logs laravel_app
docker logs laravel_mysql

# Check container status
docker ps -a

# Restart containers
docker-compose restart
```

### Permission errors
```bash
docker exec -it laravel_app chown -R www-data:www-data /var/www/html/storage
docker exec -it laravel_app chmod -R 755 /var/www/html/storage
docker exec -it laravel_app chmod -R 755 /var/www/html/bootstrap/cache
```

### Database connection error
```bash
# Verify environment variables
docker exec -it laravel_app cat .env

# Check MySQL is running
docker exec -it laravel_mysql mysql -u root -p -e "SHOW DATABASES;"
```

### Port already in use
```bash
# Find process using port
sudo lsof -i :8080

# Kill process
sudo kill -9 <PID>

# Or change APP_PORT in GitHub Secrets
```

## File Structure

```
project/
├── docker/
│   ├── nginx/
│   │   └── default.conf          # Nginx server configuration
│   ├── supervisor/
│   │   └── supervisord.conf      # Process manager config
│   ├── php/
│   │   └── local.ini             # PHP settings
│   └── mysql/
│       └── my.cnf                # MySQL configuration
├── Dockerfile                     # Docker image definition
├── docker-compose.yml            # Multi-container orchestration
├── .dockerignore                 # Files to exclude from image
└── .github/
    └── workflows/
        └── github-action.yml     # CI/CD pipeline
```

## Security Best Practices

1. **Firewall**: Hanya buka port yang diperlukan
   ```bash
   sudo ufw allow 22/tcp    # SSH
   sudo ufw allow 80/tcp    # HTTP
   sudo ufw allow 443/tcp   # HTTPS
   sudo ufw enable
   ```

2. **SSL/TLS**: Setup HTTPS dengan Let's Encrypt
   ```bash
   # Install certbot
   sudo apt install certbot
   
   # Get certificate
   sudo certbot certonly --standalone -d your-domain.com
   ```

3. **Environment Variables**: Jangan commit `.env` ke Git

4. **Database**: Gunakan strong password untuk MySQL

5. **Regular Updates**: Update Docker images secara berkala
   ```bash
   docker-compose pull
   docker-compose up -d
   ```

## Monitoring

### Container Health
```bash
docker stats
```

### Disk Usage
```bash
docker system df
```

### Clean Up
```bash
# Remove unused images
docker image prune -a

# Remove unused volumes
docker volume prune

# Complete cleanup
docker system prune -a --volumes
```

## Support

Jika ada masalah:
1. Check logs: `docker logs laravel_app`
2. Check GitHub Actions workflow run
3. Verify all secrets are set correctly
4. Test database connection in container
5. Check file permissions

## Production Optimization

### Enable OPcache
Edit `docker/php/local.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### Optimize Composer Autoload
```bash
docker exec -it laravel_app composer install --optimize-autoloader --no-dev
```

### Cache Configuration
```bash
docker exec -it laravel_app php artisan config:cache
docker exec -it laravel_app php artisan route:cache
docker exec -it laravel_app php artisan view:cache
```

---

**Created**: 2025
**Author**: System Administrator
**Version**: 1.0
