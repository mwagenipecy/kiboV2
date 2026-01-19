# Post-Pull Deployment Instructions

## Quick Start

After pulling changes from Git on your server, simply run:

```bash
./post-pull.sh
```

That's it! The script will handle everything automatically.

## What the Script Does

The `post-pull.sh` script automatically:

1. ✅ Ensures Docker containers are running
2. ✅ Installs/updates PHP dependencies (Composer)
3. ✅ Installs/updates Node.js dependencies (NPM)
4. ✅ Runs database migrations
5. ✅ Fixes storage permissions
6. ✅ Clears Laravel caches
7. ✅ Builds frontend assets
8. ✅ Caches configuration for production
9. ✅ Restarts containers

## Step-by-Step Usage

1. **SSH into your server:**
   ```bash
   ssh your-user@your-server-ip
   ```

2. **Navigate to project directory:**
   ```bash
   cd /var/www/html/Kibo_version_2/kiboV2
   # or wherever your project is located
   ```

3. **Pull latest changes:**
   ```bash
   git pull origin main
   # or
   git pull origin master
   ```

4. **Make script executable (first time only):**
   ```bash
   chmod +x post-pull.sh
   ```

5. **Run the post-pull script:**
   ```bash
   ./post-pull.sh
   ```

6. **Verify deployment:**
   - Visit: http://stage.kiboauto.co.tz
   - Check container status: `docker compose ps`
   - View logs if needed: `docker compose logs -f`

## Troubleshooting

If something goes wrong:

1. **Check container status:**
   ```bash
   docker compose ps
   ```

2. **View logs:**
   ```bash
   docker compose logs -f app
   docker compose logs -f nginx
   ```

3. **Run the fix script:**
   ```bash
   ./fix-deployment.sh
   ```

4. **Manual rebuild (if needed):**
   ```bash
   docker compose down
   docker compose up -d --build
   ./post-pull.sh
   ```

## Alternative: Manual Commands

If you prefer to run commands manually instead of using the script:

```bash
# After git pull, run these commands:

docker compose up -d
docker compose exec app composer install --optimize-autoloader --no-dev
docker compose exec node npm install
docker compose exec app php artisan migrate --force
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
docker compose exec node npm run build
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose restart app nginx
```

## Important Notes

- **Always pull changes first** before running the script
- **The script is safe to run multiple times** if needed
- **Check logs** if deployment fails
- **Your domain** is configured as: `stage.kiboauto.co.tz`
- **WWW version** (`www.stage.kiboauto.co.tz`) automatically redirects to non-www

## Support

If you encounter issues:
1. Check the logs: `docker compose logs -f`
2. Verify .env configuration
3. Ensure Docker is running: `docker ps`
4. Check disk space: `df -h`

