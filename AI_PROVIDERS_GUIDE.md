# 🤖 AI PROVIDERS SETUP GUIDE

Panduan lengkap cara mendapatkan API key untuk berbagai AI providers yang didukung UmkmAI.

---

## 📋 DAFTAR AI PROVIDERS

| Provider | Status | Free Tier | Keterangan |
|----------|--------|-----------|------------|
| **Google Gemini** | ✅ Recommended | 15 req/min | Paling stabil, gratis |
| **Moonshot Kimi** | ✅ China | Jutaan tokens | Alternative bagus |
| **Zhipu GLM** | ✅ China | Jutaan tokens | Alternative bagus |
| **Anthropic Claude** | 💰 Paid | $3/M tokens | Original (paid only) |

---

## 1️⃣ GOOGLE GEMINI (RECOMMENDED - FREE)

### Keunggulan:
- ✅ **100% GRATIS**
- ✅ Stabil dan cepat
- ✅ Support Bahasa Indonesia baik
- ✅ 15 requests per minute
- ✅ Tidak perlu credit card

### Langkah-langkah:

1. **Kunjungi Google AI Studio**
   - Link: https://makersuite.google.com/app/apikey
   - Login dengan akun Google

2. **Create API Key**
   - Klik "Create API Key"
   - Beri nama: "UmkmAI-Production"
   - Pilih project: "New Project" (atau pilih existing)

3. **Copy API Key**
   - Format: `AIzaSyxxxxxxxxxxxxx`
   - **COPY KEY INI**

4. **Enable Gemini API**
   - Pastikan "Gemini API" sudah enabled
   - Kalau belum, klik "Enable APIs"

5. **Simpan ke .env**
   ```env
   GEMINI_API_KEY=AIzaSyxxxxxxxxxxxxx
   ```

### Links:
- 📘 Documentation: https://ai.google.dev/gemini-api/docs
- 🔑 API Keys: https://makersuite.google.com/app/apikey
- 💻 Console: https://console.cloud.google.com/

---

## 2️⃣ MOONSHOT KIMI (FREE - CHINA ALTERNATIVE)

### Keunggulan:
- ✅ **100% GRATIS**
- ✅ Support Bahasa China sangat baik
- ✅ Support Bahasa Indonesia lumayan
- ✅ Jutaan tokens gratis
- ✅ Developed by ex-Microsoft engineers

### Langkah-langkah:

1. **Kunjungi Moonshot AI Platform**
   - Link: https://platform.moonshot.cn/
   - Login dengan phone number (+86)

2. **Register Account**
   - Klik "注册" (Register)
   - Verify dengan phone number China
   - Atau login dengan GitHub

3. **Create API Key**
   - Klik menu "API Keys"
   - Klik "创建新密钥" (Create new key)
   - Beri nama: "UmkmAI-Production"

4. **Copy API Key**
   - Format: `sk-xxxxxxxxxxxxx`
   - **COPY KEY INI**

5. **Simpan ke .env**
   ```env
   KIMI_API_KEY=sk-xxxxxxxxxxxxx
   ```

### Links:
- 📘 Documentation: https://platform.moonshot.cn/docs
- 🔑 API Keys: https://platform.moonshot.cn/console/api-keys
- 💻 Platform: https://platform.moonshot.cn/

---

## 3️⃣ ZHIPU GLM (FREE - CHINA ALTERNATIVE)

### Keunggulan:
- ✅ **100% GRATIS**
- ✅ Support Bahasa China sangat baik
- ✅ Support Bahasa Indonesia lumayan
- ✅ Jutaan tokens gratis
- ✅ GLM-4 model cukup powerful

### Langkah-langkah:

1. **Kunjungi Zhipu AI Platform**
   - Link: https://open.bigmodel.cn/
   - Login dengan phone number (+86)

2. **Register Account**
   - Klik "注册" (Register)
   - Verify dengan phone number China
   - Atau login dengan GitHub

3. **Create API Key**
   - Klik menu "API Keys"
   - Klik "创建 API Key" (Create API Key)
   - Beri nama: "UmkmAI-Production"

4. **Copy API Key**
   - Format: berbagai format, biasanya mulai dengan `sk-`
   - **COPY KEY INI**

5. **Simpan ke .env**
   ```env
   GLM_API_KEY=sk-xxxxxxxxxxxxx
   ```

### Links:
- 📘 Documentation: https://open.bigmodel.cn/dev/api
- 🔑 API Keys: https://open.bigmodel.cn/usercenter/apikeys
- 💻 Platform: https://open.bigmodel.cn/

---

## 4️⃣ ANTHROPIC CLAUDE (PAID - ORIGINAL)

### Keunggulan:
- ✅ Support Bahasa Indonesia sangat baik
- ✅ Model yang paling cerdas
- ✅ Context window besar (200K tokens)
- ❌ **PAID** - $3 per million input tokens

### Langkah-langkah:

1. **Kunjungi Anthropic Console**
   - Link: https://console.anthropic.com/
   - Login dengan email

2. **Create API Key**
   - Klik "API Keys" di sidebar
   - Klik "+ Create Key"
   - Beri nama: "UmkmAI-Production"

3. **Set Usage Limit**
   - Set spending limit untuk keamanan
   - Default: $5 per bulan

