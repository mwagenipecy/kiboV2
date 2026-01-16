# Docker Setup Guide

This guide will help you set up and run the Kibo application using Docker.

## Prerequisites

- Docker Desktop (or Docker Engine + Docker Compose)
- Git

## Quick Start

### 1. Environment Configuration

Create a `.env` file in the root directory if you don't have one. You can copy from `.env.example`:

```bash
cp .env.example .env
```

Update the following database configuration in your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=kiboAuto_2025
DB_USERNAME=Kiboauto_2025_admin
DB_PASSWORD=kiboAuto_2025

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

APP_URL=http://40.127.10.196:8084
```

### 2. Build and Start Containers

```bash
docker-compose up -d --build
```

This will:
- Build the PHP 8.4 container with all required extensions
- Start MySQL database
- Start Redis
- Start Nginx web server
- Start Node.js for asset compilation

### 3. Install Dependencies

Once containers are running, install PHP dependencies:

```bash
docker-compose exec app composer install
```

Install Node.js dependencies:

```bash
docker-compose exec node npm install
```

### 4. Generate Application Key

```bash
docker-compose exec app php artisan key:generate
```

### 5. Run Migrations

```bash
docker-compose exec app php artisan migrate
```

Or with seeders:

```bash
docker-compose exec app php artisan migrate --seed
```

### 6. Build Frontend Assets

```bash
docker-compose exec node npm run build
```

Or for development with hot reload:

```bash
docker-compose exec node npm run dev
```

### 7. Set Storage Permissions

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

## Accessing the Application

- **Web Application**: http://40.127.10.196:8084
  - Server IP: `40.127.10.196`
  - Port: `8084`
- **MySQL Database**: localhost:3306 (or 40.127.10.196:3306 from external)
  - Username: `Kiboauto_2025_admin`
  - Password: `kiboAuto_2025`
  - Database: `kiboAuto_2025`
- **phpMyAdmin**: http://40.127.10.196:8083/
  - Username: `Kiboauto_2025_admin`
  - Password: `kiboAuto_2025`
- **Redis**: localhost:6379 (or 40.127.10.196:6379 from external)

## Common Docker Commands

### View running containers
```bash
docker-compose ps
```

### View logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db
```

### Execute commands in containers
```bash
# PHP/Artisan commands
docker-compose exec app php artisan [command]

# Composer commands
docker-compose exec app composer [command]

# Node/NPM commands
docker-compose exec node npm [command]

# Access container shell
docker-compose exec app bash
```

### Stop containers
```bash
docker-compose stop
```

### Stop and remove containers
```bash
docker-compose down
```

### Stop and remove containers with volumes (removes database data)
```bash
docker-compose down -v
```

### Rebuild containers
```bash
docker-compose up -d --build
```

## Services Overview

- **app**: PHP 8.4-FPM container with Laravel application
- **nginx**: Nginx web server (port 8084)
- **db**: MySQL 8.0 database (port 3306)
- **redis**: Redis cache/queue service (port 6379)
- **node**: Node.js 20 for frontend asset compilation

## Troubleshooting

### Permission Issues
If you encounter permission issues, run:
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 755 /var/www/html
```

### Database Connection Issues
Ensure your `.env` file has:
- `DB_HOST=db` (for Docker container) or `40.127.10.196` (for external database server)
- `DB_DATABASE=

**Note**: If you're connecting to an existing external database server instead of the Docker MySQL container, update `DB_HOST` in your `.env` file to `40.127.10.196` and ensure the database server is accessible from the Docker network.

### Clear Cache
```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
```

### Rebuild After Changes
If you modify the Dockerfile or docker-compose.yml:
```bash
docker-compose down
docker-compose up -d --build
```

## Development Workflow

1. Make code changes in your local files (they're synced via volumes)
2. For PHP changes, they're immediately available
3. For frontend changes, run `docker-compose exec node npm run dev` for hot reload
4. Access the application at http://40.127.10.196:8084

## Production Considerations

For production deployment:
1. Update `.env` with production values
2. Set `APP_DEBUG=false`
3. Run `php artisan config:cache` and `php artisan route:cache`
4. Use proper database credentials
5. Configure proper SSL/TLS certificates
6. Review and adjust resource limits in docker-compose.yml

