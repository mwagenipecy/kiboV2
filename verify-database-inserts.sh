#!/bin/bash

# Script to verify what gets inserted into database when webhook is received

echo "=========================================="
echo "Database Insert Verification"
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
else
    echo "Using local PHP..."
    PHP_CMD="php artisan"
fi

echo ""
echo "1. Checking chatbot_conversations table..."
echo "------------------------------------------"
$PHP_CMD tinker --execute="
\$conversations = \App\Models\ChatbotConversation::orderBy('created_at', 'desc')->take(5)->get();
echo 'Recent conversations:' . PHP_EOL;
foreach (\$conversations as \$conv) {
    echo '  - Phone: ' . \$conv->phone_number . PHP_EOL;
    echo '    Step: ' . \$conv->current_step . PHP_EOL;
    echo '    Language: ' . \$conv->language . PHP_EOL;
    echo '    Last Interaction: ' . \$conv->last_interaction_at . PHP_EOL;
    echo '    Created: ' . \$conv->created_at . PHP_EOL;
    echo '';
}
"

echo ""
echo "2. Checking jobs table (queue jobs)..."
echo "--------------------------------------"
$PHP_CMD tinker --execute="
\$jobsCount = \DB::table('jobs')->count();
echo 'Total jobs in queue: ' . \$jobsCount . PHP_EOL;
if (\$jobsCount > 0) {
    \$recentJobs = \DB::table('jobs')->orderBy('created_at', 'desc')->take(3)->get();
    echo 'Recent jobs:' . PHP_EOL;
    foreach (\$recentJobs as \$job) {
        echo '  - Queue: ' . \$job->queue . PHP_EOL;
        echo '    Attempts: ' . \$job->attempts . PHP_EOL;
        echo '    Created: ' . \$job->created_at . PHP_EOL;
        echo '';
    }
} else {
    echo '  No jobs in queue (all processed or using sync mode)' . PHP_EOL;
}
"

echo ""
echo "3. Checking failed jobs..."
echo "--------------------------"
$PHP_CMD tinker --execute="
\$failedCount = \DB::table('failed_jobs')->count();
echo 'Failed jobs: ' . \$failedCount . PHP_EOL;
"

echo ""
echo "4. Checking logs for recent activity..."
echo "----------------------------------------"
echo "Recent log entries (last 5 lines with 'Incoming WhatsApp' or 'Chatbot'):"
if [ -f "storage/logs/laravel.log" ]; then
    tail -100 storage/logs/laravel.log | grep -E "Incoming WhatsApp|Chatbot" | tail -5
else
    echo "  Log file not found (check Docker logs)"
fi

echo ""
echo "=========================================="
echo "Summary:"
echo "=========================================="
echo "When you send a webhook request, the following happens:"
echo ""
echo "1. ✅ Creates/Updates record in 'chatbot_conversations' table"
echo "   - phone_number: +255767582837"
echo "   - current_step: 'welcome' (or current step)"
echo "   - language: 'en' (or selected language)"
echo "   - last_interaction_at: current timestamp"
echo "   - is_active: true"
echo ""
echo "2. ✅ Creates job in 'jobs' table (if using queue)"
echo "   - Job type: SendWhatsAppMessage"
echo "   - Contains: phone number and message to send"
echo ""
echo "3. ✅ Logs entry in storage/logs/laravel.log"
echo "   - 'Incoming WhatsApp message from Twilio'"
echo "   - 'Chatbot response queued' or 'Chatbot response sent synchronously'"
echo ""
echo "4. ✅ Queue worker processes job and sends message via Twilio"
echo ""
echo "=========================================="

