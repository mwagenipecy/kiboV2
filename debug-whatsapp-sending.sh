#!/bin/bash

# Debug script to check why WhatsApp messages aren't being sent

echo "=========================================="
echo "WhatsApp Message Sending Debug"
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
    echo "Using Docker Compose..."
    PHP_CMD="$DOCKER_COMPOSE exec -T app php artisan"
    EXEC_CMD="$DOCKER_COMPOSE exec -T app"
else
    echo "Using local PHP..."
    PHP_CMD="php artisan"
    EXEC_CMD=""
fi

echo ""
echo "1. Checking Twilio Configuration..."
echo "-----------------------------------"
$PHP_CMD tinker --execute="
\$sid = config('services.twilio.sid');
\$token = config('services.twilio.token');
\$from = config('services.twilio.whatsapp_from');

echo 'Twilio Account SID: ' . (\$sid ? '✅ Set (' . substr(\$sid, 0, 10) . '...)' : '❌ NOT SET') . PHP_EOL;
echo 'Twilio Auth Token: ' . (\$token ? '✅ Set (' . substr(\$token, 0, 10) . '...)' : '❌ NOT SET') . PHP_EOL;
echo 'WhatsApp From: ' . (\$from ? '✅ Set (' . \$from . ')' : '❌ NOT SET') . PHP_EOL;

if (!\$sid || !\$token || !\$from) {
    echo PHP_EOL . '❌ ERROR: Twilio credentials are missing!' . PHP_EOL;
    echo 'Please check your .env file for:' . PHP_EOL;
    echo '  - TWILIO_ACCOUNT_SID' . PHP_EOL;
    echo '  - TWILIO_AUTH_TOKEN' . PHP_EOL;
    echo '  - TWILIO_WHATSAPP_FROM' . PHP_EOL;
}
"

echo ""
echo "2. Checking Chatbot Configuration..."
echo "------------------------------------"
$PHP_CMD tinker --execute="
\$sendSync = config('chatbot.send_sync', false);
\$queueConnection = config('queue.default', 'database');

echo 'Send Sync Mode: ' . (\$sendSync ? '✅ ENABLED (sends immediately)' : '❌ DISABLED (uses queue)') . PHP_EOL;
echo 'Queue Connection: ' . \$queueConnection . PHP_EOL;

if (!\$sendSync) {
    echo PHP_EOL . '⚠️  Queue mode is enabled. Make sure queue worker is running!' . PHP_EOL;
}
"

echo ""
echo "3. Checking Queue Worker Status..."
echo "----------------------------------"
if [ -n "$DOCKER_COMPOSE" ]; then
    if $DOCKER_COMPOSE ps queue 2>/dev/null | grep -q "Up"; then
        echo "✅ Queue worker container is running"
        echo ""
        echo "Recent queue worker logs:"
        $DOCKER_COMPOSE logs --tail=10 queue 2>/dev/null || echo "  No logs available"
    else
        echo "❌ Queue worker container is NOT running"
        echo ""
        echo "Start it with: docker-compose up -d queue"
    fi
else
    echo "⚠️  Not using Docker - check queue worker manually: php artisan queue:work"
fi

echo ""
echo "4. Checking Queue Jobs..."
echo "-------------------------"
$PHP_CMD tinker --execute="
\$jobsCount = \DB::table('jobs')->count();
\$failedCount = \DB::table('failed_jobs')->count();

echo 'Jobs in queue: ' . \$jobsCount . PHP_EOL;
echo 'Failed jobs: ' . \$failedCount . PHP_EOL;

if (\$jobsCount > 0) {
    echo PHP_EOL . '⚠️  There are ' . \$jobsCount . ' jobs waiting in queue!' . PHP_EOL;
    echo 'Make sure queue worker is running to process them.' . PHP_EOL;
    
    \$oldestJob = \DB::table('jobs')->orderBy('created_at', 'asc')->first();
    if (\$oldestJob) {
        \$age = \Carbon\Carbon::parse(\$oldestJob->created_at)->diffForHumans();
        echo 'Oldest job age: ' . \$age . PHP_EOL;
    }
}

if (\$failedCount > 0) {
    echo PHP_EOL . '❌ There are ' . \$failedCount . ' failed jobs!' . PHP_EOL;
    echo 'Check failed jobs with: php artisan queue:failed' . PHP_EOL;
}
"

echo ""
echo "5. Checking Recent Logs..."
echo "--------------------------"
if [ -f "storage/logs/laravel.log" ]; then
    echo "Recent WhatsApp-related log entries:"
    tail -50 storage/logs/laravel.log | grep -E "WhatsApp|Chatbot|Twilio|queue" | tail -10 || echo "  No recent WhatsApp logs found"
else
    echo "⚠️  Log file not found. Check Docker logs:"
    if [ -n "$DOCKER_COMPOSE" ]; then
        $DOCKER_COMPOSE logs --tail=20 app | grep -E "WhatsApp|Chatbot|Twilio|queue" || echo "  No logs found"
    fi
fi

echo ""
echo "6. Testing Twilio Connection..."
echo "-------------------------------"
$PHP_CMD tinker --execute="
try {
    \$twilioService = app(\App\Services\TwilioService::class);
    echo '✅ TwilioService initialized successfully' . PHP_EOL;
} catch (\Exception \$e) {
    echo '❌ ERROR: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "7. Checking Chatbot Conversations..."
echo "------------------------------------"
$PHP_CMD tinker --execute="
\$conversations = \App\Models\ChatbotConversation::orderBy('last_interaction_at', 'desc')->take(3)->get();
echo 'Recent conversations: ' . \$conversations->count() . PHP_EOL;
foreach (\$conversations as \$conv) {
    echo '  - Phone: ' . \$conv->phone_number . ' | Step: ' . \$conv->current_step . ' | Last: ' . \$conv->last_interaction_at . PHP_EOL;
}
"

echo ""
echo "=========================================="
echo "Troubleshooting Steps:"
echo "=========================================="
echo ""
echo "1. If Twilio credentials are missing:"
echo "   - Add to .env:"
echo "     TWILIO_ACCOUNT_SID=your_sid"
echo "     TWILIO_AUTH_TOKEN=your_token"
echo "     TWILIO_WHATSAPP_FROM=whatsapp:+14155238886"
echo ""
echo "2. If queue worker is not running:"
echo "   - Start it: docker-compose up -d queue"
echo "   - Or enable sync mode: CHATBOT_SEND_SYNC=true in .env"
echo ""
echo "3. If jobs are stuck in queue:"
echo "   - Check queue worker: docker-compose logs -f queue"
echo "   - Process manually: docker-compose exec app php artisan queue:work --once"
echo ""
echo "4. If there are failed jobs:"
echo "   - Check: docker-compose exec app php artisan queue:failed"
echo "   - Retry: docker-compose exec app php artisan queue:retry all"
echo ""
echo "5. For immediate testing (sync mode):"
echo "   - Add to .env: CHATBOT_SEND_SYNC=true"
echo "   - Restart: docker-compose restart app"
echo "   - Test again"
echo ""
echo "=========================================="

