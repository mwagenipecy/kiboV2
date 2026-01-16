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
        echo "DB_HOST=db" >> .env
        echo "DB_PORT=3306" >> .env
        echo "DB_DATABASE=kiboAuto_2025" >> .env
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
# Update or add database configuration
if grep -q "^DB_HOST=" .env; then
    sed -i.bak 's/^DB_HOST=.*/DB_HOST=db/' .env 2>/dev/null || sed -i '' 's/^DB_HOST=.*/DB_HOST=db/' .env
else
    echo "DB_HOST=db" >> .env
fi

if grep -q "^DB_CONNECTION=" .env; then
    sed -i.bak 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env 2>/dev/null || sed -i '' 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
else
    echo "DB_CONNECTION=mysql" >> .env
fi

if grep -q "^DB_DATABASE=" .env; then
    sed -i.bak 's/^DB_DATABASE=.*/DB_DATABASE=kiboAuto_2025/' .env 2>/dev/null || sed -i '' 's/^DB_DATABASE=.*/DB_DATABASE=kiboAuto_2025/' .env
else
    echo "DB_DATABASE=kiboAuto_2025" >> .env
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
docker-compose down

# Build and start containers
echo "🏗️  Building and starting Docker containers..."
docker-compose up -d --build

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 15

# Check if containers are running
if ! docker-compose ps | grep -q "Up"; then
    echo -e "${RED}❌ Some containers failed to start. Check logs with: docker-compose logs${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Containers are running${NC}"

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
docker-compose exec -T app composer install --optimize-autoloader --no-dev

# Install Node dependencies
echo "📦 Installing Node.js dependencies..."
docker-compose exec -T node npm install

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    docker-compose exec -T app php artisan key:generate
fi

# Set permissions
echo "🔐 Setting storage permissions..."
docker-compose exec -T app chmod -R 775 storage bootstrap/cache
docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache

# Run migrations
echo "🗄️  Running database migrations..."
docker-compose exec -T app php artisan migrate --force

# Build frontend assets
echo "🎨 Building frontend assets..."
docker-compose exec -T node npm run build

# Cache configuration for production
echo "⚡ Caching configuration for production..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

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
echo "  - View logs: docker-compose logs -f"
echo "  - Check status: docker-compose ps"
echo "  - Restart services: docker-compose restart"
echo "  - Stop services: docker-compose stop"
echo ""

