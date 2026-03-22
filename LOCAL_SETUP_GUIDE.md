# 🚀 Local Development Setup Guide - UmkmAI

Panduan lengkap untuk menjalankan UmkmAI di local development environment.

---

## 📋 TABLE OF CONTENTS

1. [Prerequisites](#prerequisites)
2. [Environment Setup](#environment-setup)
3. [API Credentials Tutorial](#api-credentials-tutorial)
4. [Running the Application](#running-the-application)
5. [Testing Webhooks](#testing-webhooks)
6. [Troubleshooting](#troubleshooting)

---

## 🔧 PREREQUISITES

### Required Software:

| Software | Version | Link |
|----------|---------|------|
| **PHP** | 8.3+ | https://windows.php.net/download/ (Windows) / `brew install php` (Mac) |
| **Composer** | 2.x | https://getcomposer.org/download/ |
| **Node.js** | 20+ | https://nodejs.org/ |
| **npm** | 10+ | (bundled with Node.js) |
| **MySQL** | 8.0+ | https://dev.mysql.com/downloads/mysql/ (atau XAMPP) |
| **Redis** | 7+ | https://redis.io/download (atau Docker) |

### Optional (Recommended):

- **Git** — Untuk version control
- **Docker Desktop** — Jika ingin gunakan Docker untuk Redis
- **Postman** — Untuk testing webhook endpoints
- **ngrok** — Untuk tunneling webhook ke local (https://ngrok.com)

---

## ⚙️ ENVIRONMENT SETUP

### Step 1: Install PHP 8.3+

#### Windows:
1. Download PHP 8.3+ dari https://windows.php.net/download/
2. Extract ke `C:\php`
3. Add `C:\php` ke System Environment Variables PATH
4. Restart terminal/command prompt

#### Verify:
```bash
php --version
# Output: PHP 8.3.x (cli)
```

### Step 2: Install Composer

#### Windows:
1. Download Composer-Setup.exe dari https://getcomposer.org/download/
2. Run installer — akan auto-detect PHP installation
3. Verify:
```bash
composer --version
# Output: Composer version 2.x.x
```

### Step 3: Install Node.js & npm

1. Download LTS version dari https://nodejs.org/
2. Run installer
3. Verify:
```bash
node --version
# Output: v20.x.x

npm --version
# Output: 10.x.x
```

### Step 4: Install MySQL

#### Option A: XAMPP (Easiest for Windows)
1. Download XAMPP dari https://www.apachefriends.org/
2. Install XAMPP
3. Start MySQL dari XAMPP Control Panel
4. Default credentials:
   - Username: `root`
   - Password: (kosong/tidak ada password)
   - Port: `3306`

#### Option B: MySQL Standalone
1. Download MySQL Installer dari https://dev.mysql.com/downloads/installer/
2. Install MySQL Server 8.0+
3. Set root password (ingat password ini!)
4. Verify:
```bash
mysql --version
# Output: mysql  Ver 8.0.x
```

### Step 5: Install Redis

#### Option A: Docker (Recommended)
```bash
docker run -d -p 6379:6379 redis:7-alpine
```

#### Option B: Windows Native
1. Download Redis untuk Windows dari https://github.com/microsoftarchive/redis/releases
2. Extract dan jalankan `redis-server.exe`
3. Verify:
```bash
redis-cli ping
# Output: PONG
```

---

## 🔑 API CREDENTIALS TUTORIAL

Berikut tutorial lengkap cara mendapatkan semua API credentials yang dibutuhkan.

---

### 1️⃣ ANTHROPIC API KEY (CLAUDE AI) — WAJIB

Dibutuhkan untuk fitur AI auto-reply.

#### Langkah-langkah:

1. **Buat Account Anthropic**
   - Kunjungi: https://console.anthropic.com/
   - Sign up dengan email atau Google/GitHub account

2. **Create API Key**
   - Login ke console
   - Klik menu "API Keys" di sidebar kiri
   - Klik tombol "+ Create Key"
   - Beri nama key (misal: "UmkmAI-Development")
   - Klik "Create Key"
   - **COPY API KEY** — hanya muncul sekali! Format: `sk-ant-xxxxxxxxxxxxx`

3. **Simpan ke .env**
   ```env
   ANTHROPIC_API_KEY=sk-ant-xxxxxxxxxxxxx
   ```

4. **Verification**
   - Cek billing di console.anthropic.com
   - Free tier: 5 tokens/gratis per bulan (cukup untuk testing)

---

### 2️⃣ WHATSAPP CLOUD API — WAJIB (untuk WhatsApp)

Dibutuhkan untuk menerima dan mengirim pesan WhatsApp.

#### Langkah-langkah:

1. **Buat Meta Developer Account**
   - Kunjungi: https://developers.facebook.com/
   - Login dengan akun Facebook Anda

2. **Buat App Baru**
   - Klik "My Apps" → "Create App"
   - Pilih type: **"Business"**
   - Masukkan nama app: "UmkmAI-WhatsApp"
   - Pilih atau buat Business Account (bisa pilih "I don't have a Business Manager")
   - Klik "Create App"

3. **Add Product: WhatsApp**
   - Di dashboard app, cari "WhatsApp" di "Add a Product"
   - Klik "Set Up"
   - Pilih "Send and receive WhatsApp messages for your business"
   - Klik "Continue"

4. **Setup WhatsApp Business Account**
   - Pilih atau buat WhatsApp Business Account baru
   - Masukkan nomor telepon (akan jadi nomor pengirim)
   - Verifikasi nomor dengan OTP via WhatsApp

5. **Dapatkan Credentials**
   - Di sidebar, klik "WhatsApp" → "Getting Started"
   - Catat informasi berikut:

   **a. Phone Number ID:**
   - Scroll ke "Send a message"
   - Copy "Phone Number ID" (format: `123456789012345`)

   **b. Access Token:**
   - Di bagian yang sama, klik "Temporary Access Token"
   - Pilih expiry: "Never expires" (untuk development)
   - Klik "Generate Token"
   - **COPY TOKEN** — hanya muncul sekali! Format: `EAAxxxxxxxxxxxxx`

   **c. App Secret:**
   - Di sidebar, klik "Settings" → "Basic"
   - Scroll ke "App Secret"
   - Klik "Show"
   - **COPY APP SECRET** — format: `xxxxxxxxxxxxxxxx`

   **d. Verify Token:**
   - Buat random string yang aman (untuk webhook verification)
   - Contoh: `umkm_ai_verify_token_2025_random_string`
   - **INGAT STRING INI** — akan diminta saat setup webhook

6. **Simpan ke .env**
   ```env
   WHATSAPP_PHONE_NUMBER_ID=123456789012345
   WHATSAPP_ACCESS_TOKEN=EAAxxxxxxxxxxxxx
   WHATSAPP_APP_SECRET=xxxxxxxxxxxxxxxx
   WHATSAPP_VERIFY_TOKEN=umkm_ai_verify_token_2025_random_string
   ```

---

### 3️⃣ INSTAGRAM GRAPH API — OPTIONAL (untuk Instagram)

Dibutuhkan untuk fitur Instagram DM.

#### Langkah-langkah:

1. **Add Instagram Product ke App Meta**
   - Di dashboard app yang sama (WhatsApp), klik "Add Products"
   - Cari "Instagram Graph API"
   - Klik "Set Up"

2. **Setup Instagram Business Account**
   - Link Instagram Business Account ke app
   - Buka Instagram → Settings → Accounts Center → Professional dashboard
   - Connect ke Meta Business Account

3. **Generate Access Token**
   - Di dashboard app, klik "Tools & Support" → "Graph API Explorer"
   - Pilih App: "UmkmAI-WhatsApp"
   - Select API Version: "v18.0"
   - Pilih User atau Page
   - Di "User or Page", pilih Instagram Business Account Anda
   - Di permissions, select:
     - `instagram_basic`
     - `instagram_manage_messages`
     - `instagram_manage_insights`
   - Klik "Generate Access Token"
   - **COPY TOKEN** — format: `IGQVJxxxxxxxxxxxxx`

4. **Dapatkan App Secret**
   - Sama seperti WhatsApp (satu app, satu secret)
   - Copy dari Settings → Basic → App Secret

5. **Verify Token**
   - Buat random string berbeda dengan WhatsApp
   - Contoh: `ig_verify_token_2025_different_string`

6. **Simpan ke .env**
   ```env
   INSTAGRAM_ACCESS_TOKEN=IGQVJxxxxxxxxxxxxx
   INSTAGRAM_APP_SECRET=xxxxxxxxxxxxxxxx
   INSTAGRAM_VERIFY_TOKEN=ig_verify_token_2025_different_string
   ```

---

### 4️⃣ LOCAL ENVIRONMENT VARIABLES — BISA DICOBA DULU

Untuk development awal tanpa WhatsApp/Instagram, Anda bisa skip dulu.

---

## 📝 .ENV CONFIGURATION EXAMPLE

Berikut contoh `.env` untuk local development:

```env
# -------------------------------------------------------
# Application
# -------------------------------------------------------
APP_NAME=UmkmAI
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# -------------------------------------------------------
# Database — MySQL (local)
# -------------------------------------------------------
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umkm_ai_local
DB_USERNAME=root
DB_PASSWORD=

# -------------------------------------------------------
# Session & Cache
# -------------------------------------------------------
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
CACHE_STORE=file

# -------------------------------------------------------
# Redis
# -------------------------------------------------------
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=predis

# -------------------------------------------------------
# Broadcasting (Soketi)
# -------------------------------------------------------
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=umkm-ai
PUSHER_APP_KEY=umkm-ai-key
PUSHER_APP_SECRET=umkm-ai-secret
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
PUSHER_APP_CLUSTER=mt1
SOKETI_DEBUG=1

# -------------------------------------------------------
# 🤖 Anthropic Claude AI (WAJIB untuk AI features)
# -------------------------------------------------------
ANTHROPIC_API_KEY=sk-ant-xxxxxxxxxxxxx

# -------------------------------------------------------
# 📱 WhatsApp Cloud API (WAJIB untuk WhatsApp)
# -------------------------------------------------------
WHATSAPP_PHONE_NUMBER_ID=123456789012345
WHATSAPP_ACCESS_TOKEN=EAAxxxxxxxxxxxxx
WHATSAPP_VERIFY_TOKEN=umkm_ai_verify_token_2025
WHATSAPP_APP_SECRET=xxxxxxxxxxxxxxxx

# -------------------------------------------------------
# 📸 Instagram Graph API (OPTIONAL untuk Instagram)
# -------------------------------------------------------
INSTAGRAM_ACCESS_TOKEN=IGQVJxxxxxxxxxxxxx
INSTAGRAM_VERIFY_TOKEN=ig_verify_token_2025
INSTAGRAM_APP_SECRET=xxxxxxxxxxxxxxxx

# -------------------------------------------------------
# Webhook Configuration
# -------------------------------------------------------
WEBHOOK_SKIP_VERIFY=false  # Set true untuk skip signature check di local

# -------------------------------------------------------
# Vite (Frontend)
# -------------------------------------------------------
VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
```

---

## 🚀 RUNNING THE APPLICATION

### Step 1: Install PHP Dependencies

```bash
composer install
```

### Step 2: Generate Application Key

```bash
php artisan key:generate
```

### Step 3: Create Database

```bash
# Login ke MySQL
mysql -u root -p

# Create database
CREATE DATABASE umkm_ai_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Exit MySQL
EXIT;
```

### Step 4: Run Migrations

```bash
php artisan migrate
```

### Step 5: Install Frontend Dependencies

```bash
npm install
```

### Step 6: Build Frontend Assets

```bash
npm run dev
```

### Step 7: Start Redis

```bash
# Jika pakai Docker
docker start <redis-container-name>

# Atau jika Redis native
redis-server

# Verify
redis-cli ping
# Output: PONG
```

### Step 8: Start Queue Worker

Di terminal baru:

```bash
php artisan queue:work redis --tries=3
```

### Step 9: Start Soketi WebSocket Server

Di terminal baru:

```bash
# Jika pakai Docker
docker run -d -p 6001:6001 -p 9601:9601 \
  -e SOKETI_APP_ID=umkm-ai \
  -e SOKETI_APP_KEY=umkm-ai-key \
  -e SOKETI_APP_SECRET=umkm-ai-secret \
  -e SOKETI_DEBUG=1 \
  quay.io/soketi/soketi:latest-16-alpine
```

### Step 10: Start Laravel Development Server

```bash
php artisan serve
```

### Step 11: Open Browser

Kunjungi: http://localhost:8000

---

## 🧪 TESTING WEBHOOKS

### Option 1: Dengan ngrok (Recommended)

1. **Install ngrok**
   - Download dari https://ngrok.com/download
   - Extract dan jalankan

2. **Start ngrok**
   ```bash
   ngrok http 8000
   ```

3. **Copy HTTPS URL**
   - Contoh: `https://abc123.ngrok.io`

4. **Setup Webhook di Meta Developer Console**
   - WhatsApp Webhook URL: `https://abc123.ngrok.io/api/webhook/whatsapp`
   - Verify Token: isi dengan `WHATSAPP_VERIFY_TOKEN` dari .env
   - Subscribe to: `messages`

5. **Test Kirim Pesan**
   - Kirim pesan ke nomor WhatsApp Business Anda
   - Cek log: `tail -f storage/logs/laravel.log`

### Option 2: Dengan Postman (Manual Testing)

1. **Buka Postman**

2. **Test Webhook Verification (GET)**
   ```
   Method: GET
   URL: http://localhost:8000/api/webhook/whatsapp
   Params:
     - hub_mode: subscribe
     - hub_verify_token: [isi dengan WHATSAPP_VERIFY_TOKEN]
     - hub_challenge: test_challenge_123
   ```
   Expected Response: `test_challenge_123` (raw text)

3. **Test Webhook Payload (POST)**
   ```
   Method: POST
   URL: http://localhost:8000/api/webhook/whatsapp
   Headers:
     - Content-Type: application/json
     - X-Hub-Signature-256: [calculate signature]
   Body (raw JSON):
   {
     "entry": [
       {
         "changes": [
           {
             "value": {
               "messages": [
                 {
                   "from": "6281234567890",
                   "id": "wamid.HBgLNjYxODc3ODU4FjEMAQAB",
                   "timestamp": "1699999999",
                   "type": "text",
                   "text": {
                     "body": "Halo, mau pesan sepatu"
                   }
                 }
               ],
               "contacts": [
                 {
                   "profile": {
                     "name": "Budi Santoso"
                   }
                 }
               ]
             }
           }
         ]
       }
     ]
   }
   ```
   Expected Response: `OK`

---

## 🔧 TROUBLESHOOTING

### Problem: PHP not recognized
**Solution:** Add PHP to System PATH dan restart terminal

### Problem: Composer not found
**Solution:** Reinstall Composer dan verify PHP installation

### Problem: MySQL connection failed
**Solution:**
- Check if MySQL running: `mysql -u root -p`
- Verify credentials di .env
- Check MySQL port: 3306

### Problem: Redis connection refused
**Solution:**
- Start Redis server
- Check if port 6379 available
- Verify REDIS_HOST di .env

### Problem: Queue worker not processing
**Solution:**
- Check if Redis running
- Verify QUEUE_CONNECTION=redis di .env
- Restart queue worker

### Problem: Webhook signature verification failed
**Solution:**
- Check WHATSAPP_APP_SECRET di .env
- Verify signature calculation
- Set WEBHOOK_SKIP_VERIFY=true untuk testing

### Problem: AI not responding
**Solution:**
- Verify ANTHROPIC_API_KEY di .env
- Check API key validity: https://console.anthropic.com/
- Check logs: `tail -f storage/logs/laravel.log`
- Verify credit di Anthropic console

---

## 📞 NEED HELP?

Jika mengalami masalah:

1. **Cek Logs:** `tail -f storage/logs/laravel.log`
2. **Clear Cache:** `php artisan optimize:clear`
3. **Restart Services:** Stop semua services dan start ulang
4. **Check GitHub Issues:** https://github.com/Gimm17/umkm-ai/issues
5. **Ask Question:** Buat new issue di GitHub

---

## ✅ CHECKLIST SEBELUM MULAI

- [ ] PHP 8.3+ installed
- [ ] Composer installed
- [ ] Node.js 20+ installed
- [ ] MySQL 8.0+ running
- [ ] Redis running
- [ ] `.env` configured
- [ ] Database created
- [ ] Migrations run
- [ ] npm packages installed
- [ ] Queue worker running
- [ ] Soketi WebSocket server running
- [ ] Laravel serve running
- [ ] ANTHROPIC_API_KEY obtained
- [ ] WHATSAPP credentials obtained (optional)
- [ ] INSTAGRAM credentials obtained (optional)

---

**Selamat coding! 🚀**

Jika panduan ini membantu, jangan lupa ⭐ star di GitHub!