4. **Copy API Key**
   - Format: `sk-ant-xxxxxxxxxxxxx`
   - **COPY KEY INI (hanya muncul sekali!)**

5. **Simpan ke .env**
   ```env
   ANTHROPIC_API_KEY=sk-ant-xxxxxxxxxxxxx
   ```

### Links:
- 📘 Documentation: https://docs.anthropic.com/
- 🔑 Console: https://console.anthropic.com/
- 💻 Pricing: https://www.anthropic.com/pricing

---

## 🔄 CARA GANTI AI PROVIDER

### Step 1: Dapatkan API Key

Pilih salah satu provider di atas dan dapatkan API key-nya.

### Step 2: Update .env

```env
# Pilih provider: gemini, kimi, glm, atau anthropic
AI_PROVIDER=gemini

# Isi API key sesuai provider
GEMINI_API_KEY=AIzaSyxxxxxxxxxxxxx
# KIMI_API_KEY=sk-xxxxxxxxxxxxx
# GLM_API_KEY=sk-xxxxxxxxxxxxx
# ANTHROPIC_API_KEY=sk-ant-xxxxxxxxxxxxx
```

### Step 3: Clear Cache

```bash
php artisan optimize:clear
```

### Step 4: Restart Queue Worker

```bash
# Stop queue worker (Ctrl+C)
# Start ulang
php artisan queue:work redis --tries=3
```

---

## 🎯 REKOMENDASI

### Untuk Testing/Development:
**Gunakan Google Gemini** ✅
- 100% gratis
- Stabil
- Support Bahasa Indonesia baik
- Cepat dan responsif

### Untuk Production:
**Pilih salah satu:**
- **Google Gemini** — Kalau mau tetap gratis
- **Anthropic Claude** — Kalau mau hasil terbaik dan siap bayar

### Untuk Market China:
**Pilih salah satu:**
- **Moonshot Kimi** — Support Bahasa China
- **Zhipu GLM** — Support Bahasa China

---

## 🧪 TEST AI PROVIDER

Setelah setup, test apakah AI provider sudah benar:

```bash
php artisan tinker
```

Lalu jalankan:

```php
use App\Services\AIProviderFactory;

// Cek provider yang aktif
$provider = AIProviderFactory::getProvider();
echo "Active provider: " . $provider->getProviderName() . "\n";

// Cek apakah sudah configured
if ($provider->isConfigured()) {
    echo "✅ Provider configured!\n";
} else {
    echo "❌ Provider not configured!\n";
}

// Test generate
try {
    $response = $provider->generate(
        'Kamu adalah AI assistant helpful.',
        'Halo! Siapa kamu?',
        []
    );
    echo "Response: " . $response . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

---

## 📊 PERBANDINGAN PROVIDERS

| Fitur | Gemini | Kimi | GLM | Claude |
|-------|--------|------|-----|--------|
| **Harga** | Free | Free | Free | $3/M tokens |
| **Kecepatan** | ⚡ Cepat | ⚡ Cepat | ⚡ Cepat | ⚡ Cepat |
| **Bahasa Indonesia** | ✅ Baik | ⚠️ Lumayan | ⚠️ Lumayan | ✅ Sangat Baik |
| **Bahasa China** | ⚠️ Lumayan | ✅ Sangat Baik | ✅ Sangat Baik | ⚠️ Lumayan |
| **Stabilitas** | ✅ Stabil | ✅ Stabil | ✅ Stabil | ✅ Sangat Stabil |
| **Context Window** | 1M tokens | 128K tokens | 128K tokens | 200K tokens |
| **Rate Limit** | 15 req/min | Tinggi | Tinggi | Tinggi |
| **Documentation** | ✅ Lengkap | ⚠️ China | ⚠️ China | ✅ Sangat Lengkap |

---

## 💡 TIPS

1. **Simpan API Keys dengan Aman**
   - Jangan commit ke git!
   - Jangan share ke orang lain
   - Gunakan environment variables

2. **Monitor Usage**
   - Cek dashboard masing-masing provider
   - Set alert kalau mendekati limit

3. **Fallback System**
   - Aplikasi otomatis pakai provider yang tersedia
   - Kalau satu gagal, akan retry

4. **Bisa Ganti Kapan Saja**
   - Ubah `AI_PROVIDER` di .env
   - Clear cache
   - Restart queue worker

---

## ❓ FAQ

### Q: Apakah bisa pakai lebih dari satu provider sekaligus?
**A:** Tidak bisa untuk sekarang. Harus pilih satu provider aktif.

### Q: Apakah bisa ganti provider tanpa restart aplikasi?
**A:** Bisa! Ubah `AI_PROVIDER` di .env, clear cache, dan restart queue worker.

### Q: Provider mana yang paling bagus untuk Bahasa Indonesia?
**A:**
1. **Anthropic Claude** — Paling bagus tapi berbayar
2. **Google Gemini** — Gratis dan sudah cukup bagus
3. **Kimi/GLM** — Lumayan tapi lebih bagus untuk Bahasa China

### Q: Apakah API key bisa dipakai di production?
**A:** Bisa! Semua provider di atas bisa dipakai di production.

---

**Selamat mencoba! 🚀**

Kalau ada masalah dengan API key, cek dokumentasi masing-masing provider atau buat issue di GitHub.
