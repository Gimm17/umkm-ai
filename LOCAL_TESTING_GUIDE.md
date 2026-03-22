# 🚀 COMPLETE LOCAL TESTING GUIDE - UmkmAI

Panduan step-by-step lengkap untuk menjalankan UmkmAI di local dengan Redis native.

---

## ✅ STATUS SAAT INI:

- ✅ Database sudah migrate
- ✅ Business settings sudah seeded
- ✅ **Gemini API key sudah terisi**
- ✅ WhatsApp credentials sudah ada
- ⏳ Aplikasi belum running

---

## 📋 PREPARATION CHECKLIST:

Pastikan software ini sudah terinstall:
- [x] PHP 8.3+
- [x] Composer
- [x] Node.js 20+
- [x] MySQL (XAMPP)
- [ ] **Redis native (redis-server.exe)** — SIAPKAN INI DULU

---

## 🎯 LANGKAH DEMI LANGKAH:

### **STEP 1: Clear Application Cache**

```bash
php artisan optimize:clear
```

**Output yang diharapkan:**
```
Cached events cleared!
Compiled views cleared!
Route cache cleared!
etc...
```

---

### **STEP 2: Install Frontend Dependencies**

```bash
npm install
```

**Tunggu sampai selesai** (akan download semua packages).

---

### **STEP 3: Build Frontend Assets**

```bash
npm run build
```

**Output yang diharapkan:**
```
✓ built in 2.5s
```

Ini akan membuat folder `public/build` dengan compiled assets.

---

### **STEP 4: Start Redis Native**

**Cara A: Dengan redis-server.exe (Native)**

1. **Cari lokasi redis-server.exe**
   - Biasanya di: `C:\Program Files\Redis\redis-server.exe`
   - Atau lokasi extract tadi

2. **Jalankan redis-server.exe**
   - Double click `redis-server.exe`
   - Atau via command prompt:
     ```bash
     "C:\Program Files\Redis\redis-server.exe"
     ```

3. **Verifikasi Redis Running**
   - Buka command prompt baru
   - Jalankan:
     ```bash
     redis-cli ping
     ```
   - Output yang diharapkan: `PONG`

**Cara B: Dengan Command Prompt (Lebih Mudah)**

```bash
# Di command prompt, jalankan:
redis-server

# Atau dengan path lengkap:
"C:\Program Files\Redis\redis-server.exe"
```

**Tinggalkan terminal ini TERBUKA** — Redis harus terus running!

---

### **STEP 5: Siapkan 3 Terminal Terpisah**

Kita butuh **3 terminal windows** yang semuanya running:

#### **Terminal 1: Queue Worker**
```bash
php artisan queue:work redis --tries=3 --timeout=300
```

**PENTING:** Terminal ini harus **TERUS TERBUKA**!
- Ini akan memproses webhook, AI reply, dan order detection
- Kalau ditutup, pesan WhatsApp tidak akan diproses

**Output yang diharapkan:**
```
[2025-03-19 14:30:00] Processing: App\Jobs\...
[2025-03-19 14:30:01] Processed:  App\Jobs\...
```

#### **Terminal 2: Laravel Development Server**
```bash
php artisan serve
```

**Output yang diharapkan:**
```
INFO  Server running on [http://127.0.0.1:8000].
```

#### **Terminal 3: (Opsional) Vite Dev Server**
```bash
npm run dev
```

**Ini OPSIONAL** — untuk development dengan hot-reload.
Kalau mau simple, skip saja.

---

### **STEP 6: Buka Browser**

Kunjungi: **http://localhost:8000**

**Yang seharusnya muncul:**
1. Welcome page UmkmAI 🤖
2. Menu navigation: Inbox, Orders, Settings

---

### **STEP 7: Test Database Connection**

Buka phpMyAdmin: **http://localhost/phpmyadmin**

**Cek database `umkm_ai`:**
- Harus ada 5 tabel: `contacts`, `conversations`, `messages`, `orders`, `business_settings`
- Harus ada data di tabel `business_settings`

---

### **STEP 8: Test AI Provider (Gemini)**

Buka **Terminal baru** dan jalankan tinker:

```bash
php artisan tinker
```

Lalu jalankan test script ini:

