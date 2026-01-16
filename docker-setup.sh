#!/bin/bash

# Docker Setup Script for Kibo Application
# This script helps set up the Docker environment

set -e

echo "🐳 Setting up Docker environment for Kibo..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found. Creating from .env.example..."
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "✅ Created .env file"
    else
        echo "❌ .env.example not found. Please create .env manually."
        exit 1
    fi
fi

# Update .env with Docker-specific database settings
echo "📝 Updating .env with Docker database configuration..."
sed -i.bak 's/DB_HOST=.*/DB_HOST=db/' .env 2>/dev/null || sed -i '' 's/DB_HOST=.*/DB_HOST=db/' .env
sed -i.bak 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env 2>/dev/null || sed -i '' 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
sed -i.bak 's/DB_DATABASE=.*/DB_DATABASE=kiboAuto_2025/' .env 2>/dev/null || sed -i '' 's/DB_DATABASE=.*/DB_DATABASE=kiboAuto_2025/' .env
sed -i.bak 's/DB_USERNAME=.*/DB_USERNAME=Kiboauto_2025_admin/' .env 2>/dev/null || sed -i '' 's/DB_USERNAME=.*/DB_USERNAME=Kiboauto_2025_admin/' .env
sed -i.bak 's/DB_PASSWORD=.*/DB_PASSWORD=kiboAuto_2025/' .env 2>/dev/null || sed -i '' 's/DB_PASSWORD=.*/DB_PASSWORD=kiboAuto_2025/' .env
sed -i.bak 's/REDIS_HOST=.*/REDIS_HOST=redis/' .env 2>/dev/null || sed -i '' 's/REDIS_HOST=.*/REDIS_HOST=redis/' .env
sed -i.bak 's|APP_URL=.*|APP_URL=http://40.127.10.196:8084|' .env 2>/dev/null || sed -i '' 's|APP_URL=.*|APP_URL=http://40.127.10.196:8084|' .env

# Remove backup file if created
rm -f .env.bak

echo "✅ Updated .env file"

# Build and start containers
echo "🏗️  Building and starting Docker containers..."
docker-compose up -d --build

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 10

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
docker-compose exec -T app composer install --no-interaction

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

echo ""
echo "✅ Docker setup complete!"
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
echo "  - Stop containers: docker-compose stop"
echo "  - Start containers: docker-compose up -d"
echo "  - Access app container: docker-compose exec app bash"
echo "  - Run artisan commands: docker-compose exec app php artisan [command]"
echo ""

