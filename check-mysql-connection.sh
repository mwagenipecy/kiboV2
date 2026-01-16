#!/bin/bash

# Script to test MySQL connection from Docker container
# Run this to diagnose database connection issues

echo "Testing MySQL connection options..."

# Test 1: host.docker.internal
echo "1. Testing host.docker.internal..."
docker-compose exec -T app php -r "
try {
    \$pdo = new PDO('mysql:host=host.docker.internal;port=3306', 'Kiboauto_2025_admin', 'kiboAuto_2025');
    echo '✅ host.docker.internal works\n';
} catch (Exception \$e) {
    echo '❌ host.docker.internal failed: ' . \$e->getMessage() . '\n';
}
"

# Test 2: 172.17.0.1 (Docker bridge gateway)
echo "2. Testing 172.17.0.1 (Docker bridge gateway)..."
docker-compose exec -T app php -r "
try {
    \$pdo = new PDO('mysql:host=172.17.0.1;port=3306', 'Kiboauto_2025_admin', 'kiboAuto_2025');
    echo '✅ 172.17.0.1 works\n';
} catch (Exception \$e) {
    echo '❌ 172.17.0.1 failed: ' . \$e->getMessage() . '\n';
}
"

# Test 3: Server IP
echo "3. Testing 40.127.10.196..."
docker-compose exec -T app php -r "
try {
    \$pdo = new PDO('mysql:host=40.127.10.196;port=3306', 'Kiboauto_2025_admin', 'kiboAuto_2025');
    echo '✅ 40.127.10.196 works\n';
} catch (Exception \$e) {
    echo '❌ 40.127.10.196 failed: ' . \$e->getMessage() . '\n';
}
"

# Test 4: Check if MySQL is listening on the right interface
echo ""
echo "Checking MySQL bind address on host..."
echo "Run this on the host (not in Docker):"
echo "  sudo netstat -tlnp | grep 3306"
echo "  or"
echo "  sudo ss -tlnp | grep 3306"
echo ""
echo "MySQL should be bound to 0.0.0.0:3306 or 40.127.10.196:3306"
echo "If it's only bound to 127.0.0.1:3306, you need to update MySQL config:"
echo "  Edit /etc/mysql/mysql.conf.d/mysqld.cnf"
echo "  Change bind-address = 127.0.0.1 to bind-address = 0.0.0.0"
echo "  Then restart MySQL: sudo systemctl restart mysql"

