# Server Deployment Guide

This guide covers deploying the Kibo application to the server at `40.127.10.196:8084`.

## Prerequisites

- SSH access to the server
- Docker and Docker Compose installed on the server
- Git installed on the server
- Server IP: `40.127.10.196`
- Application Port: `8084`
- Database credentials configured

## Step-by-Step Deployment

### 1. Connect to the Server

```bash
ssh your-user@40.127.10.196
```

### 2. Navigate to Project Directory

```bash
cd /var/www/html/Kibo_version_2/kiboV2
```

### 3. Pull Latest Changes (if using Git)

```bash
git pull origin main
# or
git pull origin master
```

### 4. Create/Update Environment File

Create or update the `.env` file with production settings:

```bash
cp .env.example .env
# Or if .env already exists, backup it first
# cp .env .env.backup
```

Edit `.env` file with the following configuration:

```env
APP_NAME=Kibo
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://40.127.10.196:8084

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=kiboAuto_2025
DB_USERNAME=Kiboauto_2025_admin
DB_PASSWORD=kiboAuto_2025

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Add other required environment variables
```

### 5. Stop Existing Containers (if running)

```bash
docker-compose down
```

### 6. Build and Start Docker Containers

```bash
docker-compose up -d --build
```

This will:
- Build the PHP 8.4 container
- Start MySQL database
- Start Redis
- Start Nginx web server
- Start Node.js container

### 7. Wait for Services to Start

```bash
# Wait a few seconds for MySQL to be ready
sleep 15

# Check container status
docker-compose ps
```

### 8. Install PHP Dependencies

```bash
docker-compose exec app composer install --optimize-autoloader --no-dev
```

### 9. Install Node.js Dependencies

```bash
docker-compose exec node npm install
```

### 10. Generate Application Key (if not set)

```bash
docker-compose exec app php artisan key:generate
```

### 11. Set Storage Permissions

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### 12. Run Database Migrations

```bash
# Run migrations
docker-compose exec app php artisan migrate --force

# Or with seeders (if needed)
# docker-compose exec app php artisan migrate --seed --force
```

### 13. Build Frontend Assets

```bash
docker-compose exec node npm run build
```

### 14. Clear and Cache Configuration

```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### 15. Verify Deployment

Check if containers are running:

```bash
docker-compose ps
```

Check application logs:

```bash
docker-compose logs -f app
docker-compose logs -f nginx
```

### 16. Test the Application

Visit the application in your browser:
- **Application**: http://40.127.10.196:8084
- **phpMyAdmin**: http://40.127.10.196:8083/

## Quick Deployment Script

You can also use the automated deployment script on the server:

```bash
cd /var/www/html/Kibo_version_2/kiboV2
chmod +x deploy.sh
./deploy.sh
```

Then manually run the production optimizations:

```bash
docker-compose exec app composer install --optimize-autoloader --no-dev
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
docker-compose exec node npm run build
```

## Post-Deployment Checklist

- [ ] All containers are running (`docker-compose ps`)
- [ ] Application is accessible at http://40.127.10.196:8084
- [ ] Database connection is working
- [ ] Storage permissions are set correctly
- [ ] Frontend assets are built
- [ ] Configuration is cached
- [ ] No errors in logs (`docker-compose logs`)

## Common Deployment Commands

### View Logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db
```

### Restart Services
```bash
# Restart all services
docker-compose restart

# Restart specific service
docker-compose restart app
docker-compose restart nginx
```

### Update Application

```bash
# Pull latest code
git pull

# Rebuild containers (if Dockerfile changed)
docker-compose up -d --build

# Install new dependencies
docker-compose exec app composer install --optimize-autoloader --no-dev
docker-compose exec node npm install

# Run migrations
docker-compose exec app php artisan migrate --force

# Rebuild assets
docker-compose exec node npm run build

# Clear and recache
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### Backup Database

```bash
docker-compose exec db mysqldump -u Kiboauto_2025_admin -pkiboAuto_2025 kiboAuto_2025 > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Restore Database

```bash
docker-compose exec -T db mysql -u Kiboauto_2025_admin -pkiboAuto_2025 kiboAuto_2025 < backup_file.sql
```

## Troubleshooting

### Containers Not Starting

```bash
# Check logs
docker-compose logs

# Check container status
docker-compose ps -a

# Restart containers
docker-compose restart
```

### Permission Issues

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 755 /var/www/html
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Database Connection Issues

```bash
# Test database connection
docker-compose exec app php artisan tinker
# Then in tinker: DB::connection()->getPdo();

# Check database container
docker-compose exec db mysql -u Kiboauto_2025_admin -pkiboAuto_2025 -e "SHOW DATABASES;"
```

### Clear All Caches

```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### Rebuild Everything

```bash
# Stop and remove containers
docker-compose down

# Remove volumes (WARNING: This deletes database data)
# docker-compose down -v

# Rebuild and start
docker-compose up -d --build
```

## Monitoring

### Check Container Resource Usage

```bash
docker stats
```

### Check Disk Space

```bash
df -h
docker system df
```

### View Container Details

```bash
docker inspect kibo_app
docker inspect kibo_nginx
docker inspect kibo_mysql
```

## Security Considerations

1. **Environment Variables**: Never commit `.env` file to Git
2. **APP_DEBUG**: Set to `false` in production
3. **Database Passwords**: Use strong passwords
4. **Firewall**: Ensure only necessary ports are open
5. **SSL/TLS**: Consider setting up HTTPS with reverse proxy
6. **Backups**: Regularly backup database and files

## Maintenance

### Regular Updates

1. Pull latest code changes
2. Update dependencies: `composer update` and `npm update`
3. Run migrations if needed
4. Rebuild assets
5. Clear and cache configuration

### Log Rotation

Monitor log files and set up rotation:

```bash
# View Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log

# View Nginx logs
docker-compose logs -f nginx
```

## Support

If you encounter issues:
1. Check container logs: `docker-compose logs`
2. Verify environment variables in `.env`
3. Check container status: `docker-compose ps`
4. Verify network connectivity
5. Check server resources (CPU, memory, disk)

