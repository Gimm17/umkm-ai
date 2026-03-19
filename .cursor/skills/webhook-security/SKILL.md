---
name: webhook-security
description: >
  Panduan keamanan dan handling webhook dari WhatsApp Cloud API dan
  Instagram Graph API untuk project UmkmAI. Gunakan skill ini setiap kali
  membuat atau mengubah WebhookController, middleware webhook, atau konfigurasi
  endpoint yang menerima data dari platform eksternal.
---

# Webhook Security — Rules & Handling (UmkmAI)

## 🔐 Prinsip Utama Keamanan Webhook

1. **Selalu verifikasi signature** — jangan percaya payload tanpa tanda tangan
2. **Return 200 secepat mungkin** — proses di background via Queue
3. **Idempotent processing** — handle duplicate webhook dengan aman
4. **Jangan expose error detail** — return generic response ke luar

---

## 📡 WhatsApp Cloud API

### Registrasi Webhook (GET request)
```php
// routes/api.php — TANPA auth middleware
Route::get('/webhook/whatsapp', [WebhookController::class, 'verifyWhatsApp']);
Route::post('/webhook/whatsapp', [WebhookController::class, 'handleWhatsApp']);

// WebhookController.php
public function verifyWhatsApp(Request $request): Response
{
    $mode      = $request->query('hub_mode');
    $token     = $request->query('hub_verify_token');
    $challenge = $request->query('hub_challenge');

    if ($mode === 'subscribe' && $token === config('services.whatsapp.verify_token')) {
        return response($challenge, 200)->header('Content-Type', 'text/plain');
    }

    return response('Forbidden', 403);
}
```

### Verifikasi Signature (POST request)
```php
public function handleWhatsApp(Request $request): Response
{
    // 1. Verifikasi signature SEBELUM apapun
    $signature = $request->header('X-Hub-Signature-256');

    if (!$this->verifySignature($request->getContent(), $signature, 'whatsapp')) {
        Log::warning('Webhook WA: signature tidak valid', [
            'ip' => $request->ip()
        ]);
        return response('Forbidden', 403);
    }

    // 2. Langsung dispatch ke queue — return 200 secepat mungkin
    ProcessIncomingMessage::dispatch('whatsapp', $request->all())
        ->onQueue('messages');

    return response('OK', 200);
}

private function verifySignature(string $payload, ?string $signature, string $channel): bool
{
    if (!$signature) return false;

    $secret   = config("services.{$channel}.app_secret");
    $expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);

    return hash_equals($expected, $signature);
}
```

---

## 📸 Instagram Graph API

### Sama persis dengan WhatsApp — bedanya:
- Header signature: `X-Hub-Signature-256` (sama)
- Secret key: `INSTAGRAM_APP_SECRET` (berbeda dari WA)
- Struktur payload berbeda — parse dengan `InstagramPayloadParser`

```php
Route::get('/webhook/instagram', [WebhookController::class, 'verifyInstagram']);
Route::post('/webhook/instagram', [WebhookController::class, 'handleInstagram']);
```

---

## 🔄 Idempotency — Handle Duplicate Webhook

Platform kadang mengirim webhook yang sama 2x. Handle dengan cache:

```php
// Di ProcessIncomingMessage job
public function handle(): void
{
    $messageId = $this->extractMessageId($this->payload);

    // Skip jika sudah pernah diproses
    $cacheKey = "processed_webhook:{$messageId}";
    if (Cache::has($cacheKey)) {
        return; // idempotent — aman diabaikan
    }

    Cache::put($cacheKey, true, now()->addHours(24));

    // Lanjut proses...
}
```

---

## 🗂️ Payload Parser

Pisahkan parsing per platform — jangan satu parser untuk semua:

```php
// app/Services/Parsers/WhatsAppPayloadParser.php
class WhatsAppPayloadParser
{
    public function parse(array $payload): ?IncomingMessageDTO
    {
        // Struktur WA: entry[0].changes[0].value.messages[0]
        $message = data_get($payload, 'entry.0.changes.0.value.messages.0');

        if (!$message) return null; // bukan pesan (mungkin status update)

        return new IncomingMessageDTO(
            channel:    'whatsapp',
            channelId:  data_get($payload, 'entry.0.id'),
            senderId:   $message['from'],
            senderName: data_get($payload, 'entry.0.changes.0.value.contacts.0.profile.name'),
            content:    data_get($message, 'text.body', ''),
            type:       $message['type'],
            rawPayload: $payload,
        );
    }
}
```

---

## 🛡️ Middleware — Webhook Route Group

```php
// Pastikan webhook endpoint:
// - TIDAK menggunakan VerifyCsrfToken
// - TIDAK menggunakan auth middleware
// - Menggunakan throttle yang longgar (WA bisa kirim burst)

// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->validateCsrfTokens(except: [
        'api/webhook/*',
    ]);
})
```

---

## 📋 .env yang Dibutuhkan

```env
# WhatsApp Cloud API
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id
WHATSAPP_ACCESS_TOKEN=your_access_token
WHATSAPP_VERIFY_TOKEN=random_string_untuk_verifikasi
WHATSAPP_APP_SECRET=your_app_secret

# Instagram Graph API
INSTAGRAM_ACCESS_TOKEN=your_access_token
INSTAGRAM_VERIFY_TOKEN=random_string_berbeda
INSTAGRAM_APP_SECRET=your_instagram_app_secret
```

---

## 🧪 Testing Webhook

```php
// tests/Feature/Webhook/WhatsAppWebhookTest.php
class WhatsAppWebhookTest extends TestCase
{
    public function test_rejects_request_without_valid_signature(): void
    {
        $this->postJson('/api/webhook/whatsapp', ['fake' => 'data'])
             ->assertStatus(403);
    }

    public function test_accepts_valid_webhook_and_dispatches_job(): void
    {
        Queue::fake();

        $payload   = WhatsAppPayloadFactory::textMessage();
        $signature = 'sha256=' . hash_hmac('sha256', json_encode($payload), config('services.whatsapp.app_secret'));

        $this->postJson('/api/webhook/whatsapp', $payload, [
            'X-Hub-Signature-256' => $signature,
        ])->assertOk();

        Queue::assertPushed(ProcessIncomingMessage::class);
    }

    public function test_duplicate_webhook_is_skipped(): void
    {
        // Kirim webhook yang sama 2x
        // Pastikan hanya 1 Message yang tersimpan
    }
}
```

---

## 🚫 Hal yang DILARANG
- ❌ Proses payload webhook secara sinkron (selalu queue)
- ❌ Log isi pesan pelanggan tanpa enkripsi
- ❌ Expose stack trace / error detail ke response webhook
- ❌ Webhook endpoint pakai CSRF protection
- ❌ Skip verifikasi signature "untuk development" — pakai `WEBHOOK_SKIP_VERIFY=true` di `.env` saja, dengan guard `if (!app()->isProduction())`
