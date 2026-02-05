#!/bin/bash

# Test script for WhatsApp webhook endpoint
# This simulates what Twilio sends when a user sends a WhatsApp message

BASE_URL="${1:-http://127.0.0.1:8000}"
ENDPOINT="$BASE_URL/api/webhook/twilio/incoming"

echo "=========================================="
echo "Testing WhatsApp Webhook Endpoint"
echo "=========================================="
echo "Endpoint: $ENDPOINT"
echo ""

# Test 1: User sends "Hi" (Welcome message)
echo "Test 1: User sends 'Hi' (Welcome message)"
echo "----------------------------------------"
curl -X POST "$ENDPOINT" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "From=whatsapp:+255767582837" \
  -d "To=whatsapp:+14155238886" \
  -d "Body=Hi" \
  -d "MessageSid=SM1234567890abcdef" \
  -w "\nHTTP Status: %{http_code}\n"
echo ""
echo ""

# Test 2: User selects language "1" (English)
echo "Test 2: User selects language '1' (English)"
echo "----------------------------------------"
curl -X POST "$ENDPOINT" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "From=whatsapp:+255767582837" \
  -d "To=whatsapp:+14155238886" \
  -d "Body=1" \
  -d "MessageSid=SM1234567890abcdef" \
  -w "\nHTTP Status: %{http_code}\n"
echo ""
echo ""

# Test 3: User selects service "1" (Cars)
echo "Test 3: User selects service '1' (Cars)"
echo "----------------------------------------"
curl -X POST "$ENDPOINT" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "From=whatsapp:+255767582837" \
  -d "To=whatsapp:+14155238886" \
  -d "Body=1" \
  -d "MessageSid=SM1234567890abcdef" \
  -w "\nHTTP Status: %{http_code}\n"
echo ""
echo ""

# Test 4: User sends "back" to return to menu
echo "Test 4: User sends 'back' to return to menu"
echo "----------------------------------------"
curl -X POST "$ENDPOINT" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "From=whatsapp:+255767582837" \
  -d "To=whatsapp:+14155238886" \
  -d "Body=back" \
  -d "MessageSid=SM1234567890abcdef" \
  -w "\nHTTP Status: %{http_code}\n"
echo ""
echo ""

echo "=========================================="
echo "Test Complete!"
echo "=========================================="
echo ""
echo "Check your Laravel logs to see the processed messages:"
echo "  tail -f storage/logs/laravel.log"
echo ""
echo "Check your queue to see if messages are being sent:"
echo "  php artisan queue:work"
echo ""

