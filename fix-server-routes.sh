#!/bin/bash

# Script to fix route cache on server after moving Twilio routes to API

echo "=========================================="
echo "Fixing Route Cache on Server"
echo "=========================================="
echo ""

echo "1. Clearing route cache..."
php artisan route:clear

echo "2. Clearing application cache..."
php artisan cache:clear

echo "3. Clearing config cache..."
php artisan config:clear

echo "4. Verifying API routes are registered..."
php artisan route:list --path=api/webhook/twilio

echo ""
echo "=========================================="
echo "If routes are not showing, check:"
echo "1. routes/api.php exists and has the Twilio routes"
echo "2. bootstrap/app.php includes api routes: api: __DIR__.'/../routes/api.php'"
echo "3. Run: php artisan route:clear && php artisan config:clear"
echo "=========================================="

