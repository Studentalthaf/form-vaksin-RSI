#!/bin/bash
# Script Deploy Production untuk Form Vaksin
# Pastikan jalankan sebagai root atau user yang punya akses docker

set -e

echo "=========================================="
echo "🚀 DEPLOY PRODUCTION - FORM VAKSIN"
echo "=========================================="

# Ganti dengan path project Anda di VPS
PROJECT_DIR="/path/to/form-vaksin"
CONTAINER_NAME="formvaksin_app"

# Masuk ke direktori project
cd "$PROJECT_DIR" || exit 1

echo ""
echo "1️⃣ Pull dari GitHub..."
git fetch origin
git pull origin main

echo ""
echo "2️⃣ Stop containers..."
docker compose down

echo ""
echo "3️⃣ Rebuild containers (PENTING untuk update kode)..."
docker compose build --no-cache app

echo ""
echo "4️⃣ Start containers..."
docker compose up -d

echo ""
echo "5️⃣ Menunggu container siap..."
sleep 15

echo ""
echo "6️⃣ Install/Update composer dependencies..."
docker exec "$CONTAINER_NAME" composer install --optimize-autoloader --no-dev --no-interaction

echo ""
echo "7️⃣ Run database migrations..."
docker exec "$CONTAINER_NAME" php artisan migrate --force

echo ""
echo "8️⃣ CLEAR SEMUA CACHE (CRITICAL!)..."
docker exec "$CONTAINER_NAME" php artisan view:clear
docker exec "$CONTAINER_NAME" php artisan config:clear
docker exec "$CONTAINER_NAME" php artisan cache:clear
docker exec "$CONTAINER_NAME" php artisan route:clear

echo ""
echo "9️⃣ Clear Opcache PHP (PENTING untuk update kode)..."
docker exec "$CONTAINER_NAME" php -r "if(function_exists('opcache_reset')){opcache_reset();echo '✅ Opcache cleared';}else{echo '⚠️ Opcache not available';}"

echo ""
echo "🔟 Optimize production..."
docker exec "$CONTAINER_NAME" php artisan config:cache
docker exec "$CONTAINER_NAME" php artisan route:cache
docker exec "$CONTAINER_NAME" php artisan view:cache

echo ""
echo "1️⃣1️⃣ Set permissions..."
docker exec "$CONTAINER_NAME" chmod -R 775 storage bootstrap/cache
docker exec "$CONTAINER_NAME" chown -R www-data:www-data storage bootstrap/cache

echo ""
echo "=========================================="
echo "✅ DEPLOYMENT SELESAI!"
echo "=========================================="
echo ""
echo "📋 Verifikasi:"
docker ps
echo ""
echo "📝 Container logs (5 baris terakhir):"
docker logs "$CONTAINER_NAME" --tail 5
echo ""
echo "🌐 Application URL:"
echo "http://$(hostname -I | awk '{print $1}'):${APP_PORT:-8000}"
echo ""

