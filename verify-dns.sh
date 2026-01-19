#!/bin/bash

# DNS Verification Script
# This script checks if DNS is properly configured for your domain

echo "🔍 DNS Configuration Verification"
echo "=================================="
echo ""

DOMAIN="stage.kiboauto.co.tz"
WWW_DOMAIN="www.stage.kiboauto.co.tz"
EXPECTED_IP="40.127.10.196"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo "Checking DNS records for:"
echo "  - $DOMAIN"
echo "  - $WWW_DOMAIN"
echo "Expected IP: $EXPECTED_IP"
echo ""

# Check main domain
echo "Checking $DOMAIN..."
if RESOLVED_IP=$(nslookup $DOMAIN 2>/dev/null | grep -A 1 "Name:" | tail -1 | awk '{print $2}'); then
    if [ "$RESOLVED_IP" = "$EXPECTED_IP" ]; then
        echo -e "${GREEN}✅ $DOMAIN resolves correctly to $RESOLVED_IP${NC}"
    else
        echo -e "${RED}❌ $DOMAIN resolves to $RESOLVED_IP (expected $EXPECTED_IP)${NC}"
    fi
else
    # Try with dig as fallback
    RESOLVED_IP=$(dig +short $DOMAIN 2>/dev/null | tail -1)
    if [ -n "$RESOLVED_IP" ]; then
        if [ "$RESOLVED_IP" = "$EXPECTED_IP" ]; then
            echo -e "${GREEN}✅ $DOMAIN resolves correctly to $RESOLVED_IP${NC}"
        else
            echo -e "${RED}❌ $DOMAIN resolves to $RESOLVED_IP (expected $EXPECTED_IP)${NC}"
        fi
    else
        echo -e "${RED}❌ $DOMAIN does not resolve (DNS not configured)${NC}"
    fi
fi

echo ""

# Check www domain
echo "Checking $WWW_DOMAIN..."
if RESOLVED_IP=$(nslookup $WWW_DOMAIN 2>/dev/null | grep -A 1 "Name:" | tail -1 | awk '{print $2}'); then
    if [ "$RESOLVED_IP" = "$EXPECTED_IP" ]; then
        echo -e "${GREEN}✅ $WWW_DOMAIN resolves correctly to $RESOLVED_IP${NC}"
    else
        echo -e "${RED}❌ $WWW_DOMAIN resolves to $RESOLVED_IP (expected $EXPECTED_IP)${NC}"
    fi
else
    # Try with dig as fallback
    RESOLVED_IP=$(dig +short $WWW_DOMAIN 2>/dev/null | tail -1)
    if [ -n "$RESOLVED_IP" ]; then
        if [ "$RESOLVED_IP" = "$EXPECTED_IP" ]; then
            echo -e "${GREEN}✅ $WWW_DOMAIN resolves correctly to $RESOLVED_IP${NC}"
        else
            echo -e "${RED}❌ $WWW_DOMAIN resolves to $RESOLVED_IP (expected $EXPECTED_IP)${NC}"
        fi
    else
        echo -e "${RED}❌ $WWW_DOMAIN does not resolve (DNS not configured)${NC}"
    fi
fi

echo ""

# Test HTTP connectivity
echo "Testing HTTP connectivity..."
if curl -s -o /dev/null -w "%{http_code}" --max-time 5 "http://$DOMAIN" > /dev/null 2>&1; then
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 "http://$DOMAIN")
    if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "301" ] || [ "$HTTP_CODE" = "302" ]; then
        echo -e "${GREEN}✅ HTTP connection successful (Status: $HTTP_CODE)${NC}"
    else
        echo -e "${YELLOW}⚠️  HTTP connection returned status: $HTTP_CODE${NC}"
    fi
else
    echo -e "${RED}❌ Cannot connect to http://$DOMAIN${NC}"
    echo "   This could mean:"
    echo "   - DNS is not configured yet"
    echo "   - Firewall is blocking port 80"
    echo "   - Nginx is not running"
fi

echo ""

# Test IP access
echo "Testing direct IP access (http://$EXPECTED_IP:8084)..."
if curl -s -o /dev/null -w "%{http_code}" --max-time 5 "http://$EXPECTED_IP:8084" > /dev/null 2>&1; then
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 "http://$EXPECTED_IP:8084")
    if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "301" ] || [ "$HTTP_CODE" = "302" ]; then
        echo -e "${GREEN}✅ Direct IP access working (Status: $HTTP_CODE)${NC}"
    else
        echo -e "${YELLOW}⚠️  Direct IP returned status: $HTTP_CODE${NC}"
    fi
else
    echo -e "${RED}❌ Cannot connect to http://$EXPECTED_IP:8084${NC}"
    echo "   Check if Docker containers are running: docker compose ps"
fi

echo ""
echo "=================================="
echo "Summary:"
echo ""
echo "📋 Next Steps:"
echo "1. If DNS is not configured, add A records pointing to $EXPECTED_IP"
echo "2. See DNS_SETUP_GUIDE.md for detailed instructions"
echo "3. Wait 5-60 minutes for DNS propagation"
echo "4. Run this script again to verify"
echo ""
echo "🌐 Your application is accessible at:"
echo "   - http://$EXPECTED_IP:8084 (direct IP - works now)"
echo "   - http://$DOMAIN (domain - after DNS is configured)"
echo ""

