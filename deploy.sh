#!/bin/bash

# Server Deployment Script for Kibo Application
# Run this script on the server after pushing code

set -e

echo "🚀 Starting deployment process..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Detect docker-compose command (v1 or v2)
if command -v docker-compose &> /dev/null; then
    DOCKER_COMPOSE="docker-compose"
elif docker compose version &> /dev/null 2>&1; then
    DOCKER_COMPOSE="docker compose"
else
    echo -e "${RED}❌ Docker Compose is not installed or not found in PATH${NC}"
    echo -e "${YELLOW}Please install Docker Compose or ensure it's in your PATH${NC}"
    echo -e "${YELLOW}For Docker Compose v2, use: docker compose${NC}"
    echo -e "${YELLOW}For Docker Compose v1, use: docker-compose${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Using: $DOCKER_COMPOSE${NC}"

# Check if Docker daemon is running
if ! docker info &> /dev/null; then
    echo -e "${RED}❌ Docker daemon is not running or you don't have permission to access it${NC}"
    echo ""
    echo -e "${YELLOW}Try one of the following:${NC}"
    echo "  1. Start Docker daemon: sudo systemctl start docker"
    echo "  2. Enable Docker to start on boot: sudo systemctl enable docker"
    echo "  3. Add your user to docker group (recommended):"
    echo "     sudo usermod -aG docker $USER"
    echo "     Then log out and log back in"
    echo "  4. Or run with sudo: sudo $0"
    echo ""
    echo -e "${YELLOW}Checking Docker service status...${NC}"
    sudo systemctl status docker || echo -e "${YELLOW}Docker service not found. Please install Docker first.${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Docker daemon is running${NC}"

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠️  .env file not found. Creating from .env.example...${NC}"
    if [ -f .env.example ]; then
        cp .env.example .env
        echo -e "${GREEN}✅ Created .env file${NC}"
    else
        echo -e "${YELLOW}⚠️  .env.example not found. Creating basic .env file...${NC}"
        touch .env
        echo "APP_NAME=Kibo" >> .env
        echo "APP_ENV=production" >> .env
        echo "APP_KEY=" >> .env
        echo "APP_DEBUG=false" >> .env
        echo "APP_URL=http://40.127.10.196:8084" >> .env
        echo "" >> .env
        echo "DB_CONNECTION=mysql" >> .env
        echo "DB_HOST=host.docker.internal" >> .env
        echo "DB_PORT=3306" >> .env
        echo "DB_DATABASE=kiboV2" >> .env
        echo "DB_USERNAME=Kiboauto_2025_admin" >> .env
        echo "DB_PASSWORD=kiboAuto_2025" >> .env
        echo "" >> .env
        echo "REDIS_HOST=redis" >> .env
        echo "REDIS_PASSWORD=null" >> .env
        echo "REDIS_PORT=6379" >> .env
    fi
fi

# Update .env with production database settings
echo "📝 Updating .env with production database configuration..."
# Update or add database configuration (external MySQL)
if grep -q "^DB_HOST=" .env; then
    sed -i.bak 's/^DB_HOST=.*/DB_HOST=host.docker.internal/' .env 2>/dev/null || sed -i '' 's/^DB_HOST=.*/DB_HOST=host.docker.internal/' .env
else
    echo "DB_HOST=host.docker.internal" >> .env
fi

if grep -q "^DB_CONNECTION=" .env; then
    sed -i.bak 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env 2>/dev/null || sed -i '' 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
else
    echo "DB_CONNECTION=mysql" >> .env
fi

if grep -q "^DB_DATABASE=" .env; then
    sed -i.bak 's/^DB_DATABASE=.*/DB_DATABASE=kiboV2/' .env 2>/dev/null || sed -i '' 's/^DB_DATABASE=.*/DB_DATABASE=kiboV2/' .env
else
    echo "DB_DATABASE=kiboV2" >> .env
fi

if grep -q "^DB_USERNAME=" .env; then
    sed -i.bak 's/^DB_USERNAME=.*/DB_USERNAME=Kiboauto_2025_admin/' .env 2>/dev/null || sed -i '' 's/^DB_USERNAME=.*/DB_USERNAME=Kiboauto_2025_admin/' .env
else
    echo "DB_USERNAME=Kiboauto_2025_admin" >> .env
fi

