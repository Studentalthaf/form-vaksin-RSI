#!/bin/bash

# Script untuk debug dan fix database container issue

echo "🔍 Debugging Database Container..."
echo "=================================="

# 1. Stop semua container
echo ""
echo "1. Stopping all containers..."
docker compose down

# 2. Cek port conflict
echo ""
echo "2. Checking port conflicts..."
echo "Port 3306 usage:"
sudo lsof -i :3306 || echo "Port 3306 is free"
echo ""
echo "Port 3307 usage:"
sudo lsof -i :3307 || echo "Port 3307 is free"

# 3. Remove old volume (HATI-HATI: Data lama akan hilang!)
echo ""
echo "3. Do you want to remove old database volume? (y/n)"
echo "   WARNING: This will DELETE all existing data!"
read -p "   Your choice: " remove_volume

if [ "$remove_volume" = "y" ]; then
    echo "   Removing old volume..."
    docker volume rm form-vaksin-rsi_dbdata 2>/dev/null || true
    echo "   Volume removed!"
else
    echo "   Keeping existing volume..."
fi

# 4. Clean up
echo ""
echo "4. Cleaning up..."
docker container prune -f
docker network prune -f

# 5. Check .env.docker file
echo ""
echo "5. Checking .env.docker configuration..."
if [ -f .env.docker ]; then
    echo "   .env.docker found!"
    cat .env.docker
else
    echo "   ERROR: .env.docker not found!"
    echo "   Creating default .env.docker..."
    cat > .env.docker << 'EOF'
APP_PORT=8000
DB_DATABASE=form_vaksin
DB_USERNAME=formvaksin_user
DB_PASSWORD=formvaksin123
DB_ROOT_PASSWORD=rootpassword123
DB_PORT=3307
PMA_PORT=8081
EOF
    echo "   .env.docker created with default values"
fi

# 6. Build database container only
echo ""
echo "6. Testing database container..."
docker compose --env-file .env.docker up -d db

echo ""
echo "   Waiting for database to start..."
sleep 10

echo ""
echo "   Checking database logs..."
docker logs formvaksin_db --tail 50

echo ""
echo "   Checking database health..."
docker ps -a --format "table {{.Names}}\t{{.Status}}"

# 7. Test database connection
echo ""
echo "7. Testing database connection..."
sleep 5
docker exec formvaksin_db mysqladmin ping -h localhost -uroot -p$(grep DB_ROOT_PASSWORD .env.docker | cut -d'=' -f2) 2>&1

if [ $? -eq 0 ]; then
    echo "   ✅ Database is healthy!"
    
    # Start app container
    echo ""
    echo "8. Starting app container..."
    docker compose --env-file .env.docker up -d app
    
    echo ""
    echo "   Waiting for app to start..."
    sleep 5
    
    echo ""
    echo "   Final status:"
    docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
    
    echo ""
    echo "✅ Deployment successful!"
    echo ""
    echo "Access your application at: http://$(hostname -I | awk '{print $1}'):8000"
else
    echo "   ❌ Database failed to start!"
    echo ""
    echo "   Please check the logs above for errors."
    echo "   Common issues:"
    echo "   - Port 3306 or 3307 already in use"
    echo "   - Corrupted database volume"
    echo "   - Wrong password in .env.docker"
    echo ""
    echo "   To force clean restart, run:"
    echo "   docker compose down -v"
    echo "   rm -rf /var/lib/docker/volumes/form-vaksin-rsi_dbdata"
fi
