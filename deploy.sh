#!/bin/bash

# Form Vaksin RSI - Deployment Script untuk VPS
# Jalankan script ini di VPS setelah clone repository

set -e

echo "=================================================="
echo "Form Vaksin RSI - VPS Deployment Script"
echo "=================================================="

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Check Docker installed
echo -e "\n${YELLOW}[1/10] Checking Docker...${NC}"
if ! command -v docker &> /dev/null; then
    echo -e "${RED}Docker not found! Installing Docker...${NC}"
    curl -fsSL https://get.docker.com -o get-docker.sh
    sudo sh get-docker.sh
    sudo usermod -aG docker $USER
    echo -e "${GREEN}Docker installed successfully!${NC}"
else
    echo -e "${GREEN}Docker already installed.${NC}"
fi

# 2. Check Docker Compose installed
echo -e "\n${YELLOW}[2/10] Checking Docker Compose...${NC}"
if ! docker compose version &> /dev/null; then
    echo -e "${RED}Docker Compose plugin not found! Installing...${NC}"
    sudo apt-get update
    sudo apt-get install -y docker-compose-plugin
    echo -e "${GREEN}Docker Compose installed successfully!${NC}"
else
    echo -e "${GREEN}Docker Compose already installed.${NC}"
fi

# 3. Create .env file
echo -e "\n${YELLOW}[3/10] Setting up environment file...${NC}"
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
        echo -e "${GREEN}.env file created from .env.example${NC}"
    else
        echo -e "${RED}ERROR: .env.example not found!${NC}"
        exit 1
    fi
else
    echo -e "${GREEN}.env file already exists.${NC}"
fi

# 4. Create .env.docker if not exists
echo -e "\n${YELLOW}[4/10] Setting up Docker environment...${NC}"
if [ ! -f .env.docker ]; then
    cat > .env.docker << 'EOF'
# Docker Environment Variables
APP_PORT=8000
DB_DATABASE=form_vaksin
DB_USERNAME=formvaksin_user
DB_PASSWORD=SecurePassword123!
DB_ROOT_PASSWORD=RootSecurePassword123!
DB_PORT=3306
PMA_PORT=8080
EOF
    echo -e "${YELLOW}⚠️  Please edit .env.docker and set secure passwords!${NC}"
else
    echo -e "${GREEN}.env.docker already exists.${NC}"
fi

# 5. Set proper permissions
echo -e "\n${YELLOW}[5/10] Setting file permissions...${NC}"
sudo chown -R $USER:$USER .
chmod -R 755 storage bootstrap/cache
echo -e "${GREEN}Permissions set successfully.${NC}"

# 6. Stop existing containers
echo -e "\n${YELLOW}[6/10] Stopping existing containers...${NC}"
docker compose down 2>/dev/null || true

# 7. Build Docker images
echo -e "\n${YELLOW}[7/10] Building Docker images...${NC}"
docker compose --env-file .env.docker build --no-cache

# 8. Start containers
echo -e "\n${YELLOW}[8/10] Starting containers...${NC}"
docker compose --env-file .env.docker up -d

# 9. Wait for database to be ready
echo -e "\n${YELLOW}[9/10] Waiting for database to be ready...${NC}"
echo "This may take up to 60 seconds..."
sleep 10

for i in {1..30}; do
    if docker exec formvaksin_db mysqladmin ping -h localhost -uroot -p$(grep DB_ROOT_PASSWORD .env.docker | cut -d '=' -f2) &>/dev/null; then
        echo -e "${GREEN}Database is ready!${NC}"
        break
    fi
    echo -n "."
    sleep 2
done

# 10. Run Laravel setup
echo -e "\n${YELLOW}[10/10] Setting up Laravel...${NC}"

echo "- Generating application key..."
docker exec formvaksin_app php artisan key:generate --force

echo "- Running database migrations..."
docker exec formvaksin_app php artisan migrate --force

echo "- Clearing and caching config..."
docker exec formvaksin_app php artisan config:clear
docker exec formvaksin_app php artisan cache:clear
docker exec formvaksin_app php artisan route:clear
docker exec formvaksin_app php artisan view:clear

echo "- Optimizing application..."
docker exec formvaksin_app php artisan config:cache
docker exec formvaksin_app php artisan route:cache
docker exec formvaksin_app php artisan view:cache

# Show container status
echo -e "\n${GREEN}=================================================="
echo "Deployment Completed Successfully!"
echo "==================================================${NC}"
echo ""
echo -e "${YELLOW}Running Containers:${NC}"
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"

echo ""
echo -e "${GREEN}Access your application:${NC}"
echo "- Application: http://YOUR_VPS_IP:$(grep APP_PORT .env.docker | cut -d '=' -f2)"
echo "- PHPMyAdmin:  http://YOUR_VPS_IP:$(grep PMA_PORT .env.docker | cut -d '=' -f2)"
echo ""
echo -e "${YELLOW}Useful Commands:${NC}"
echo "- View logs:         docker compose logs -f"
echo "- Stop containers:   docker compose down"
echo "- Restart:           docker compose restart"
echo "- Enter container:   docker exec -it formvaksin_app bash"
echo ""
echo -e "${RED}IMPORTANT:${NC}"
echo "1. Update .env file with your production settings"
echo "2. Update .env.docker with secure passwords"
echo "3. Configure firewall to allow ports $(grep APP_PORT .env.docker | cut -d '=' -f2) and $(grep PMA_PORT .env.docker | cut -d '=' -f2)"
echo "4. Consider disabling PHPMyAdmin in production"
echo ""
