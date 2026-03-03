#!/bin/bash
#
# Run Kibo on server with Docker (MySQL, Redis, Nginx, App, Queue)
# Usage: ./run-on-server.sh
# Server API: 197.250.35.61
#
# This script:
#  - Ensures .env is configured for Docker (DB_HOST=db, REDIS_HOST=redis)
#  - Starts all containers including MySQL in Docker
#  - Waits for MySQL to be ready, runs migrations, builds assets
#  - Leaves the stack running with no database connection issues
#

set -e

# --- Config (edit if needed) ---
SERVER_IP="${SERVER_IP:-197.250.35.61}"
APP_PORT="${APP_PORT:-8084}"
APP_URL_DEFAULT="http://${SERVER_IP}:${APP_PORT}"

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo "=============================================="
echo "  Kibo – Docker run on server (MySQL in Docker)"
echo "  Server: ${SERVER_IP}  |  Port: ${APP_PORT}"
echo "=============================================="
echo ""

# --- Docker Compose command ---
if command -v docker-compose &> /dev/null; then
    DOCKER_COMPOSE="docker-compose"
elif docker compose version &> /dev/null 2>&1; then
    DOCKER_COMPOSE="docker compose"
else
    echo -e "${RED}❌ Docker Compose not found. Install Docker and Docker Compose.${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Using: $DOCKER_COMPOSE${NC}"
echo ""

# --- Ensure we're in project root ---
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# --- Ensure .env exists and is configured for Docker ---
if [ ! -f .env ]; then
    echo -e "${YELLOW}No .env found. Copying from .env.example...${NC}"
    if [ -f .env.example ]; then
        cp .env.example .env
        echo -e "${GREEN}✅ Created .env from .env.example${NC}"
    else
        echo -e "${RED}❌ No .env.example. Create .env with at least DB_*, APP_KEY, APP_URL.${NC}"
        exit 1
    fi
fi

# Force Docker-friendly DB and Redis hosts (so app container uses MySQL/Redis containers)
ensure_env() {
    local key="$1"
    local value="$2"
    if grep -q "^${key}=" .env 2>/dev/null; then
        sed "s|^${key}=.*|${key}=${value}|" .env > .env.tmp && mv .env.tmp .env
    else
        echo "${key}=${value}" >> .env
    fi
}

echo "Step 0: Ensuring .env is set for Docker (DB_HOST=db, REDIS_HOST=redis)..."
ensure_env "DB_HOST" "db"
ensure_env "DB_PORT" "3306"
ensure_env "REDIS_HOST" "redis"
ensure_env "REDIS_PORT" "6379"
# Optionally set APP_URL for server (uncomment and set your domain if you have one)
# ensure_env "APP_URL" "$APP_URL_DEFAULT"
echo -e "${GREEN}✅ .env configured for Docker${NC}"
echo ""

# --- Ensure Docker config files exist ---
if [ ! -f docker/php/local.ini ]; then
    echo -e "${YELLOW}Creating docker/php/local.ini...${NC}"
    mkdir -p docker/php
    cat > docker/php/local.ini << 'PHPINI'
upload_max_filesize=40M
post_max_size=40M
memory_limit=256M
max_execution_time=300
max_input_vars=3000
PHPINI
fi
if [ ! -f docker/nginx/default.conf ]; then
    echo -e "${RED}❌ docker/nginx/default.conf missing. Add it from the repo.${NC}"
    exit 1
fi
echo ""

# --- Build and start all services (MySQL first, then app, nginx, redis, node, queue) ---
echo "Step 1: Building and starting containers (MySQL, Redis, App, Nginx, Node, Queue)..."
$DOCKER_COMPOSE build --no-cache 2>/dev/null || true
$DOCKER_COMPOSE up -d
echo -e "${GREEN}✅ Containers started${NC}"
echo ""

# --- Wait for MySQL to be ready (app container uses .env with DB_HOST=db) ---
echo "Step 2: Waiting for MySQL (db) to be ready..."
for i in $(seq 1 60); do
    if $DOCKER_COMPOSE exec -T app php artisan db:show 2>/dev/null; then
        echo -e "${GREEN}✅ MySQL is ready${NC}"
        break
    fi
    if [ "$i" -eq 60 ]; then
        echo -e "${YELLOW}⚠️  MySQL slow to start; continuing anyway. If migrations fail, run: $DOCKER_COMPOSE restart db && sleep 20${NC}"
    fi
    sleep 2
done
echo ""

# --- Create DB user if needed (MySQL container creates from MYSQL_* env; Laravel uses DB_* from .env) ---
# Docker Compose passes DB_DATABASE, DB_USERNAME, DB_PASSWORD to MySQL container as MYSQL_DATABASE, MYSQL_USER, MYSQL_PASSWORD.
# So as long as .env has same DB_* values, the database and user already exist. No extra init needed.
echo "Step 3: Installing PHP dependencies..."
$DOCKER_COMPOSE exec -T app composer install --optimize-autoloader --no-dev --no-interaction
echo -e "${GREEN}✅ Composer install done${NC}"
echo ""

echo "Step 4: Installing Node dependencies..."
$DOCKER_COMPOSE exec -T node npm install --silent
echo -e "${GREEN}✅ NPM install done${NC}"
echo ""

echo "Step 5: Running database migrations..."
$DOCKER_COMPOSE exec -T app php artisan migrate --force
echo -e "${GREEN}✅ Migrations done${NC}"
echo ""

echo "Step 6: Storage and cache permissions..."
$DOCKER_COMPOSE exec -T app chmod -R 775 storage bootstrap/cache 2>/dev/null || true
$DOCKER_COMPOSE exec -T app chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
echo -e "${GREEN}✅ Permissions set${NC}"
echo ""

echo "Step 7: Clearing and caching config..."
$DOCKER_COMPOSE exec -T app php artisan config:clear
$DOCKER_COMPOSE exec -T app php artisan config:cache
echo -e "${GREEN}✅ Config cached${NC}"
echo ""

echo "Step 8: Building frontend assets..."
$DOCKER_COMPOSE exec -T node npm run build
echo -e "${GREEN}✅ Frontend built${NC}"
echo ""

echo "Step 9: Restarting app, nginx, queue..."
$DOCKER_COMPOSE restart app nginx queue
sleep 3
echo -e "${GREEN}✅ Restart done${NC}"
echo ""

# --- Status ---
echo "=============================================="
echo "  Container status"
echo "=============================================="
$DOCKER_COMPOSE ps
echo ""

echo -e "${GREEN}✅ Kibo is running with MySQL in Docker.${NC}"
echo ""
echo "  API / App URL:  http://${SERVER_IP}:${APP_PORT}"
echo "  (Set APP_URL in .env to your domain when using one, e.g. https://kiboauto.co.tz)"
echo ""
echo "  Useful commands:"
echo "    $DOCKER_COMPOSE ps              # status"
echo "    $DOCKER_COMPOSE logs -f         # all logs"
echo "    $DOCKER_COMPOSE logs -f app     # app logs"
echo "    $DOCKER_COMPOSE logs -f db      # MySQL logs"
echo "    $DOCKER_COMPOSE restart         # restart all"
echo ""
