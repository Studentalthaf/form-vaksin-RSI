#!/bin/bash

# ============================================
# SCRIPT BACKUP DATABASE OTOMATIS
# ============================================

# Konfigurasi
BACKUP_DIR="/var/www/form-vaksin-RSI/backups"
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="form_vaksin_backup_$DATE.sql"
RETENTION_DAYS=30  # Simpan backup selama 30 hari

# Warna
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${YELLOW}🔄 Starting database backup...${NC}"

# Buat folder backup jika belum ada
mkdir -p $BACKUP_DIR

# Backup database
echo -e "${YELLOW}Backing up database...${NC}"
docker exec formvaksin_db mysqldump -uroot -prootpassword \
    --single-transaction \
    --routines \
    --triggers \
    --events \
    form_vaksin > "$BACKUP_DIR/$BACKUP_FILE"

# Cek apakah backup berhasil
if [ $? -eq 0 ]; then
    # Compress backup
    echo -e "${YELLOW}Compressing backup...${NC}"
    gzip "$BACKUP_DIR/$BACKUP_FILE"
    
    BACKUP_SIZE=$(du -h "$BACKUP_DIR/$BACKUP_FILE.gz" | cut -f1)
    echo -e "${GREEN}✅ Backup berhasil: $BACKUP_FILE.gz ($BACKUP_SIZE)${NC}"
    
    # Hapus backup lama (lebih dari RETENTION_DAYS hari)
    echo -e "${YELLOW}Cleaning old backups (older than $RETENTION_DAYS days)...${NC}"
    find $BACKUP_DIR -name "*.sql.gz" -type f -mtime +$RETENTION_DAYS -delete
    
    # Hitung jumlah backup yang tersisa
    BACKUP_COUNT=$(ls -1 $BACKUP_DIR/*.sql.gz 2>/dev/null | wc -l)
    echo -e "${GREEN}✅ Total backups: $BACKUP_COUNT${NC}"
    
    # Optional: Upload ke cloud storage (uncomment jika perlu)
    # echo -e "${YELLOW}Uploading to cloud storage...${NC}"
    # aws s3 cp "$BACKUP_DIR/$BACKUP_FILE.gz" s3://your-bucket/backups/
    # rclone copy "$BACKUP_DIR/$BACKUP_FILE.gz" remote:backups/
    
else
    echo -e "${RED}❌ Backup GAGAL!${NC}"
    exit 1
fi

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ BACKUP SELESAI!${NC}"
echo -e "${GREEN}========================================${NC}"
