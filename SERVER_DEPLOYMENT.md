# Quick Server Deployment Guide

## Server Information

- **Server IP**: `40.127.10.196`
- **Project Path**: `/var/www/html/Kibo_version_2/kiboV2`
- **Application URL**: http://40.127.10.196:8084
- **phpMyAdmin URL**: http://40.127.10.196:8083/

## Quick Start

### 1. Connect to Server

```bash
ssh your-user@40.127.10.196
```

### 2. Navigate to Project

```bash
cd /var/www/html/Kibo_version_2/kiboV2
```

### 3. Pull Latest Code (if using Git)

```bash
git pull
```

### 4. Run Deployment Script

```bash
chmod +x deploy.sh
./deploy.sh
```

That's it! The script will handle everything automatically.

## Manual Deployment (Alternative)

If you prefer to deploy manually:

```bash
# 1. Navigate to project
cd /var/www/html/Kibo_version_2/kiboV2

# 2. Update .env file (if needed)
nano .env
# Ensure these values are set:
# DB_DATABASE=kiboAuto_2025
# DB_USERNAME=Kiboauto_2025_admin
# DB_PASSWORD=kiboAuto_2025
# APP_URL=http://40.127.10.196:8084
# APP_ENV=production
# APP_DEBUG=false

# 3. Stop existing containers
docker-compose down

# 4. Build and start containers
docker-compose up -d --build

# 5. Wait for services to start
sleep 15

# 6. Install dependencies
docker-compose exec app composer install --optimize-autoloader --no-dev
docker-compose exec node npm install

# 7. Generate key (if needed)
docker-compose exec app php artisan key:generate

# 8. Set permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache

# 9. Run migrations
docker-compose exec app php artisan migrate --force

# 10. Build assets
docker-compose exec node npm run build

# 11. Cache configuration
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

## Verify Deployment

```bash
# Check container status
docker-compose ps

# View logs
docker-compose logs -f

# Test application
curl http://40.127.10.196:8084
```

## Common Commands

```bash
# View logs
docker-compose logs -f

# Restart services
docker-compose restart

# Stop services
docker-compose stop

# Start services
docker-compose start

# Access app container
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan [command]
```

## Troubleshooting

### Check if containers are running
```bash
docker-compose ps
```

### View container logs
```bash
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db
```

### Restart all services
```bash
docker-compose restart
```

### Fix permissions
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Clear cache
```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

## Database Credentials

- **Username**: `Kiboauto_2025_admin`
- **Password**: `kiboAuto_2025`
- **Database**: `kiboAuto_2025`
- **Host**: `db` (inside Docker) or `40.127.10.196` (external)

## Access Points

- **Application**: http://40.127.10.196:8084
- **phpMyAdmin**: http://40.127.10.196:8083/
- **MySQL**: `40.127.10.196:3306`
- **Redis**: `40.127.10.196:6379`

