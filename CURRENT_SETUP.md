# Current Setup - Using IP Access

## ✅ Current Configuration

**Application URL:** `http://40.127.10.196:8084`

This is your primary access method while DNS is being configured.

## 🌐 Access Methods

### ✅ Working Now (IP Access)
- **Primary:** http://40.127.10.196:8084
- **Status:** ✅ Active and working

### ⏳ Pending (Domain Access)
- **Domain:** http://stage.kiboauto.co.tz
- **WWW:** http://www.stage.kiboauto.co.tz
- **Status:** ⏳ Waiting for DNS configuration

## 📋 Configuration Files

All configuration files are set to use IP access:

- **APP_URL in .env:** `http://40.127.10.196:8084`
- **Nginx:** Configured to handle both IP and domain
- **Deployment scripts:** Updated to use IP

## 🚀 Deployment Commands

### After Git Pull (Standard Workflow)

```bash
git pull origin main
./post-pull.sh
```

### Full Deployment (First Time or After Major Changes)

```bash
./deploy.sh
```

### Quick Fix (If Issues Occur)

```bash
./fix-deployment.sh
```

## 🔄 Switching to Domain Later

Once DNS is configured and working:

1. **Update .env file:**
   ```bash
   # Change APP_URL from:
   APP_URL=http://40.127.10.196:8084
   
   # To:
   APP_URL=http://stage.kiboauto.co.tz
   ```

2. **Clear Laravel caches:**
   ```bash
   docker compose exec app php artisan config:clear
   docker compose exec app php artisan route:clear
   docker compose exec app php artisan view:clear
   ```

3. **Recache configuration:**
   ```bash
   docker compose exec app php artisan config:cache
   docker compose exec app php artisan route:cache
   docker compose exec app php artisan view:cache
   ```

4. **Restart containers:**
   ```bash
   docker compose restart app nginx
   ```

## 📝 Notes

- **IP access will continue to work** even after domain is configured
- **Domain configuration is ready** - just needs DNS to be set up
- **Nginx handles both** IP and domain automatically
- **No code changes needed** when switching from IP to domain

## 🔍 Verify Setup

**Check if application is accessible:**
```bash
curl -I http://40.127.10.196:8084
# Should return HTTP 200 or 301/302
```

**Check container status:**
```bash
docker compose ps
# All containers should show "Up"
```

**View logs if issues:**
```bash
docker compose logs -f app
docker compose logs -f nginx
```

## 📞 Quick Reference

- **Application URL:** http://40.127.10.196:8084
- **Server IP:** 40.127.10.196
- **Port:** 8084
- **Domain (pending):** stage.kiboauto.co.tz