```php
use App\Services\AIProviderFactory;

echo "=== TEST AI PROVIDER ===\n";

$provider = AIProviderFactory::getProvider();
echo "Provider: " . $provider->getProviderName() . "\n";
echo "Configured: " . ($provider->isConfigured() ? 'YES ✅' : 'NO ❌') . "\n";

echo "\n=== TEST GENERATE ===\n";

try {
    $response = $provider->generate(
        'Kamu adalah AI assistant untuk Toko Sepatu Keren.',
        'Halo! Apa ada sepatu size 43?',
        []
    );
    echo "Response: " . $response . "\n";
    echo "✅ AI Provider BERHASIL!\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

exit;
```

**Output yang diharapkan:**
```
=== TEST AI PROVIDER ===
Provider: gemini
Configured: YES ✅

=== TEST GENERATE ===
Response: Halo! Tentu ada sepatu size 43 di Toko Sepatu Keren...
✅ AI Provider BERHASIL!
```

---

### **STEP 9: Test Kirim Pesan WhatsApp (Manual)**

Masih di tinker, jalankan:

```php
use Illuminate\Support\Facades\Http;

$phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
$accessToken = env('WHATSAPP_ACCESS_TOKEN');

// GANTI DENGAN NOMOR WHATSAPP ANDA!
$testNumber = '6281234567890';

echo "Sending test message to {$testNumber}...\n";

$response = Http::withToken($accessToken)
    ->timeout(30)
    ->post("https://graph.facebook.com/v18.0/{$phoneNumberId}/messages", [
        'messaging_product' => 'whatsapp',
        'to' => $testNumber,
        'type' => 'text',
        'text' => [
            'body' => '🎉 Tes dari UmkmAI! Pesan test berhasil.',
        ],
    ]);

if ($response->successful()) {
    echo "✅ BERHASIL! Cek WhatsApp Anda.\n";
} else {
    echo "❌ GAGAL! Status: {$response->status()}\n";
    echo "Error: {$response->body()}\n";
}

exit;
```

**Kalau berhasil**, Anda akan menerima pesan di WhatsApp:
> 🎉 Tes dari UmkmAI! Pesan test berhasil.

---

## 🔧 TROUBLESHOOTING

### **Problem: Redis connection refused**

**Solusi:**
1. Pastikan redis-server.exe sudah running
2. Cek dengan: `redis-cli ping`
3. Kalau belum, start redis-server.exe

### **Problem: Queue worker tidak memproses apa-apa**

**Solusi:**
1. Cek apakah Redis running
2. Restart queue worker
3. Cek logs: `tail -f storage/logs/laravel.log`

### **Problem: Blank page di browser**

**Solusi:**
1. Clear cache: `php artisan optimize:clear`
2. Rebuild frontend: `npm run build`
3. Cek apakah `php artisan serve` running

### **Problem: "Class not found"**

**Solusi:**
```bash
composer dump-autoload
php artisan optimize:clear
```

### **Problem: AI tidak merespon**

**Solusi:**
1. Cek GEMINI_API_KEY di .env
2. Test dengan tinker (Step 8)
3. Cek logs: `tail -f storage/logs/laravel.log`

---

## ✅ CHECKLIST SEBELUM TEST WEBHOOK:

- [ ] ✅ Redis sudah running (redis-cli ping → PONG)
- [ ] ✅ Queue worker sudah running (Terminal 1)
- [ ] ✅ Laravel serve sudah running (Terminal 2)
- [ ] ✅ Bisa akses http://localhost:8000
- [ ] ✅ Database sudah ada isi (phpMyAdmin)
- [ ] ✅ AI Provider sudah tested (tinker)
- [ ] ✅ Kirim pesan WhatsApp manual berhasil

---

## 🚀 SETELAH SEMUA JALAN:

Kalau semua step di atas berhasil, aplikasi sudah siap!

**Sekarang Anda bisa:**

1. **Kirim pesan WhatsApp** → Akan masuk ke database
2. **AI akan auto-reply** → Menggunakan Gemini
3. **Order akan terdeteksi** → Tersimpan di database

**Next Steps:**
- Setup webhook di Meta Developer Console (pakai ngrok)
- Test full flow: Kirim pesan → AI reply → Order detection

---

**Sekarang coba jalankan Step 1-7 di atas, lalu kabari saya hasilnya!** 🎯

Kalau ada masalah di step mana, bilang ya dan saya bantu troubleshooting! 🚀
