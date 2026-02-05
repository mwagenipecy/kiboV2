# WhatsApp Chatbot Flow Documentation

## Overview
This document explains how the WhatsApp chatbot works, including session management and conversation flow.

## Complete Flow

### 1. User Sends Message (e.g., "Hi")
```
User (WhatsApp) → Twilio → Webhook Endpoint → Laravel App
```

**Endpoint:** `POST /api/webhook/twilio/incoming`

**Twilio sends:**
- `From`: `whatsapp:+255767582837` (user's WhatsApp number)
- `To`: `whatsapp:+14155238886` (your Twilio WhatsApp number)
- `Body`: `Hi` (user's message)
- `MessageSid`: `SM1234567890abcdef` (unique message ID)

### 2. Webhook Processing

**Controller:** `TwilioWebhookController@handleIncomingMessage`

1. Logs the incoming message
2. Extracts phone number and message body
3. Calls `WhatsAppChatbotService::processMessage()`

### 3. Session Management

**Model:** `ChatbotConversation`

**Session Creation:**
- When user sends first message, a conversation record is created
- Phone number is stored (without `whatsapp:` prefix)
- Initial state: `current_step = 'welcome'`
- `last_interaction_at` is set to current time

**Session Expiration:**
- **Idle Timeout:** 30 minutes (configurable via `CHATBOT_IDLE_TIMEOUT_MINUTES`)
  - If user doesn't send a message for 30 minutes, session expires
  - Next message will reset conversation to welcome
  
- **Max Session Lifetime:** 24 hours (configurable via `CHATBOT_MAX_SESSION_LIFETIME_HOURS`)
  - Even if user is active, session resets after 24 hours

**Session Reset:**
- When session expires, conversation resets to `welcome` step
- Context is cleared
- `last_interaction_at` is updated

### 4. Conversation Flow

#### Step 1: Welcome
- **Trigger:** User sends "Hi", "Hello", "Hey", "Hujambo", "Mambo", "Habari", etc.
- **Action:** Move to `language_selection` step
- **Response:** Welcome message + Language selection menu

#### Step 2: Language Selection
- **Options:** 
  - `1` or `en` or `english` → English
  - `2` or `sw` or `swahili` or `kiswahili` → Swahili
- **Action:** Set language, move to `main_menu` step
- **Response:** Main menu with services

#### Step 3: Main Menu
- **Options:** User selects a service (1-8)
- **Services:**
  1. Cars
  2. Trucks
  3. Spare Parts
  4. Garage Services
  5. Vehicle Leasing
  6. Vehicle Financing
  7. Vehicle Valuation
  8. Sell Your Vehicle
- **Action:** Move to `service_selected` step
- **Response:** Service details + website link

#### Step 4: Service Selected
- **Options:**
  - `back` or `menu` → Return to main menu
  - `reset` or `start` → Reset to welcome
  - Any other message → Show service help
- **Response:** Service information or menu

### 5. Response Sending

**Job:** `SendWhatsAppMessage`

- Message is queued for sending
- Uses `TwilioService` to send WhatsApp message
- Message is sent asynchronously via queue

## Configuration

### Environment Variables

Add to `.env`:
```env
# Chatbot session timeout (minutes)
CHATBOT_IDLE_TIMEOUT_MINUTES=30

# Maximum session lifetime (hours)
CHATBOT_MAX_SESSION_LIFETIME_HOURS=24
```

### Config File

`config/chatbot.php` contains all chatbot settings.

## Example Conversation

```
User: Hi
Bot: 👋 Welcome to Kibo Auto! 🚗
     Please select your preferred language:
     1. English
     2. Swahili
     Please reply with the number of your choice.

User: 1
Bot: 🏠 *Main Menu* - What can we help you with today?
     1. 🚗 Cars
     2. 🚛 Trucks
     3. 🔧 Spare Parts
     4. 🛠️ Garage Services
     5. 📋 Vehicle Leasing
     6. 💰 Vehicle Financing
     7. 📊 Vehicle Valuation
     8. 💵 Sell Your Vehicle
     Please reply with the number of your choice.

User: 1
Bot: 🚗 Cars
     Browse and search through thousands of quality cars. Find new or used vehicles from trusted dealers.
     🌐 Visit our website: https://your-domain.com/cars

User: back
Bot: 🏠 *Main Menu* - What can we help you with today?
     [Menu options again...]
```

## Session Expiration Example

```
User: Hi
Bot: [Welcome message]

[30 minutes pass with no messages]

User: Hello
Bot: 👋 Welcome to Kibo Auto! 🚗
     [Session was reset due to inactivity]
```

## Database Schema

**Table:** `chatbot_conversations`

- `id`: Primary key
- `phone_number`: User's WhatsApp number (indexed)
- `language`: `en` or `sw`
- `current_step`: Current conversation step
- `context`: JSON data for conversation context
- `is_active`: Boolean flag
- `last_interaction_at`: Timestamp of last message
- `created_at`: Session creation time
- `updated_at`: Last update time

## Testing

### Test Script
```bash
./test-whatsapp-webhook.sh http://your-domain.com
```

### Manual Test
```bash
curl -X POST http://your-domain.com/api/webhook/twilio/incoming \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "From=whatsapp:+255767582837" \
  -d "To=whatsapp:+14155238886" \
  -d "Body=Hi" \
  -d "MessageSid=SM1234567890abcdef"
```

## Logging

All chatbot interactions are logged:
- Incoming messages: `storage/logs/laravel.log`
- Search for: `"Incoming WhatsApp message from Twilio"`
- Session expiration: `"Chatbot session expired, resetting conversation"`
- Response sent: `"Chatbot response sent"`

## Queue Processing

Make sure queue worker is running:
```bash
php artisan queue:work
```

Messages are sent asynchronously via the queue system.

