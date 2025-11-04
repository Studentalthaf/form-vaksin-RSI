# 🚀 CARA DEPLOY KE VPS - SIMPLE

## 1️⃣ Persiapan VPS (Ubuntu 20.04 / 22.04)

```bash
# SSH ke VPS
ssh root@ip-vps-anda

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh

# Install Docker Compose
apt install docker-compose -y

# Install Git
apt install git -y
```

## 2️⃣ Clone Project ke VPS

```bash
# Clone repository
git clone https://github.com/Studentalthaf/form-vaksin-RSI.git
cd form-vaksin-RSI

# Copy dan edit .env
cp .env.production .env
nano .env
```

**Edit .env:**
- Ganti `APP_URL` dengan domain/IP VPS Anda
- Ganti password database jika perlu
- Generate APP_KEY (lihat step selanjutnya)

## 3️⃣ Build dan Jalankan

```bash
# Build containers
docker-compose -f docker-compose.simple.yml build

# Jalankan containers
docker-compose -f docker-compose.simple.yml up -d

# Generate APP_KEY
docker-compose -f docker-compose.simple.yml exec app php artisan key:generate

# Jalankan migration dan seeder
docker-compose -f docker-compose.simple.yml exec app php artisan migrate:fresh --seed

# Set permission storage
docker-compose -f docker-compose.simple.yml exec app chmod -R 777 storage bootstrap/cache
```

## 4️⃣ Akses Aplikasi

Buka browser: `http://ip-vps-anda:8000`

**Login Default:**
- Admin: `admin@rsi.com` / `password123`
- Dokter: `dokter@rsi.com` / `password123`

## 5️⃣ Command Berguna

```bash
# Lihat status containers
docker-compose -f docker-compose.simple.yml ps

# Lihat logs
docker-compose -f docker-compose.simple.yml logs -f

# Restart semua containers
docker-compose -f docker-compose.simple.yml restart

# Stop semua containers
docker-compose -f docker-compose.simple.yml down

# Update code dari Git
git pull origin main
docker-compose -f docker-compose.simple.yml restart app
```

## 6️⃣ Update Aplikasi (Jika Ada Perubahan Code)

```bash
# SSH ke VPS
cd form-vaksin-RSI

# Pull perubahan terbaru
git pull origin main

# Restart container
docker-compose -f docker-compose.simple.yml restart app

# Jika ada perubahan migration
docker-compose -f docker-compose.simple.yml exec app php artisan migrate
```

## 📊 Resource Usage

- **RAM**: ~1GB total (Nginx 128MB + PHP 512MB + MySQL 512MB)
- **CPU**: 1.25 cores maksimal (sudah di-limit)
- **Disk**: ~500MB untuk Docker images

## ⚠️ Troubleshooting

**Error "Cannot connect to MySQL":**
```bash
docker-compose -f docker-compose.simple.yml restart db
docker-compose -f docker-compose.simple.yml logs db
```

**Error Permission:**
```bash
docker-compose -f docker-compose.simple.yml exec app chmod -R 777 storage bootstrap/cache
```

**Port sudah digunakan:**
Edit `.env`, ubah `APP_PORT=8000` ke port lain (misal: 8080)

## 🔒 Security (Optional tapi Penting!)

```bash
# Ubah password database di .env
# Ubah port default jika perlu
# Setup firewall
ufw allow 22
ufw allow 8000
ufw enable
```

---

**Selesai! Aplikasi sudah running di VPS** ✅
