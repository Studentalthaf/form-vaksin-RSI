# 🚨 URGENT: RANSOMWARE RECOVERY

## Status: Database Diserang Ransomware (24 Jan 2026)

Database production telah diserang ransomware. Semua data dihapus dan diganti dengan ransom note.

---

## 🔥 LANGKAH RECOVERY CEPAT

### 1. Upload File ke VPS

```bash
# Di VPS, pull perubahan terbaru
cd /var/www/form-vaksin-RSI
git pull origin main

# Atau upload manual via SCP:
# scp *.sh RECOVERY-GUIDE.md user@vps:/var/www/form-vaksin-RSI/
```

### 2. Beri Permission

```bash
chmod +x recovery-database.sh
chmod +x security-hardening.sh
chmod +x backup-database.sh
```

### 3. Jalankan Recovery

```bash
# Step 1: Recovery database
./recovery-database.sh

# Step 2: Security hardening
./security-hardening.sh
```

### 4. Setup Backup Otomatis

```bash
# Edit crontab
crontab -e

# Tambahkan (backup setiap hari jam 2 pagi):
0 2 * * * /var/www/form-vaksin-RSI/backup-database.sh >> /var/www/form-vaksin-RSI/backup.log 2>&1
```

---

## 📋 File Recovery

| File | Deskripsi |
|------|-----------|
| `recovery-database.sh` | Script untuk rebuild database dari nol |
| `security-hardening.sh` | Script untuk hardening keamanan |
| `backup-database.sh` | Script backup otomatis |
| `RECOVERY-GUIDE.md` | Panduan lengkap recovery |
| `database/seeders/VaksinSeeder.php` | Seeder untuk data vaksin |

---

## ⚠️ PERUBAHAN PENTING

### docker-compose.yml
- ✅ Port MySQL **DITUTUP** dari public (line 63-66)
- ⚠️ Setelah pull, jalankan: `docker-compose down && docker-compose up -d`

### DatabaseSeeder.php
- ✅ Ditambahkan `VaksinSeeder` untuk seed data vaksin

---

## 📖 Dokumentasi Lengkap

Baca file `RECOVERY-GUIDE.md` untuk:
- Penjelasan detail masalah
- Langkah-langkah recovery
- Security best practices
- Troubleshooting

---

## 🔒 Keamanan

**Penyebab Serangan**: Port MySQL (3306) terbuka ke public

**Solusi yang Diterapkan**:
1. ✅ Port MySQL ditutup
2. ✅ Password database akan diganti (via security-hardening.sh)
3. ✅ Firewall dikonfigurasi
4. ✅ Backup otomatis disetup

---

## ❌ Data yang Hilang

- Semua data pasien
- Semua permohonan vaksinasi
- Semua screening records

**Data harus di-input ulang setelah recovery!**

---

## 📞 Support

Jika ada masalah, baca `RECOVERY-GUIDE.md` bagian Troubleshooting.

---

**JANGAN BAYAR TEBUSAN!**
