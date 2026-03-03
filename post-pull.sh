#!/bin/bash

# Post-Pull Deployment Script
# Run this script on the server after: git pull origin main
# Usage: ./post-pull.sh

set -e

echo "🚀 Post-Pull Deployment Script"
echo "================================"
echo ""
echo "This script expects Docker with MySQL (db), app, nginx, redis, node, and queue."
echo "First time on server? Run: ./run-on-server.sh  then use ./post-pull.sh after git pull."
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Detect docker-compose command
if command -v docker-compose &> /dev/null; then
    DOCKER_COMPOSE="docker-compose"
elif docker compose version &> /dev/null 2>&1; then
    DOCKER_COMPOSE="docker compose"
else
    echo -e "${RED}❌ Docker Compose is not installed${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Using: $DOCKER_COMPOSE${NC}"
echo ""

# Step 1: Ensure containers are running
echo "Step 1: Ensuring containers are running..."
echo "------------------------------------------"
$DOCKER_COMPOSE up -d
sleep 3
echo -e "${GREEN}✅ Containers checked${NC}"
echo ""

# Step 1.5: Ensure queue worker is running
echo "Step 1.5: Ensuring queue worker is running..."
echo "---------------------------------------------"
if $DOCKER_COMPOSE ps queue | grep -q "Up"; then
    echo -e "${GREEN}✅ Queue worker is running${NC}"
else
    echo -e "${YELLOW}⚠️  Starting queue worker...${NC}"
    $DOCKER_COMPOSE up -d queue
    sleep 2
    if $DOCKER_COMPOSE ps queue | grep -q "Up"; then
        echo -e "${GREEN}✅ Queue worker started${NC}"
    else
        echo -e "${RED}❌ Queue worker failed to start${NC}"
    fi
fi
echo ""

# Step 2: Install/Update PHP dependencies
echo "Step 2: Installing/Updating PHP dependencies..."
echo "-----------------------------------------------"
if $DOCKER_COMPOSE exec -T app composer install --optimize-autoloader --no-dev --quiet; then
    echo -e "${GREEN}✅ PHP dependencies updated${NC}"
else
    echo -e "${YELLOW}⚠️  Composer install had some issues (check logs)${NC}"
fi
echo ""

# Step 3: Install/Update Node dependencies
echo "Step 3: Installing/Updating Node dependencies..."
echo "------------------------------------------------"
if $DOCKER_COMPOSE exec -T node npm install --silent; then
    echo -e "${GREEN}✅ Node dependencies updated${NC}"
else
    echo -e "${YELLOW}⚠️  NPM install had some issues (check logs)${NC}"
fi
echo ""

# Step 3.5: Wait for MySQL to be ready (when using Docker DB)
echo "Step 3.5: Waiting for MySQL to be ready..."
echo "-------------------------------------------"
if $DOCKER_COMPOSE ps db 2>/dev/null | grep -q "Up"; then
    echo "Docker MySQL (db) detected, waiting for it to accept connections..."
    for i in $(seq 1 30); do
        if $DOCKER_COMPOSE exec -T app php artisan db:show 2>/dev/null; then
            echo -e "${GREEN}✅ MySQL is ready${NC}"
            break
        fi
        if [ "$i" -eq 30 ]; then
            echo -e "${YELLOW}⚠️  MySQL may still be starting; migrations will be attempted anyway${NC}"
        fi
        sleep 2
    done
else
    echo "Using external database; skipping MySQL wait."
fi
echo ""

# Step 4: Run database migrations
echo "Step 4: Running database migrations..."
echo "--------------------------------------"
if $DOCKER_COMPOSE exec -T app php artisan migrate --force; then
    echo -e "${GREEN}✅ Migrations completed${NC}"
else
    echo -e "${YELLOW}⚠️  Migrations had some issues (check logs)${NC}"
fi
echo ""

# Step 5: Fix storage permissions
echo "Step 5: Fixing storage permissions..."
echo "-------------------------------------"
$DOCKER_COMPOSE exec -T app chmod -R 775 storage bootstrap/cache 2>/dev/null || true
$DOCKER_COMPOSE exec -T app chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
echo -e "${GREEN}✅ Permissions fixed${NC}"
echo ""

# Step 6: Clear Laravel caches
echo "Step 6: Clearing Laravel caches..."
echo "-----------------------------------"
$DOCKER_COMPOSE exec -T app php artisan config:clear 2>/dev/null || true
$DOCKER_COMPOSE exec -T app php artisan route:clear 2>/dev/null || true
$DOCKER_COMPOSE exec -T app php artisan view:clear 2>/dev/null || true
$DOCKER_COMPOSE exec -T app php artisan cache:clear 2>/dev/null || true
echo -e "${GREEN}✅ Caches cleared${NC}"
echo ""

# Step 7: Build frontend assets
echo "Step 7: Building frontend assets..."
echo "-----------------------------------"
if $DOCKER_COMPOSE exec -T node npm run build; then
    echo -e "${GREEN}✅ Frontend assets built${NC}"
else
    echo -e "${YELLOW}⚠️  Asset build had some issues (check logs)${NC}"
fi
echo ""

# Step 8: Cache configuration for production
echo "Step 8: Caching configuration for production..."
echo "----------------------------------------------"
$DOCKER_COMPOSE exec -T app php artisan config:cache 2>/dev/null || true
$DOCKER_COMPOSE exec -T app php artisan route:cache 2>/dev/null || true
$DOCKER_COMPOSE exec -T app php artisan view:cache 2>/dev/null || true
echo -e "${GREEN}✅ Configuration cached${NC}"
echo ""

# Step 9: Restart containers to ensure everything is fresh
echo "Step 9: Restarting containers..."
echo "---------------------------------"
$DOCKER_COMPOSE restart app nginx queue
sleep 2
echo -e "${GREEN}✅ Containers restarted${NC}"
echo ""

# Final status check
echo "Final Status Check"
echo "=================="
$DOCKER_COMPOSE ps
echo ""

echo ""
echo -e "${GREEN}✅ Post-pull deployment completed successfully!${NC}"
echo ""
echo "🌐 Your application is available at:"
echo "   - http://197.250.35.61:8084 (IP access)"
echo "   - http://kiboauto.co.tz (when DNS points to 197.250.35.61)"
echo ""
echo "📝 Domain: http://kiboauto.co.tz  |  WWW: http://www.kiboauto.co.tz"
echo ""
echo "⚠️  Set APP_URL in .env to http://kiboauto.co.tz (or https:// when SSL is configured)."
echo ""
echo "📋 Quick commands:"
echo "   - View logs: $DOCKER_COMPOSE logs -f"
echo "   - View queue logs: $DOCKER_COMPOSE logs -f queue"
echo "   - Check status: $DOCKER_COMPOSE ps"
echo "   - Restart services: $DOCKER_COMPOSE restart"
echo "   - Restart queue worker: $DOCKER_COMPOSE restart queue"
echo ""
echo "🔄 Queue Worker Status:"
if $DOCKER_COMPOSE ps queue | grep -q "Up"; then
    echo -e "   ${GREEN}✅ Queue worker is running${NC}"
    echo "   - Processing WhatsApp messages from queue"
    echo "   - Check logs: $DOCKER_COMPOSE logs -f queue"
else
    echo -e "   ${RED}❌ Queue worker is not running${NC}"
    echo "   - Start it: $DOCKER_COMPOSE up -d queue"
fi
echo ""

