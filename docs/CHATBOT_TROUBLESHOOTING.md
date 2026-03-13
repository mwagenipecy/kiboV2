# WhatsApp Chatbot Not Getting Response – Troubleshooting

Use this checklist when the chatbot receives messages but does not reply (or nothing works).

---

## 1. Twilio webhook URL

**Using your real number (+255794777772)?** Set the webhook on that **registered WhatsApp sender**, not on the Sandbox. See **docs/WHATSAPP_REAL_NUMBER_SETUP.md**.

In **Twilio Console** → **Messaging** → your **WhatsApp sender** (e.g. +255794777772) or Sandbox → **When a message comes in**:

- URL must be: **`https://kiboauto.co.tz/api/webhook/twilio/incoming`**
- Method: **POST**
- The server must be reachable from the internet (no firewall blocking port 443/80, and SSL valid if using HTTPS).

Test from another machine:
```bash
curl -X POST https://kiboauto.co.tz/api/webhook/twilio/incoming \
  -d "From=whatsapp:+255712345678" \
  -d "To=whatsapp:+255794777772" \
  -d "Body=Hello"
```
You should get HTTP 200. If connection refused / timeout, fix firewall or DNS.

---

## 2. Laravel logs (does the webhook run?)

On the server:
```bash
sudo docker compose exec app tail -f storage/logs/laravel.log
```
Send a WhatsApp message to the bot, then check:

- **"Incoming WhatsApp message from Twilio"** → Twilio is reaching your app.
- **"Processing WhatsApp message"** → Request is being handled.
- **"Attempting to send WhatsApp response"** → App is trying to send the reply.
- **"WhatsApp response sent successfully"** → Reply was sent to Twilio.
- **"Failed to send WhatsApp response"** or **"Error processing WhatsApp message"** → See the `error` and `trace` in the log; that’s the cause.

If you never see "Incoming WhatsApp message from Twilio", the webhook URL is wrong or the server is not reachable (see step 1).

---

## 3. `.env` and Twilio config

- **TWILIO_WHATSAPP_FROM**  
  Use exactly (no quotes, no space at end, no invisible character):
  ```env
  TWILIO_WHATSAPP_FROM=whatsapp:+255794777772
  ```
  If you copied from somewhere, re-type the line. The code now strips invisible characters, but a clean value is best.

- **DB when using Docker**  
  Your `.env` has `DB_HOST=127.0.0.1`. In Docker, `docker-compose.yml` overrides this with `DB_HOST=db` for the `app` and `queue` containers, so the app in Docker uses the MySQL container. No change needed in `.env` for that.

- **APP_URL**  
  Set in `.env`:
  ```env
  APP_URL=https://kiboauto.co.tz
  ```
  (or `http://` if you don’t use SSL yet.)

After changing `.env`:
```bash
sudo docker compose exec app php artisan config:clear
sudo docker compose restart app nginx queue
```

---

## 4. Twilio “from” number (WhatsApp sender)

The number in `TWILIO_WHATSAPP_FROM` (+255794777772) must be:

- Added in Twilio as a **WhatsApp sender** (Messaging → Try it out / Sender configuration).
- Approved and enabled for WhatsApp.

If the number is not valid for WhatsApp in Twilio, the API may accept the request but delivery fails (check Twilio message log for error code).

---

## 5. Database and Redis (Docker)

Chatbot uses DB (conversations, state) and may use cache. In Docker:

- **DB**: `docker-compose` sets `DB_HOST=db` for the app; MySQL container must be healthy.
- **Redis**: `.env` has `REDIS_HOST=redis`; `docker-compose` passes it. Redis is used if `CACHE_STORE=redis` or queue uses Redis.

Check:
```bash
sudo docker compose exec app php artisan db:show
sudo docker compose ps
```
If `db:show` fails, fix DB. If Redis is used and connection fails, fix Redis.

---

## 6. Quick checks on server

```bash
# App container can reach DB and Redis
sudo docker compose exec app php artisan db:show
sudo docker compose exec app php artisan tinker --execute="echo Illuminate\Support\Facades\Redis::connection()->ping();"

# Config and routes
sudo docker compose exec app php artisan config:clear
sudo docker compose exec app php artisan route:list | grep twilio
```

You should see the `twilio.webhook.incoming` route.

---

## Summary

| Symptom | What to check |
|--------|----------------|
| No log line “Incoming WhatsApp message from Twilio” | Webhook URL in Twilio, firewall, DNS, SSL. |
| “Incoming” appears, then “Error processing” or exception | Laravel log `error` and `trace`; often DB or code bug. |
| “Attempting to send” then “Failed to send” | Twilio credentials, `TWILIO_WHATSAPP_FROM`, and that the number is a valid WhatsApp sender in Twilio. |
| “No response message generated” | Chatbot logic (e.g. state, conversation) not producing a reply; check `WhatsAppChatbotService` and DB. |

After any change to `.env` or code, run:
`sudo docker compose exec app php artisan config:clear`
and restart: `sudo docker compose restart app queue`.
