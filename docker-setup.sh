#!/bin/bash

# Docker Setup Script for Kibo Application
# Run this once on the server to set up Docker with built-in MySQL. Then use post-pull.sh after each git pull.

set -e

echo "🐳 Setting up Docker environment for Kibo (app + MySQL + Redis + nginx + queue)..."

# Detect docker-compose command
if command -v docker-compose &> /dev/null; then
    DOCKER_COMPOSE="docker-compose"
elif docker compose version &> /dev/null 2>&1; then
    DOCKER_COMPOSE="docker compose"
else
    echo "❌ Docker Compose is not installed"
    exit 1
fi

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

# Update .env with Docker-specific database settings (built-in MySQL)
echo "📝 Updating .env for Docker (DB_HOST=db, REDIS_HOST=redis)..."
sed -i.bak 's/DB_HOST=.*/DB_HOST=db/' .env 2>/dev/null || sed -i '' 's/DB_HOST=.*/DB_HOST=db/' .env
sed -i.bak 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env 2>/dev/null || sed -i '' 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
sed -i.bak 's/DB_DATABASE=.*/DB_DATABASE=kiboAuto_2025/' .env 2>/dev/null || sed -i '' 's/DB_DATABASE=.*/DB_DATABASE=kiboAuto_2025/' .env
sed -i.bak 's/DB_USERNAME=.*/DB_USERNAME=Kiboauto_2025_admin/' .env 2>/dev/null || sed -i '' 's/DB_USERNAME=.*/DB_USERNAME=Kiboauto_2025_admin/' .env
sed -i.bak 's/DB_PASSWORD=.*/DB_PASSWORD=kiboAuto_2025/' .env 2>/dev/null || sed -i '' 's/DB_PASSWORD=.*/DB_PASSWORD=kiboAuto_2025/' .env
sed -i.bak 's/REDIS_HOST=.*/REDIS_HOST=redis/' .env 2>/dev/null || sed -i '' 's/REDIS_HOST=.*/REDIS_HOST=redis/' .env
sed -i.bak 's|APP_URL=.*|APP_URL=http://kiboauto.co.tz|' .env 2>/dev/null || sed -i '' 's|APP_URL=.*|APP_URL=http://kiboauto.co.tz|' .env

# Remove backup file if created
rm -f .env.bak

echo "✅ Updated .env file"

# Build and start all containers (including MySQL)
echo "🏗️  Building and starting Docker containers (db, app, nginx, redis, node, queue)..."
$DOCKER_COMPOSE up -d --build

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
for i in $(seq 1 45); do
    if $DOCKER_COMPOSE exec -T app php artisan db:show 2>/dev/null; then
        echo "✅ MySQL is ready"
        break
    fi
    [ "$i" -eq 45 ] && echo "⚠️  Timeout waiting for MySQL; continuing anyway."
    sleep 2
done

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
$DOCKER_COMPOSE exec -T app composer install --no-interaction

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

echo ""
echo "✅ Docker setup complete! (MySQL is running inside Docker.)"
echo ""
echo "🌐 Application: http://kiboauto.co.tz (or http://197.250.35.61:8084)"
echo "🗄️  MySQL (from host): localhost:3307 (user: Kiboauto_2025_admin, db: kiboAuto_2025)"
echo ""
echo "Next time after git pull, run: ./post-pull.sh"
echo ""
echo "Useful commands:"
echo "  - View logs: $DOCKER_COMPOSE logs -f"
echo "  - Stop: $DOCKER_COMPOSE stop"
echo "  - Start: $DOCKER_COMPOSE up -d"
echo "  - Shell: $DOCKER_COMPOSE exec app bash"
echo ""

