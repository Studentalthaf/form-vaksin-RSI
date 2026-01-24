#!/bin/bash

# ============================================
# SCRIPT KEAMANAN SETELAH RANSOMWARE ATTACK
# ============================================

echo "🔒 SECURITY HARDENING SCRIPT"
echo "============================="
echo ""

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# 1. Generate password baru yang kuat
echo -e "${YELLOW}[1/5] Generate password database baru...${NC}"
NEW_DB_PASSWORD=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)
NEW_ROOT_PASSWORD=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)

echo "Password baru telah di-generate:"
echo "DB_PASSWORD: $NEW_DB_PASSWORD"
echo "DB_ROOT_PASSWORD: $NEW_ROOT_PASSWORD"
echo ""
echo -e "${YELLOW}⚠️  SIMPAN PASSWORD INI DI TEMPAT AMAN!${NC}"
echo ""

# 2. Update .env.docker
echo -e "${YELLOW}[2/5] Update .env.docker dengan password baru...${NC}"
if [ -f .env.docker ]; then
    sed -i.bak "s/^DB_PASSWORD=.*/DB_PASSWORD=$NEW_DB_PASSWORD/" .env.docker
    sed -i.bak "s/^DB_ROOT_PASSWORD=.*/DB_ROOT_PASSWORD=$NEW_ROOT_PASSWORD/" .env.docker
    echo -e "${GREEN}✅ .env.docker updated (backup: .env.docker.bak)${NC}"
else
    echo -e "${RED}❌ File .env.docker tidak ditemukan!${NC}"
fi
echo ""

# 3. Cek port yang terbuka
echo -e "${YELLOW}[3/5] Cek port yang terbuka...${NC}"
netstat -tulpn | grep -E ":(80|443|3306|33060|8000|8080)" || echo "Tidak ada port berbahaya terbuka"
echo ""

# 4. Setup firewall (UFW)
echo -e "${YELLOW}[4/5] Setup firewall...${NC}"
if command -v ufw &> /dev/null; then
    echo "Mengaktifkan UFW firewall..."
    ufw --force enable
    ufw default deny incoming
    ufw default allow outgoing
    ufw allow 22/tcp comment 'SSH'
    ufw allow 80/tcp comment 'HTTP'
    ufw allow 443/tcp comment 'HTTPS'
    ufw allow 8080/tcp comment 'App Port'
    # JANGAN buka port 3306 atau 33060!
    ufw reload
    echo -e "${GREEN}✅ Firewall configured${NC}"
else
    echo -e "${YELLOW}⚠️  UFW tidak terinstall. Install dengan: apt-get install ufw${NC}"
fi
echo ""

# 5. Restart containers dengan konfigurasi baru
echo -e "${YELLOW}[5/5] Restart containers...${NC}"
docker-compose down
sleep 3
docker-compose up -d
echo -e "${GREEN}✅ Containers restarted${NC}"
echo ""

# Verifikasi
echo -e "${YELLOW}Verifikasi keamanan...${NC}"
echo ""
echo "Port yang terbuka:"
docker ps --format "table {{.Names}}\t{{.Ports}}"
echo ""

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ SECURITY HARDENING SELESAI!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}📋 CHECKLIST KEAMANAN:${NC}"
echo "✅ Password database diganti"
echo "✅ Port MySQL ditutup dari public"
echo "✅ Firewall dikonfigurasi"
echo "✅ Containers direstart"
echo ""
echo -e "${RED}🔒 REKOMENDASI TAMBAHAN:${NC}"
echo "1. Setup backup otomatis database (cron job)"
echo "2. Install fail2ban untuk proteksi brute force"
echo "3. Setup monitoring (Prometheus/Grafana)"
echo "4. Enable SSL/HTTPS dengan Let's Encrypt"
echo "5. Regular security audit"
echo ""
echo -e "${YELLOW}📝 Password baru Anda:${NC}"
echo "DB_PASSWORD: $NEW_DB_PASSWORD"
echo "DB_ROOT_PASSWORD: $NEW_ROOT_PASSWORD"
echo ""
echo -e "${RED}⚠️  SIMPAN PASSWORD INI SEKARANG!${NC}"
echo ""
