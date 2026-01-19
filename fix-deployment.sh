#!/bin/bash

# Quick Fix Script for Server Deployment Issues
# Run this on the server after git pull if the application is not accessible

set -e

echo "🔧 Quick Fix Script for Deployment Issues"
echo "=========================================="
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

echo "Step 1: Checking Docker containers status..."
echo "---------------------------------------------"
$DOCKER_COMPOSE ps
echo ""

echo "Step 2: Checking container logs for errors..."
echo "----------------------------------------------"
echo -e "${YELLOW}Recent errors from app container:${NC}"
$DOCKER_COMPOSE logs --tail=50 app 2>&1 | grep -i error || echo "No errors found in recent logs"
echo ""

echo -e "${YELLOW}Recent errors from nginx container:${NC}"
$DOCKER_COMPOSE logs --tail=50 nginx 2>&1 | grep -i error || echo "No errors found in recent logs"
echo ""

echo "Step 3: Checking if containers are running..."
echo "----------------------------------------------"
if $DOCKER_COMPOSE ps | grep -q "Up"; then
    echo -e "${GREEN}✅ Some containers are running${NC}"
else
    echo -e "${RED}❌ No containers are running${NC}"
    echo "Starting containers..."
    $DOCKER_COMPOSE up -d
    sleep 5
fi
echo ""

echo "Step 4: Ensuring all containers are up..."
echo "------------------------------------------"
$DOCKER_COMPOSE up -d
sleep 3
echo ""

echo "Step 5: Installing/Updating PHP dependencies..."
echo "-----------------------------------------------"
$DOCKER_COMPOSE exec -T app composer install --optimize-autoloader --no-dev --quiet || echo -e "${YELLOW}⚠️  Composer install had issues${NC}"
echo ""

echo "Step 6: Installing/Updating Node dependencies..."
echo "------------------------------------------------"
$DOCKER_COMPOSE exec -T node npm install --silent || echo -e "${YELLOW}⚠️  NPM install had issues${NC}"
echo ""

echo "Step 7: Fixing storage permissions..."
echo "-------------------------------------"
$DOCKER_COMPOSE exec -T app chmod -R 775 storage bootstrap/cache 2>/dev/null || true
$DOCKER_COMPOSE exec -T app chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
echo -e "${GREEN}✅ Permissions set${NC}"
echo ""

echo "Step 8: Clearing Laravel caches..."
echo "-----------------------------------"
$DOCKER_COMPOSE exec -T app php artisan config:clear 2>/dev/null || true
$DOCKER_COMPOSE exec -T app php artisan route:clear 2>/dev/null || true
$DOCKER_COMPOSE exec -T app php artisan view:clear 2>/dev/null || true
$DOCKER_COMPOSE exec -T app php artisan cache:clear 2>/dev/null || true
echo -e "${GREEN}✅ Caches cleared${NC}"
echo ""

echo "Step 9: Rebuilding frontend assets..."
echo "--------------------------------------"
$DOCKER_COMPOSE exec -T node npm run build 2>/dev/null || echo -e "${YELLOW}⚠️  Asset build had issues${NC}"
echo ""

echo "Step 10: Caching configuration for production..."
echo "------------------------------------------------"
$DOCKER_COMPOSE exec -T app php artisan config:cache 2>/dev/null || true
$DOCKER_COMPOSE exec -T app php artisan route:cache 2>/dev/null || true
$DOCKER_COMPOSE exec -T app php artisan view:cache 2>/dev/null || true
echo -e "${GREEN}✅ Configuration cached${NC}"
echo ""

echo "Step 11: Final status check..."
echo "-------------------------------"
$DOCKER_COMPOSE ps
echo ""

echo ""
echo -e "${GREEN}✅ Fix script completed!${NC}"
echo ""
echo "📋 Summary:"
echo "  - Containers should be running"
echo "  - Dependencies installed"
echo "  - Permissions fixed"
echo "  - Caches cleared and rebuilt"
echo ""
echo "🔍 If still having issues, check logs:"
echo "  $DOCKER_COMPOSE logs -f app"
echo "  $DOCKER_COMPOSE logs -f nginx"
echo ""
echo "🌐 Application should be available at:"
echo "   - http://stage.kiboauto.co.tz"
echo "   - http://www.stage.kiboauto.co.tz (redirects to non-www)"
echo ""

