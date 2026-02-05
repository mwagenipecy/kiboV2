#!/bin/bash

# Test script to verify webhook endpoint is working
# The empty response is EXPECTED - webhooks return empty 200 OK

BASE_URL="${1:-http://40.127.10.196:8084}"
ENDPOINT="$BASE_URL/api/webhook/twilio/incoming"

echo "=========================================="
echo "Testing WhatsApp Webhook Endpoint"
echo "=========================================="
echo "Endpoint: $ENDPOINT"
echo ""
echo "Note: Empty response is EXPECTED and CORRECT!"
echo "Webhooks return empty 200 OK to acknowledge receipt."
echo ""

# Test with verbose output to see HTTP status
echo "Sending test message..."
echo ""

HTTP_CODE=$(curl -X POST "$ENDPOINT" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "From=whatsapp:+255767582837" \
  -d "To=whatsapp:+14155238886" \
  -d "Body=Hi" \
  -d "MessageSid=SM1234567890abcdef" \
  -w "\nHTTP_CODE:%{http_code}\n" \
  -s -o /dev/null)

echo "HTTP Status Code: $HTTP_CODE"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ SUCCESS! Endpoint is working correctly."
    echo "   Empty response is the expected behavior for webhooks."
    echo ""
    echo "To verify the message was processed, check the Laravel logs:"
    echo "   tail -f storage/logs/laravel.log | grep 'Incoming WhatsApp message'"
else
    echo "❌ ERROR! Expected HTTP 200, got $HTTP_CODE"
    echo "   Check server logs for details."
fi

echo ""
echo "=========================================="

