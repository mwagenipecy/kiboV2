#!/bin/bash

# Test DNS from server perspective
# Run this on your server to verify DNS configuration

echo "🔍 DNS Testing Script (Run on Server)"
echo "======================================"
echo ""

DOMAIN="stage.kiboauto.co.tz"
EXPECTED_IP="40.127.10.196"

echo "Testing DNS resolution for: $DOMAIN"
echo "Expected IP: $EXPECTED_IP"
echo ""

# Test with different DNS servers
echo "1. Testing with Google DNS (8.8.8.8):"
dig @8.8.8.8 +short $DOMAIN
echo ""

echo "2. Testing with Cloudflare DNS (1.1.1.1):"
dig @1.1.1.1 +short $DOMAIN
echo ""

echo "3. Testing with system DNS:"
dig +short $DOMAIN
echo ""

echo "4. Full DNS query:"
dig $DOMAIN
echo ""

echo "5. Testing www subdomain:"
dig +short www.stage.kiboauto.co.tz
echo ""

echo "6. Testing HTTP connectivity:"
if curl -s -o /dev/null -w "HTTP Status: %{http_code}\n" --max-time 5 "http://$DOMAIN" 2>/dev/null; then
    echo "✅ Domain is accessible via HTTP"
else
    echo "❌ Cannot connect to domain via HTTP"
    echo "   This could mean DNS is not configured or nginx is not running"
fi

echo ""
echo "======================================"
echo "Summary:"
echo "- If dig shows $EXPECTED_IP, DNS is configured correctly"
echo "- If dig shows nothing, DNS records may not be saved correctly"
echo "- Check your DNS provider to ensure records are active"

