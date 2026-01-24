# 🚨 PANDUAN RECOVERY SETELAH RANSOMWARE ATTACK

## 📋 Ringkasan Kejadian

**Tanggal**: 24 Januari 2026  
**Masalah**: Database MySQL diserang ransomware  
**Dampak**: Semua tabel dihapus, diganti dengan `RECOVER_YOUR_DATA_info`  
**Penyebab**: Port MySQL (3306) terbuka ke public internet  

---

## ⚠️ PENTING: JANGAN BAYAR TEBUSAN!

Alasan:
- ❌ Tidak ada jaminan data akan dikembalikan
- ❌ Mendorong serangan lebih lanjut
- ❌ Ilegal dan mendanai aktivitas kriminal
- ❌ Mereka kemungkinan besar tidak punya backup Anda

---

## 🔧 LANGKAH RECOVERY

### Persiapan

1. **Upload file ke VPS**
   ```bash
   cd /var/www/form-vaksin-RSI
   
   # Upload file dari local (jalankan di local):
   # scp recovery-database.sh security-hardening.sh user@your-vps:/var/www/form-vaksin-RSI/
   
   # Atau pull dari git jika sudah di-commit
   git pull origin main
   ```

2. **Beri permission execute**
   ```bash
   chmod +x recovery-database.sh
   chmod +x security-hardening.sh
   ```

---

### Step 1: Recovery Database

```bash
cd /var/www/form-vaksin-RSI
./recovery-database.sh
```

**Script ini akan:**
1. ✅ Backup ransom note sebagai bukti
2. ✅ Drop database yang ter-infeksi
3. ✅ Buat database baru
4. ✅ Jalankan semua migrations
5. ✅ Seed data awal (users, vaksin, screening questions)
6. ✅ Clear cache aplikasi

**⚠️ CATATAN**: Semua data pasien/permohonan akan HILANG (harus input ulang)

---

### Step 2: Security Hardening

```bash
cd /var/www/form-vaksin-RSI
./security-hardening.sh
```

**Script ini akan:**
1. ✅ Generate password database baru yang kuat
2. ✅ Update `.env.docker` dengan password baru
3. ✅ Setup firewall (UFW)
4. ✅ Tutup port MySQL dari public
5. ✅ Restart containers dengan konfigurasi aman

**⚠️ PENTING**: Simpan password baru yang di-generate!

---

### Step 3: Verifikasi

```bash
# Cek apakah aplikasi berjalan
curl http://localhost:8080

# Cek database
docker exec formvaksin_db mysql -uroot -p[NEW_ROOT_PASSWORD] form_vaksin -e "SHOW TABLES;"

# Cek data vaksin
docker exec formvaksin_app php artisan tinker --execute="echo \App\Models\Vaksin::count();"

# Cek port yang terbuka (pastikan 3306 TIDAK ada)
netstat -tulpn | grep 3306
```

---

## 🔒 KEAMANAN YANG SUDAH DITERAPKAN

### 1. Port MySQL Ditutup
- ❌ **SEBELUM**: Port 3306/33060 terbuka ke public
- ✅ **SETELAH**: MySQL hanya accessible dari dalam Docker network

### 2. Password Diganti
- ❌ **SEBELUM**: Password default/lemah
- ✅ **SETELAH**: Password random 25 karakter

### 3. Firewall Aktif
- ✅ Hanya port 22 (SSH), 80 (HTTP), 443 (HTTPS), 8080 (App) yang terbuka
- ✅ Semua port lain ditutup

---

## 📊 DATA YANG HILANG

### ❌ Data yang TIDAK bisa di-recover:
- Semua data pasien
- Semua permohonan vaksinasi
- Semua screening records
- Semua vaccine requests

### ✅ Data yang sudah di-restore:
- User admin/dokter (dari UserSeeder)
- Data vaksin (13 jenis vaksin)
- Screening questions
- Struktur database (migrations)

---

## 🔐 REKOMENDASI KEAMANAN TAMBAHAN

### 1. Setup Backup Otomatis

Buat cron job untuk backup database setiap hari:

```bash
# Edit crontab
crontab -e

# Tambahkan line ini (backup setiap hari jam 2 pagi):
0 2 * * * /var/www/form-vaksin-RSI/backup-database.sh
```

### 2. Install Fail2Ban

```bash
apt-get update
apt-get install fail2ban -y
systemctl enable fail2ban
systemctl start fail2ban
```

### 3. Setup SSL/HTTPS

```bash
# Install certbot
apt-get install certbot python3-certbot-nginx -y

# Generate SSL certificate
certbot --nginx -d yourdomain.com
```

### 4. Monitoring

- Setup Uptime monitoring (UptimeRobot, Pingdom)
- Setup log monitoring
- Setup disk space alerts

### 5. Regular Security Audit

```bash
# Update system
apt-get update && apt-get upgrade -y

# Cek port terbuka
nmap localhost

# Cek proses mencurigakan
ps aux | grep -v grep | grep -E "mysql|docker"
```

---

## 📝 Akses Aplikasi Setelah Recovery

### Login Admin/Dokter

Cek file `database/seeders/UserSeeder.php` untuk username dan password default.

Biasanya:
- **Email**: admin@example.com / dokter@example.com
- **Password**: Lihat di UserSeeder

**⚠️ GANTI PASSWORD SETELAH LOGIN PERTAMA!**

---

## 🆘 Troubleshooting

### Error: "Connection refused" saat akses aplikasi

```bash
# Restart containers
docker-compose down
docker-compose up -d

# Cek logs
docker logs formvaksin_app
docker logs formvaksin_db
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"

```bash
# Cek apakah DB container running
docker ps | grep formvaksin_db

# Cek DB logs
docker logs formvaksin_db

# Restart DB
docker restart formvaksin_db
```

### Error: "Table 'vaksins' doesn't exist"

```bash
# Jalankan ulang migrations
docker exec formvaksin_app php artisan migrate:fresh --force
docker exec formvaksin_app php artisan db:seed --force
```

---

## 📞 Kontak

Jika ada masalah, hubungi:
- **Developer**: [Your Contact]
- **Hosting Provider**: [Provider Support]

---

## 📚 Referensi

- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [Docker Security](https://docs.docker.com/engine/security/)
- [MySQL Security](https://dev.mysql.com/doc/refman/8.0/en/security.html)

---

**Dibuat**: 24 Januari 2026  
**Update Terakhir**: 24 Januari 2026
