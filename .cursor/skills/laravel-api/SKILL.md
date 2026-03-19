---
name: laravel-api
description: >
  Panduan membangun backend Laravel 12 untuk project UmkmAI.
  Gunakan skill ini setiap kali membuat Controller, Service, Model, Migration,
  Job, Event, atau konfigurasi API di project ini. Wajib dibaca sebelum
  menulis kode PHP apapun di project UmkmAI.
---

# Laravel API — Rules & Best Practices (UmkmAI)

## ⚙️ Versi & Konfigurasi
- Laravel: **12.x**
- PHP: **8.3** (gunakan typed properties, enums, fibers jika relevan)
- Database: **MySQL 8** (production), **SQLite** (testing)
- Queue Driver: **Redis** via Laravel Horizon
- Realtime: **Laravel Echo + Soketi** (self-hosted Pusher)

---

## 📐 Arsitektur Wajib

### Controller → tipis, hanya routing logic
```php
// ✅ BENAR
class MessageController extends Controller
{
    public function store(SendMessageRequest $request, Conversation $conversation): JsonResponse
    {
        $message = $this->messageService->send($conversation, $request->validated());
        return MessageResource::make($message)->response()->setStatusCode(201);
    }
}

// ❌ SALAH — jangan taruh business logic di controller
public function store(Request $request): JsonResponse
{
    $message = Message::create([...]);
    Http::post('https://api.whatsapp.com/...', [...]);
    // dst...
}
```

### Service Class — semua business logic di sini
- Setiap domain punya Service: `AIAgentService`, `WhatsAppService`, `InstagramService`, `OrderExtractorService`
- Service di-inject via constructor (bukan `new ServiceClass()`)
- Daftarkan di `AppServiceProvider`

### Job — semua operasi berat masuk queue
```php
// Setiap pesan masuk HARUS diproses via job, TIDAK sinkron
ProcessIncomingMessage::dispatch($payload)->onQueue('messages');
SendAIReply::dispatch($message)->onQueue('ai-replies');
```

---

## 🗄️ Database Rules

### Migration
- Selalu gunakan `->comment('...')` pada kolom penting
- Tambahkan index pada kolom yang sering di-query: `channel`, `status`, `contact_id`
- Gunakan `json` type untuk data fleksibel (`items`, `raw_payload`, `tags`)
- Semua tabel gunakan `timestamps()` + `softDeletes()` kecuali ada alasan kuat

### Eloquent
```php
// Gunakan Scopes untuk query berulang
public function scopeOpen(Builder $query): Builder
{
    return $query->where('status', 'open');
}

// Gunakan dengan eager loading — HINDARI N+1
$conversations = Conversation::with(['contact', 'lastMessage'])->open()->get();

// Gunakan firstOrCreate / updateOrCreate untuk upsert
$contact = Contact::updateOrCreate(
    ['channel' => 'whatsapp', 'channel_id' => $phone],
    ['name' => $name]
);
```

---

## 🔒 Keamanan

### Webhook Endpoint
```php
// WAJIB verifikasi signature di setiap webhook
public function whatsapp(Request $request): Response
{
    // 1. Verifikasi hub.challenge untuk registrasi
    if ($request->has('hub_challenge')) {
        return $this->verifyWebhook($request);
    }

    // 2. Verifikasi X-Hub-Signature-256 setiap request
    if (!$this->webhookVerifier->verify($request)) {
        abort(403, 'Invalid signature');
    }

    // 3. Langsung return 200, proses di background
    ProcessIncomingMessage::dispatch($request->all())->onQueue('messages');
    return response('OK', 200);
}
```

### Enkripsi Token
```php
// WAJIB enkripsi semua token pihak ketiga
BusinessSetting::set('whatsapp_token', encrypt($token));
$token = decrypt(BusinessSetting::get('whatsapp_token'));
```

### Jangan Expose di Log
```php
// Di .env
LOG_SANITIZE_KEYS=whatsapp_token,instagram_token,claude_api_key
```

---

## 📦 Form Request — Selalu Gunakan
```php
class SendMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content'   => ['required', 'string', 'max:4096'],
            'type'      => ['required', Rule::in(['text', 'image'])],
        ];
    }
}
```

---

## 🧪 Testing Rules
- Setiap webhook endpoint WAJIB punya Feature Test
- Gunakan `Http::fake()` untuk mock API eksternal (WA, IG, Claude)
- Gunakan `Queue::fake()` untuk assert job di-dispatch
- Test file: `tests/Feature/Webhook/WhatsAppWebhookTest.php`

```php
public function test_incoming_whatsapp_message_dispatches_job(): void
{
    Queue::fake();
    Http::fake(); // mock semua HTTP call

    $payload = WhatsAppPayloadFactory::incomingText();

    $this->postJson('/api/webhook/whatsapp', $payload, [
        'X-Hub-Signature-256' => $this->sign($payload)
    ])->assertOk();

    Queue::assertPushed(ProcessIncomingMessage::class);
}
```

---

## 📝 Docblock Standard
```php
/**
 * Kirim pesan ke WhatsApp via Cloud API.
 *
 * @param  Conversation  $conversation  Target percakapan
 * @param  string        $content       Isi pesan teks
 * @return Message                      Record pesan yang tersimpan
 * @throws WhatsAppException            Jika API call gagal
 */
public function sendText(Conversation $conversation, string $content): Message
```

---

## 🚫 Hal yang DILARANG
- ❌ `DB::statement()` raw SQL tanpa alasan kuat
- ❌ `env()` langsung di kode — selalu lewat `config()`
- ❌ Logic di blade / Vue template
- ❌ API token hardcode di kode
- ❌ Proses synchronous untuk AI call atau kirim pesan
- ❌ `sleep()` atau blocking operation di request lifecycle
