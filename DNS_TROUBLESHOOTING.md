# DNS Troubleshooting Guide

## Current Issue: NXDOMAIN Error

You're getting `NXDOMAIN` which means "Non-Existent Domain" - the DNS server can't find your domain records.

## Possible Causes & Solutions

### 1. DNS Records Not Saved Correctly ⚠️ (Most Common)

**Check your DNS provider panel:**

The hostname format matters! Make sure you entered:

**✅ CORRECT:**
- Name/Host: `stage` (just the subdomain part)
- Type: `A`
- Value: `40.127.10.196`

**❌ WRONG:**
- Name/Host: `stage.kiboauto.co.tz` (full domain - WRONG!)
- Name/Host: `kiboauto.co.tz` (parent domain - WRONG!)

**For www subdomain:**
- Name/Host: `www.stage` (or sometimes `www.stage.kiboauto` depending on provider)
- Type: `A`
- Value: `40.127.10.196`

### 2. DNS Propagation Delay ⏰

Even with correct records, DNS can take time to propagate:

- **Minimum:** 5 minutes
- **Typical:** 15-60 minutes  
- **Maximum:** 24-48 hours (rare)

**What to do:**
- Wait 15-30 minutes
- Try testing from different locations
- Use online DNS checker: https://www.whatsmydns.net/#A/stage.kiboauto.co.tz

### 3. DNS Server Cache 🗄️

Your local DNS server (41.204.132.6) may have cached the "domain doesn't exist" response.

**Solutions:**

**macOS:**
```bash
sudo dscacheutil -flushcache
sudo killall -HUP mDNSResponder
```

**Test with different DNS server:**
```bash
# Use Google DNS
nslookup stage.kiboauto.co.tz 8.8.8.8

# Use Cloudflare DNS
nslookup stage.kiboauto.co.tz 1.1.1.1
```

### 4. Verify DNS Records Are Actually Saved ✅

**Check in your DNS provider:**

1. Log back into your DNS control panel
2. Look for the DNS records list
3. Verify you see:
   - `stage` → `40.127.10.196` (Type: A)
   - `www.stage` → `40.127.10.196` (Type: A)
4. Make sure they show as "Active" or "Enabled" (not "Pending" or "Disabled")

### 5. Test from Server Side 🔍

**On your server, run:**

```bash
# Install dig if not available
# Ubuntu/Debian: sudo apt-get install dnsutils
# CentOS/RHEL: sudo yum install bind-utils

# Test DNS resolution
dig stage.kiboauto.co.tz
dig @8.8.8.8 stage.kiboauto.co.tz

# Or use the test script
chmod +x test-dns-from-server.sh
./test-dns-from-server.sh
```

### 6. Common DNS Provider Issues

#### If using cPanel:
- Go to "Zone Editor"
- Make sure you're editing the correct zone (`kiboauto.co.tz`)
- The "Name" field should be just `stage` (not `stage.kiboauto.co.tz`)
- Click "Add Record" and save

#### If using Cloudflare:
- Make sure the proxy is OFF (gray cloud, not orange)
- DNS-only mode (not proxied)
- Records should show as "DNS only"

#### If using Namecheap:
- Go to "Advanced DNS"
- Make sure records are saved (click "Save" button)
- Check "Host Records" section

## Step-by-Step Verification

### Step 1: Verify DNS Records Format

Go back to your DNS provider and check:

```
Record 1:
Type: A
Name: stage
Value: 40.127.10.196
TTL: 3600 (or Auto)

Record 2:
Type: A  
Name: www.stage
Value: 40.127.10.196
TTL: 3600 (or Auto)
```

### Step 2: Test with Online Tools

Visit these URLs to check DNS propagation globally:

1. **What's My DNS:** https://www.whatsmydns.net/#A/stage.kiboauto.co.tz
2. **DNS Checker:** https://dnschecker.org/#A/stage.kiboauto.co.tz

If these show `40.127.10.196` in some locations but not others, DNS is propagating (just wait).

If these show nothing anywhere, DNS records may not be saved correctly.

### Step 3: Test from Different DNS Servers

```bash
# Google DNS
nslookup stage.kiboauto.co.tz 8.8.8.8

# Cloudflare DNS  
nslookup stage.kiboauto.co.tz 1.1.1.1

# OpenDNS
nslookup stage.kiboauto.co.tz 208.67.222.222
```

If any of these return `40.127.10.196`, DNS is working - your local DNS server just hasn't updated yet.

### Step 4: Clear Local DNS Cache

```bash
# macOS
sudo dscacheutil -flushcache
sudo killall -HUP mDNSResponder

# Wait 30 seconds, then test again
nslookup stage.kiboauto.co.tz
```

## Quick Diagnostic Commands

Run these to diagnose:

```bash
# 1. Check if DNS is resolving anywhere
dig @8.8.8.8 stage.kiboauto.co.tz +short

# 2. Check your local DNS
nslookup stage.kiboauto.co.tz

# 3. Check DNS propagation online
# Visit: https://www.whatsmydns.net/#A/stage.kiboauto.co.tz

# 4. Test HTTP connection (if DNS resolves)
curl -I http://stage.kiboauto.co.tz
```

## Expected Results

**✅ DNS Working:**
```
$ dig @8.8.8.8 stage.kiboauto.co.tz +short
40.127.10.196
```

**❌ DNS Not Working:**
```
$ dig @8.8.8.8 stage.kiboauto.co.tz +short
(empty - no response)
```

## If DNS Still Not Working After 1 Hour

1. **Double-check DNS records are saved:**
   - Log into DNS provider
   - Verify records exist and are active
   - Check the exact format (hostname should be `stage`, not `stage.kiboauto.co.tz`)

2. **Contact your DNS provider:**
   - Ask them to verify the records are active
   - Some providers have a "propagation" status page

3. **Try a different DNS provider:**
   - If using your domain registrar's DNS, try switching to Cloudflare (free)
   - Cloudflare DNS usually propagates faster

## Temporary Workaround

While waiting for DNS, you can:

1. **Access via IP:** `http://40.127.10.196:8084` ✅ (works now)

2. **Edit hosts file** (works on your computer only):
   ```bash
   sudo nano /etc/hosts
   # Add: 40.127.10.196 stage.kiboauto.co.tz
   ```

## Summary Checklist

- [ ] DNS records saved correctly (hostname = `stage`, not full domain)
- [ ] Records show as "Active" in DNS panel
- [ ] Waited at least 15-30 minutes
- [ ] Cleared local DNS cache
- [ ] Tested with `dig @8.8.8.8 stage.kiboauto.co.tz`
- [ ] Checked online DNS checker
- [ ] Verified records format in DNS provider

If all checked and still not working, the DNS records may not be saved correctly - double-check the format in your DNS provider's control panel.

