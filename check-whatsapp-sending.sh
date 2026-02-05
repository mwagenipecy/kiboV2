#!/bin/bash

# Script to check why WhatsApp replies aren't being sent

echo "=========================================="
echo "WhatsApp Reply Debugging"
echo "=========================================="
echo ""

# Check if we're in Docker or local
if command -v docker-compose &> /dev/null; then
    DOCKER_COMPOSE="docker-compose"
elif docker compose version &> /dev/null 2>&1; then
    DOCKER_COMPOSE="docker compose"
else
    DOCKER_COMPOSE=""
fi

if [ -n "$DOCKER_COMPOSE" ]; then
    PHP_CMD="$DOCKER_COMPOSE exec -T app php artisan"
    EXEC_CMD="$DOCKER_COMPOSE exec -T app"
else
    PHP_CMD="php artisan"
    EXEC_CMD=""
fi

echo "1. Checking Twilio Configuration..."
echo "-----------------------------------"
$PHP_CMD tinker --execute="
\$sid = config('services.twilio.sid');
\$token = config('services.twilio.token');
\$from = config('services.twilio.whatsapp_from');

echo 'Twilio Account SID: ' . (\$sid ? '✅ Set' : '❌ NOT SET') . PHP_EOL;
echo 'Twilio Auth Token: ' . (\$token ? '✅ Set' : '❌ NOT SET') . PHP_EOL;
echo 'WhatsApp From: ' . (\$from ? '✅ Set (' . \$from . ')' : '❌ NOT SET') . PHP_EOL;
"

echo ""
echo "2. Checking Recent Logs for Errors..."
echo "-------------------------------------"
if [ -f "storage/logs/laravel.log" ]; then
    echo "Recent WhatsApp-related logs (last 20 lines):"
    tail -100 storage/logs/laravel.log | grep -E "WhatsApp|Twilio|Chatbot|Failed|Error" | tail -20
else
    if [ -n "$DOCKER_COMPOSE" ]; then
        echo "Checking Docker logs..."
        $DOCKER_COMPOSE logs --tail=50 app | grep -E "WhatsApp|Twilio|Chatbot|Failed|Error" | tail -20
    fi
fi

echo ""
echo "3. Testing TwilioService..."
echo "---------------------------"
$PHP_CMD tinker --execute="
try {
    \$service = app(\App\Services\TwilioService::class);
    echo '✅ TwilioService can be instantiated' . PHP_EOL;
    
    \$from = config('services.twilio.whatsapp_from');
    echo 'From number: ' . \$from . PHP_EOL;
} catch (\Exception \$e) {
    echo '❌ ERROR: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "4. Checking if responses are being generated..."
echo "-----------------------------------------------"
$PHP_CMD tinker --execute="
\$conversations = \App\Models\ChatbotConversation::orderBy('last_interaction_at', 'desc')->take(3)->get();
echo 'Recent conversations:' . PHP_EOL;
foreach (\$conversations as \$conv) {
    echo '  Phone: ' . \$conv->phone_number . PHP_EOL;
    echo '  Step: ' . \$conv->current_step . PHP_EOL;
    echo '  Last interaction: ' . \$conv->last_interaction_at . PHP_EOL;
    echo '';
}
"

echo ""
echo "5. Checking for failed Twilio calls..."
echo "---------------------------------------"
if [ -f "storage/logs/laravel.log" ]; then
    echo "Failed Twilio calls:"
    tail -200 storage/logs/laravel.log | grep -i "failed.*whatsapp\|twilio.*error\|twilio.*exception" | tail -10
fi

echo ""
echo "=========================================="
echo "Common Issues to Check:"
echo "=========================================="
echo ""
echo "1. Twilio Credentials:"
echo "   - Check .env has: TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN, TWILIO_WHATSAPP_FROM"
echo "   - Verify credentials in Twilio Console"
echo ""
echo "2. Phone Number Format:"
echo "   - Must be: whatsapp:+255767582837 (with country code)"
echo "   - Check if number is verified in Twilio (for trial accounts)"
echo ""
echo "3. Check Logs:"
echo "   - Look for 'Failed to send WhatsApp response' errors"
echo "   - Check for Twilio API errors"
echo ""
echo "4. Test Manually:"
echo "   docker-compose exec app php artisan tinker"
echo "   >>> \$service = app(\App\Services\TwilioService::class);"
echo "   >>> \$service->sendWhatsAppMessage('+255767582837', 'Test message');"
echo ""
echo "=========================================="

