# DNS Configuration Guide

## Problem
You're getting the error: **ERR_NAME_NOT_RESOLVED** when accessing `stage.kiboauto.co.tz`

This means the domain is not pointing to your server IP address yet.

## Solution: Configure DNS Records

You need to add DNS A records to point your domain to your server IP.

### Server Information
- **Server IP**: `40.127.10.196`
- **Domain**: `stage.kiboauto.co.tz`
- **WWW Domain**: `www.stage.kiboauto.co.tz`

### Steps to Configure DNS

1. **Log in to your Domain Registrar/DNS Provider**
   - This is usually where you purchased the domain `kiboauto.co.tz`
   - Common providers: Namecheap, GoDaddy, Cloudflare, etc.
   - Or your local Tanzania domain registrar

2. **Navigate to DNS Management**
   - Look for "DNS Settings", "DNS Management", or "DNS Records"
   - You should see options to add/edit DNS records

3. **Add A Records**
   Add these two A records:

   | Type | Name/Host | Value/Points To | TTL |
   |------|-----------|----------------|-----|
   | A    | stage     | 40.127.10.196  | 3600 (or Auto) |
   | A    | www.stage | 40.127.10.196  | 3600 (or Auto) |

   **OR** if using subdomain notation:
   
   | Type | Name/Host | Value/Points To | TTL |
   |------|-----------|----------------|-----|
   | A    | stage.kiboauto | 40.127.10.196 | 3600 (or Auto) |
   | A    | www.stage.kiboauto | 40.127.10.196 | 3600 (or Auto) |

4. **Save the Changes**
   - DNS changes can take anywhere from a few minutes to 48 hours to propagate
   - Usually it takes 5-60 minutes
   - You can check propagation at: https://www.whatsmydns.net

### Verify DNS Configuration

After adding the DNS records, verify they're working:

**Option 1: Using Command Line**
```bash
# Check if domain resolves to correct IP
nslookup stage.kiboauto.co.tz

# Should return: 40.127.10.196

# Or using dig
dig stage.kiboauto.co.tz

# Should show A record pointing to 40.127.10.196
```

**Option 2: Using Online Tools**
- Visit: https://www.whatsmydns.net/#A/stage.kiboauto.co.tz
- Enter: `stage.kiboauto.co.tz`
- It should show `40.127.10.196` globally

**Option 3: Test from Browser**
Once DNS is configured, you can access:
- http://stage.kiboauto.co.tz
- http://www.stage.kiboauto.co.tz (redirects to non-www)

### Current Status

**Until DNS is configured:**
- ✅ Your application is still accessible via IP: `http://40.127.10.196:8084`
- ❌ Domain `stage.kiboauto.co.tz` will not work (ERR_NAME_NOT_RESOLVED)

**After DNS is configured:**
- ✅ Domain will work: `http://stage.kiboauto.co.tz` (port 80)
- ✅ Domain will work: `http://stage.kiboauto.co.tz:8084` (port 8084)
- ✅ IP will still work: `http://40.127.10.196:8084`
- ✅ WWW redirects to non-www

### Common DNS Providers Instructions

#### If using Cloudflare:
1. Log in to Cloudflare
2. Select your domain `kiboauto.co.tz`
3. Go to "DNS" section
4. Click "Add record"
5. Type: A, Name: stage, IPv4: 40.127.10.196, Proxy: DNS only (gray cloud)
6. Click "Add record" again
7. Type: A, Name: www.stage, IPv4: 40.127.10.196, Proxy: DNS only (gray cloud)

#### If using cPanel:
1. Log in to cPanel
2. Go to "Zone Editor" or "Advanced DNS Zone Editor"
3. Select domain `kiboauto.co.tz`
4. Add A record:
   - Name: `stage`
   - TTL: `3600`
   - Address: `40.127.10.196`
5. Add another A record:
   - Name: `www.stage`
   - TTL: `3600`
   - Address: `40.127.10.196`

#### If using your local Tanzania domain registrar:
Contact your domain registrar or hosting provider and ask them to add:
- A record for `stage.kiboauto.co.tz` → `40.127.10.196`
- A record for `www.stage.kiboauto.co.tz` → `40.127.10.196`

### Troubleshooting

**If DNS still not working after 24 hours:**
1. Double-check the A records are correct
2. Ensure TTL is not too high (3600 seconds is good)
3. Clear your local DNS cache:
   ```bash
   # Windows
   ipconfig /flushdns
   
   # macOS
   sudo dscacheutil -flushcache; sudo killall -HUP mDNSResponder
   
   # Linux
   sudo systemd-resolve --flush-caches
   ```

**If domain resolves but site doesn't load:**
1. Check nginx is running: `docker compose ps`
2. Check nginx logs: `docker compose logs nginx`
3. Verify firewall allows port 80 and 8084
4. Test IP access first: `http://40.127.10.196:8084`

### Testing After DNS Configuration

Once DNS is configured and propagated:

1. **Test domain access:**
   ```bash
   curl -I http://stage.kiboauto.co.tz
   # Should return HTTP 200 or 301/302
   ```

2. **Verify in browser:**
   - Visit: http://stage.kiboauto.co.tz
   - Should load your Laravel application

3. **Check www redirect:**
   - Visit: http://www.stage.kiboauto.co.tz
   - Should redirect to: http://stage.kiboauto.co.tz

### Next Steps After DNS Works

Once DNS is working and domain is accessible:

1. **Update .env file** (if not already done):
   ```bash
   APP_URL=http://stage.kiboauto.co.tz
   ```

2. **Restart containers to ensure all configs are loaded:**
   ```bash
   docker compose restart nginx app
   ```

3. **Set up SSL/HTTPS** (recommended for production):
   - Install Let's Encrypt certificate
   - Update nginx config for HTTPS
   - Update APP_URL to `https://stage.kiboauto.co.tz`

### Summary

**Current Setup:**
- Application runs on: `40.127.10.196:8084` ✅
- Nginx configured to handle domain ✅
- **DNS not configured yet** ❌ (this is what you need to do)

**What You Need to Do:**
1. Add DNS A records pointing `stage.kiboauto.co.tz` to `40.127.10.196`
2. Wait for DNS propagation (5-60 minutes usually)
3. Test domain access
4. Once working, optionally set up SSL/HTTPS