if grep -q "^DB_PASSWORD=" .env; then
    sed -i.bak 's/^DB_PASSWORD=.*/DB_PASSWORD=kiboAuto_2025/' .env 2>/dev/null || sed -i '' 's/^DB_PASSWORD=.*/DB_PASSWORD=kiboAuto_2025/' .env
else
    echo "DB_PASSWORD=kiboAuto_2025" >> .env
fi

if grep -q "^DB_PORT=" .env; then
    sed -i.bak 's/^DB_PORT=.*/DB_PORT=3306/' .env 2>/dev/null || sed -i '' 's/^DB_PORT=.*/DB_PORT=3306/' .env
else
    echo "DB_PORT=3306" >> .env
fi

if grep -q "^REDIS_HOST=" .env; then
    sed -i.bak 's/^REDIS_HOST=.*/REDIS_HOST=redis/' .env 2>/dev/null || sed -i '' 's/^REDIS_HOST=.*/REDIS_HOST=redis/' .env
else
    echo "REDIS_HOST=redis" >> .env
fi

if grep -q "^APP_URL=" .env; then
    sed -i.bak 's|^APP_URL=.*|APP_URL=http://40.127.10.196:8084|' .env 2>/dev/null || sed -i '' 's|^APP_URL=.*|APP_URL=http://40.127.10.196:8084|' .env
else
    echo "APP_URL=http://40.127.10.196:8084" >> .env
fi

if grep -q "^APP_ENV=" .env; then
    sed -i.bak 's/^APP_ENV=.*/APP_ENV=production/' .env 2>/dev/null || sed -i '' 's/^APP_ENV=.*/APP_ENV=production/' .env
else
    echo "APP_ENV=production" >> .env
fi

if grep -q "^APP_DEBUG=" .env; then
    sed -i.bak 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env 2>/dev/null || sed -i '' 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env
else
    echo "APP_DEBUG=false" >> .env
fi

# Remove backup file if created
rm -f .env.bak

echo -e "${GREEN}✅ Updated .env file${NC}"

# Stop existing containers
echo "🛑 Stopping existing containers..."
$DOCKER_COMPOSE down

# Build and start containers
echo "🏗️  Building and starting Docker containers..."
$DOCKER_COMPOSE up -d --build

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 5

# Check if containers are running
if ! $DOCKER_COMPOSE ps | grep -q "Up"; then
    echo -e "${RED}❌ Some containers failed to start. Check logs with: $DOCKER_COMPOSE logs${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Containers are running${NC}"

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
$DOCKER_COMPOSE exec -T app composer install --optimize-autoloader --no-dev

# Install Node dependencies
echo "📦 Installing Node.js dependencies..."
$DOCKER_COMPOSE exec -T node npm install

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    $DOCKER_COMPOSE exec -T app php artisan key:generate
fi

# Set permissions
echo "🔐 Setting storage permissions..."
$DOCKER_COMPOSE exec -T app chmod -R 775 storage bootstrap/cache
$DOCKER_COMPOSE exec -T app chown -R www-data:www-data storage bootstrap/cache

# Run migrations
echo "🗄️  Running database migrations..."
$DOCKER_COMPOSE exec -T app php artisan migrate --force

# Build frontend assets
echo "🎨 Building frontend assets..."
$DOCKER_COMPOSE exec -T node npm run build

# Cache configuration for production
echo "⚡ Caching configuration for production..."
$DOCKER_COMPOSE exec -T app php artisan config:cache
$DOCKER_COMPOSE exec -T app php artisan route:cache
$DOCKER_COMPOSE exec -T app php artisan view:cache

echo ""
echo -e "${GREEN}✅ Deployment complete!${NC}"
echo ""
echo "🌐 Application is available at: http://40.127.10.196:8084"
echo "🗄️  phpMyAdmin is available at: http://40.127.10.196:8083/"
echo ""
echo "Database Credentials:"
echo "  - Username: Kiboauto_2025_admin"
echo "  - Password: kiboAuto_2025"
echo "  - Database: kiboAuto_2025"
echo ""
echo "Useful commands:"
echo "  - View logs: $DOCKER_COMPOSE logs -f"
echo "  - Check status: $DOCKER_COMPOSE ps"
echo "  - Restart services: $DOCKER_COMPOSE restart"
echo "  - Stop services: $DOCKER_COMPOSE stop"
echo ""

