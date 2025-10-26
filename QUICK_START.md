# 📋 QUICK START - Deploy Form Vaksin RSI ke VPS

## 🚀 Langkah Cepat (5 Menit)

### 1. Di VPS - Clone & Deploy

```bash
# Clone repository
git clone https://github.com/Studentalthaf/form-vaksin-RSI.git
cd form-vaksin-RSI

# Setup environment
cp .env.example .env
nano .env  # Edit sesuai kebutuhan

# Edit password Docker
nano .env.docker  # WAJIB ganti password!

# Deploy!
chmod +x deploy.sh
./deploy.sh
```

### 2. Akses Aplikasi

```
http://YOUR_VPS_IP:8000
```

**SELESAI!** ✅

---

## 🔑 File Penting yang Harus Diedit

### `.env` - Laravel Configuration
```env
APP_URL=http://YOUR_VPS_IP:8000
DB_HOST=db
DB_DATABASE=form_vaksin
DB_USERNAME=formvaksin_user
DB_PASSWORD=YourSecurePassword123!  # SAMA dengan .env.docker
```

### `.env.docker` - Docker Configuration
```env
APP_PORT=8000
DB_DATABASE=form_vaksin
DB_USERNAME=formvaksin_user
DB_PASSWORD=YourSecurePassword123!  # SAMA dengan .env
DB_ROOT_PASSWORD=RootPassword123!   # Password MySQL root
PMA_PORT=8080
```

⚠️ **PENTING:** Password di `.env` dan `.env.docker` HARUS SAMA!

---

## 🎛️ Perintah Utama

```bash
# Start
docker compose --env-file .env.docker up -d

# Stop
docker compose down

# Restart
docker compose restart

# Logs
docker compose logs -f

# Update Code
git pull
docker compose down
docker compose --env-file .env.docker up -d --build

# Clear Cache
docker exec formvaksin_app php artisan cache:clear
```

---

## 🔥 Fitur Production-Ready

✅ **Auto-Restart:** Container otomatis restart jika crash  
✅ **Nginx:** Web server untuk high performance  
✅ **PHP-FPM:** FastCGI Process Manager  
✅ **Supervisor:** Process manager untuk PHP-FPM + Nginx  
✅ **OPcache:** PHP accelerator untuk performance  
✅ **Health Checks:** Monitoring container health  
✅ **Persistent Data:** Database data tetap aman  
✅ **Multi-User:** Bisa diakses banyak orang bersamaan  

---

## 🛡️ Security Checklist

- [  ] Ganti semua password default
- [  ] Set `APP_DEBUG=false` di `.env`
- [  ] Setup firewall: `sudo ufw allow 8000/tcp`
- [  ] Disable PHPMyAdmin di production (comment di docker-compose.yml)
- [  ] Backup database secara berkala
- [  ] Update aplikasi secara berkala: `git pull`

---

## 📞 Troubleshooting

**Container tidak start?**
```bash
docker compose logs
docker ps -a
```

**Database error?**
```bash
# Check password cocok
cat .env | grep DB_PASSWORD
cat .env.docker | grep DB_PASSWORD

# Restart database
docker compose restart db
```

**Permission error?**
```bash
chmod -R 775 storage bootstrap/cache
```

---

**Dokumentasi Lengkap:** Lihat file `DOCKER_DEPLOYMENT.md`
