# Use Your Real WhatsApp Number (+255794777772) – Not Sandbox

You want: **when someone sends a WhatsApp message TO +255794777772, they get the chatbot reply FROM +255794777772.**

The sandbox uses a different number (e.g. +1 415 523 8886) and "join &lt;code&gt;". For your real number, do the following.

---

## 1. Twilio: Webhook on the **real** number (not sandbox)

The webhook must be set on your **registered WhatsApp sender** +255794777772, so that messages **to** that number trigger your app.

1. Log in to **Twilio Console**: https://console.twilio.com  
2. Go to **Messaging** → **Try it out** → **Send a WhatsApp message** (or **Messaging** → **Settings** → **WhatsApp senders** / **Sender configurations**).  
3. Find your **registered WhatsApp sender** with number **+255794777772** (not the "Sandbox" sender).  
4. Open that sender’s settings and set:
   - **When a message comes in**: `https://kiboauto.co.tz/api/webhook/twilio/incoming`
   - **HTTP method**: **POST**
5. Save.

If your UI is different, look for:
- **Manage** → **Sender configurations** → select the sender for +255794777772, or  
- **Phone Numbers** → **Manage** → **Active numbers** and see if the WhatsApp sender is linked there.

Result: any message sent **to** +255794777772 will trigger a POST to your Laravel app, and your app will reply using the same number (see step 2).

---

## 2. Your app: send replies FROM +255794777772

Your `.env` must use the **same** number as the Twilio sender:

```env
TWILIO_WHATSAPP_FROM=whatsapp:+255794777772
```

No spaces, no quotes, no extra characters. Then:

```bash
sudo docker compose exec app php artisan config:clear
sudo docker compose restart app queue
```

The app already uses `TWILIO_WHATSAPP_FROM` for the "from" address when sending; with this value, replies will go out **from** +255794777772.

---

## 3. Stop using the sandbox for this bot

- Do **not** give users the sandbox number or "join &lt;code&gt;" for this flow.  
- Tell users to message **+255794777772** directly.  
- You can leave the sandbox configured for testing other things, but for this chatbot only the real number (+255794777772) should receive messages and send replies.

---

## 4. Check that it works

1. From a **different** phone (not the one that owns +255794777772), open WhatsApp and send a message **to** +255794777772 (e.g. "Hi").  
2. You should get an automated reply **from** +255794777772.  
3. On the server, confirm the webhook is hit:
   ```bash
   sudo docker compose exec app tail -f storage/logs/laravel.log
   ```
   You should see lines like "Incoming WhatsApp message from Twilio" and "WhatsApp response sent successfully".

If replies still come from the sandbox number, the webhook is still set on the sandbox in Twilio; switch it to the sender for +255794777772 as in step 1.
