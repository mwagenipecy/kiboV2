# DNS Error Fix - ERR_NAME_NOT_RESOLVED

## The Problem
You're seeing: **ERR_NAME_NOT_RESOLVED** for `stage.kiboauto.co.tz`

**This is NOT a server problem** - your server is working fine!  
**This IS a DNS problem** - the domain doesn't know where to find your server yet.

## Quick Explanation

Think of DNS like a phone book:
- Your server IP `40.127.10.196` = Your phone number
- Your domain `stage.kiboauto.co.tz` = A name in the phone book
- **Right now:** The phone book doesn't have your name/number listed
- **Solution:** Add your name and number to the phone book (DNS records)

## What You Need to Do

### Step 1: Find Your Domain Registrar

You need to log in to where you registered/manage `kiboauto.co.tz`. This could be:
- Your hosting provider's control panel
- Domain registrar (like Namecheap, GoDaddy, etc.)
- cPanel if you have hosting
- Your local Tanzania domain registrar

**How to find it:**
- Check your email for domain registration/purchase confirmation
- Check who you paid for the domain
- Ask your IT team/web developer who manages the domain

### Step 2: Add DNS A Records

Once you're logged in, you need to add **TWO A records**:

**Record 1:**
- **Type:** A
- **Name/Host:** `stage` (or `stage.kiboauto`)
- **Value/Points To:** `40.127.10.196`
- **TTL:** `3600` (or Auto)

**Record 2:**
- **Type:** A
- **Name/Host:** `www.stage` (or `www.stage.kiboauto`)
- **Value/Points To:** `40.127.10.196`
- **TTL:** `3600` (or Auto)

### Step 3: Wait for DNS Propagation

After adding the records:
- **Minimum wait:** 5 minutes
- **Typical wait:** 15-60 minutes
- **Maximum wait:** 24-48 hours (rare)

### Step 4: Verify It's Working

**Option A: From your computer**
```bash
# Windows PowerShell or Command Prompt
nslookup stage.kiboauto.co.tz

# macOS/Linux Terminal
nslookup stage.kiboauto.co.tz
# or
dig stage.kiboauto.co.tz
```

**Option B: Online tool**
Visit: https://www.whatsmydns.net/#A/stage.kiboauto.co.tz

**Option C: From your server**
```bash
cd /path/to/your/project
chmod +x verify-dns.sh
./verify-dns.sh
```

## Current Status

✅ **Your server is working:** `http://40.127.10.196:8084`  
✅ **Nginx is configured correctly**  
❌ **DNS is not configured yet** ← This is what you need to fix

## Testing While DNS Propagates

**Until DNS is configured, you can:**

1. **Access via IP:** `http://40.127.10.196:8084` ✅ (works now)

2. **Test locally on your computer** (temporary workaround):
   
   **Windows:**
   - Open Notepad as Administrator
   - Open file: `C:\Windows\System32\drivers\etc\hosts`
   - Add this line: `40.127.10.196 stage.kiboauto.co.tz`
   - Save and close
   - Now `http://stage.kiboauto.co.tz` will work on YOUR computer only
   
   **macOS/Linux:**
   ```bash
   sudo nano /etc/hosts
   # Add this line:
   40.127.10.196 stage.kiboauto.co.tz
   # Save and exit (Ctrl+X, then Y, then Enter)
   ```

## Common Questions

**Q: Can I fix this from the server?**  
A: No, DNS is managed by your domain registrar, not your server.

**Q: How do I know who manages my domain?**  
A: Check your email for domain purchase/registration emails, or use: https://whois.net/kiboauto.co.tz

**Q: What if I don't have access to DNS?**  
A: Contact whoever manages your domain (IT team, web developer, hosting provider).

**Q: Will the IP address still work after DNS is configured?**  
A: Yes! `http://40.127.10.196:8084` will continue to work.

**Q: How long does DNS take?**  
A: Usually 5-60 minutes, but can take up to 48 hours.

## Step-by-Step for Common Providers

### If using cPanel:
1. Log in to cPanel
2. Find "Zone Editor" or "DNS Zone Editor"
3. Click on `kiboauto.co.tz`
4. Click "Add Record"
5. Fill in:
   - Name: `stage`
   - Type: `A`
   - Address: `40.127.10.196`
   - TTL: `3600`
6. Click "Add Record" again
7. Fill in:
   - Name: `www.stage`
   - Type: `A`
   - Address: `40.127.10.196`
   - TTL: `3600`

### If using Cloudflare:
1. Log in to Cloudflare
2. Select domain `kiboauto.co.tz`
3. Go to "DNS" → "Records"
4. Click "Add record"
5. Type: `A`, Name: `stage`, IPv4: `40.127.10.196`, Proxy: OFF (gray cloud)
6. Click "Add record" again
7. Type: `A`, Name: `www.stage`, IPv4: `40.127.10.196`, Proxy: OFF

### If using Namecheap:
1. Log in to Namecheap
2. Go to "Domain List"
3. Click "Manage" next to `kiboauto.co.tz`
4. Go to "Advanced DNS"
5. Click "Add New Record"
6. Type: `A Record`, Host: `stage`, Value: `40.127.10.196`, TTL: Automatic
7. Click "Add New Record" again
8. Type: `A Record`, Host: `www.stage`, Value: `40.127.10.196`, TTL: Automatic

## Verification Checklist

After adding DNS records, check:

- [ ] DNS records added at domain registrar
- [ ] Waited at least 5 minutes
- [ ] Ran `nslookup stage.kiboauto.co.tz` - shows `40.127.10.196`
- [ ] Can access `http://stage.kiboauto.co.tz` in browser
- [ ] Can access `http://www.stage.kiboauto.co.tz` (redirects to non-www)

## Still Having Issues?

If DNS is configured but still not working:

1. **Clear DNS cache on your computer:**
   ```bash
   # Windows
   ipconfig /flushdns
   
   # macOS
   sudo dscacheutil -flushcache
   sudo killall -HUP mDNSResponder
   
   # Linux
   sudo systemd-resolve --flush-caches
   ```

2. **Check firewall on server:**
   ```bash
   # Make sure ports 80 and 8084 are open
   sudo ufw status
   # or
   sudo iptables -L
   ```

3. **Verify nginx is running:**
   ```bash
   docker compose ps
   docker compose logs nginx
   ```

4. **Test from server directly:**
   ```bash
   curl -I http://stage.kiboauto.co.tz
   ```

## Summary

**The error ERR_NAME_NOT_RESOLVED means:**
- Your domain doesn't know where your server is
- You need to add DNS A records at your domain registrar
- This is NOT something you can fix from your server
- Your server is working fine - it's just DNS that needs configuration

**Action Required:**
1. Log in to domain registrar
2. Add A records for `stage` and `www.stage` → `40.127.10.196`
3. Wait 5-60 minutes
4. Test domain access

