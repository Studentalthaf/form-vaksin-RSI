#!/bin/bash

# ============================================
# SCRIPT RECOVERY DATABASE SETELAH RANSOMWARE
# ============================================

echo "🚨 DATABASE RECOVERY SCRIPT"
echo "============================"
echo ""

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Backup ransom note sebagai bukti
echo -e "${YELLOW}[1/6] Backup ransom note sebagai bukti...${NC}"
docker exec formvaksin_db mysqldump -uroot -prootpassword form_vaksin RECOVER_YOUR_DATA_info > /var/www/form-vaksin-RSI/ransom_evidence_$(date +%Y%m%d_%H%M%S).sql
echo -e "${GREEN}✅ Ransom note disimpan${NC}"
echo ""

# 2. Drop database yang ter-infeksi
echo -e "${YELLOW}[2/6] Menghapus database yang ter-infeksi...${NC}"
docker exec formvaksin_db mysql -uroot -prootpassword -e "DROP DATABASE IF EXISTS form_vaksin;"
echo -e "${GREEN}✅ Database lama dihapus${NC}"
echo ""

# 3. Buat database baru
echo -e "${YELLOW}[3/6] Membuat database baru...${NC}"
docker exec formvaksin_db mysql -uroot -prootpassword -e "CREATE DATABASE form_vaksin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
docker exec formvaksin_db mysql -uroot -prootpassword -e "GRANT ALL PRIVILEGES ON form_vaksin.* TO 'formvaksin_user'@'%';"
docker exec formvaksin_db mysql -uroot -prootpassword -e "FLUSH PRIVILEGES;"
echo -e "${GREEN}✅ Database baru dibuat${NC}"
echo ""

# 4. Jalankan migrations
echo -e "${YELLOW}[4/6] Menjalankan migrations...${NC}"
docker exec formvaksin_app php artisan migrate:fresh --force
echo -e "${GREEN}✅ Migrations selesai${NC}"
echo ""

# 5. Seed data awal
echo -e "${YELLOW}[5/6] Mengisi data awal (users, vaksin, screening questions)...${NC}"
docker exec formvaksin_app php artisan db:seed --force
echo -e "${GREEN}✅ Data awal berhasil di-seed${NC}"
echo ""

# 6. Clear cache
echo -e "${YELLOW}[6/6] Clear cache aplikasi...${NC}"
docker exec formvaksin_app php artisan config:clear
docker exec formvaksin_app php artisan cache:clear
docker exec formvaksin_app php artisan route:clear
docker exec formvaksin_app php artisan view:clear
echo -e "${GREEN}✅ Cache cleared${NC}"
echo ""

# Verifikasi
echo -e "${YELLOW}Verifikasi database...${NC}"
echo "Tabel yang ada:"
docker exec formvaksin_db mysql -uroot -prootpassword form_vaksin -e "SHOW TABLES;"
echo ""
echo "Jumlah vaksin:"
docker exec formvaksin_app php artisan tinker --execute="echo 'Total vaksin: ' . \App\Models\Vaksin::count() . PHP_EOL;"
echo ""
echo "Jumlah users:"
docker exec formvaksin_app php artisan tinker --execute="echo 'Total users: ' . \App\Models\User::count() . PHP_EOL;"
echo ""

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ DATABASE RECOVERY SELESAI!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}⚠️  CATATAN PENTING:${NC}"
echo "1. Semua data pasien/permohonan HILANG (harus input ulang)"
echo "2. User admin/dokter sudah dibuat ulang (cek UserSeeder untuk password)"
echo "3. Data vaksin dan screening questions sudah tersedia"
echo "4. Port MySQL sudah ditutup untuk keamanan"
echo ""
echo -e "${RED}🔒 LANGKAH SELANJUTNYA:${NC}"
echo "1. Restart container: docker-compose down && docker-compose up -d"
echo "2. Ganti password database di .env.docker"
echo "3. Setup firewall untuk block port yang tidak perlu"
echo "4. Setup backup otomatis database"
echo ""
