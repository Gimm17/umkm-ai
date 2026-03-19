---
name: ai-agent
description: >
  Panduan integrasi Claude AI API sebagai agent otomatis di project UmkmAI.
  Gunakan skill ini setiap kali membuat atau mengubah AIAgentService,
  ContextBuilderService, OrderExtractorService, system prompt, atau logika
  apapun yang melibatkan panggilan ke Anthropic Claude API.
---

# AI Agent — Rules & Integration Guide (UmkmAI)

## 🤖 Model & API
- **Model:** `claude-sonnet-4-20250514` (jangan ganti tanpa diskusi)
- **Max tokens:** 1024 (cukup untuk balasan chat)
- **API Key:** Simpan di `.env` sebagai `ANTHROPIC_API_KEY`
- **SDK:** Gunakan HTTP langsung via Laravel `Http::` facade (lebih kontrol)

---

## 🏗️ Arsitektur AI Pipeline

```
Pesan Masuk
    │
    ▼
ContextBuilderService
    │ → Load business settings (nama toko, produk, prompt kustom)
    │ → Load history percakapan (max 10 pesan terakhir)
    │ → Format ke struktur messages[]
    ▼
AIAgentService::process()
    │ → Kirim ke Claude API
    │ → Handle error & retry (max 2x)
    ▼
Response Parser
    │ → Pisahkan teks reply vs [ORDER_DETECTED: {...}]
    ▼
OrderExtractorService (jika ada order)
    │ → Parse JSON order
    │ → Simpan ke tabel orders
    ▼
Simpan reply ke messages
    │
    ▼
Kirim ke channel (WA / IG)
```

---

## 📝 System Prompt Template

Simpan di `business_settings` table, key: `ai_system_prompt`.
Template default yang bisa dikustomisasi pemilik toko:

```
Kamu adalah asisten AI untuk {business_name}.

Tentang bisnis kami:
{business_description}

Daftar produk:
{product_list}

Panduan menjawab:
1. Gunakan bahasa Indonesia yang santai, ramah, dan singkat
2. Jika pelanggan bertanya harga, berikan info akurat sesuai daftar produk
3. Jika pelanggan ingin memesan, tanyakan: nama produk, jumlah, dan alamat pengiriman
4. Jika ada pertanyaan yang tidak bisa dijawab, katakan: "Saya sambungkan ke admin kami ya 😊" — JANGAN mengarang jawaban
5. Balas maksimal 3-4 kalimat, jangan terlalu panjang

Deteksi Order (INTERNAL — jangan tampilkan ke pelanggan):
Jika kamu mendeteksi intent pembelian yang jelas dari percakapan, tambahkan tepat di baris terakhir responsmu (setelah teks balasan):
[ORDER_DETECTED: {"items": [{"name": "nama produk", "qty": 1, "price": 50000}], "total_estimate": 50000, "shipping_address": "alamat jika ada atau null"}]
```

---

## 💻 Implementasi AIAgentService

```php
<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIAgentService
{
    private const MAX_RETRIES = 2;
    private const MAX_HISTORY = 10;

    public function __construct(
        private readonly ContextBuilderService $contextBuilder,
        private readonly OrderExtractorService $orderExtractor,
    ) {}

    /**
     * Proses pesan masuk dan hasilkan balasan AI.
     *
     * @throws AIAgentException Jika Claude API gagal setelah retry
     */
    public function process(Conversation $conversation, Message $incomingMessage): string
    {
        $context = $this->contextBuilder->build($conversation);

        $response = $this->callClaude($context, attempt: 0);

        // Pisahkan reply teks vs ORDER_DETECTED tag
        [$replyText, $orderData] = $this->parseResponse($response);

        // Proses order jika terdeteksi
        if ($orderData) {
            $this->orderExtractor->save($conversation, $incomingMessage, $orderData);
        }

        return $replyText;
    }

    private function callClaude(array $context, int $attempt): string
    {
        try {
            $response = Http::withToken(config('services.anthropic.key'))
                ->withHeaders(['anthropic-version' => '2023-06-01'])
                ->timeout(30)
                ->post('https://api.anthropic.com/v1/messages', [
                    'model'      => 'claude-sonnet-4-20250514',
                    'max_tokens' => 1024,
                    'system'     => $context['system'],
                    'messages'   => $context['messages'],
                ]);

            if ($response->failed()) {
                throw new AIAgentException("Claude API error: {$response->status()}");
            }

            return $response->json('content.0.text');

        } catch (\Exception $e) {
            if ($attempt < self::MAX_RETRIES) {
                sleep(1); // backoff singkat
                return $this->callClaude($context, $attempt + 1);
            }

            Log::error('AIAgentService: Claude API gagal setelah retry', [
                'conversation_id' => $context['conversation_id'],
                'error' => $e->getMessage(),
            ]);

            throw new AIAgentException("AI tidak tersedia: {$e->getMessage()}");
        }
    }

    /**
     * Pisahkan teks balasan dari ORDER_DETECTED tag.
     * Return: [string $replyText, array|null $orderData]
     */
    private function parseResponse(string $raw): array
    {
        $pattern = '/\[ORDER_DETECTED:\s*(\{.*?\})\]/s';

        if (preg_match($pattern, $raw, $matches)) {
            $replyText = trim(preg_replace($pattern, '', $raw));
            $orderData = json_decode($matches[1], true);
            return [$replyText, $orderData];
        }

        return [trim($raw), null];
    }
}
```

---

## 🏗️ ContextBuilderService

```php
public function build(Conversation $conversation): array
{
    $settings   = BusinessSetting::getGroup('ai_prompt');
    $history    = $conversation->messages()
                    ->latest()
                    ->limit(self::MAX_HISTORY)
                    ->get()
                    ->reverse();

    $systemPrompt = $this->buildSystemPrompt($settings);

    $messages = $history->map(fn(Message $msg) => [
        'role'    => $msg->direction === 'inbound' ? 'user' : 'assistant',
        'content' => $msg->content,
    ])->values()->toArray();

    return [
        'conversation_id' => $conversation->id,
        'system'          => $systemPrompt,
        'messages'        => $messages,
    ];
}
```

---

## ⚡ Rate Limiting

```php
// Di ProcessIncomingMessage job — cegah spam AI call
use Illuminate\Support\Facades\RateLimiter;

$key = "ai-reply:{$conversation->id}";

if (RateLimiter::tooManyAttempts($key, maxAttempts: 1)) {
    // Skip AI, tunggu giliran berikutnya
    return;
}

RateLimiter::hit($key, decaySeconds: 3);
// lanjut proses AI...
```

---

## 🛡️ Graceful Degradation

Jika Claude API gagal, JANGAN biarkan percakapan terbengkalai:

```php
try {
    $reply = $this->aiAgent->process($conversation, $message);
    // kirim reply...
} catch (AIAgentException $e) {
    // Flag ke human, JANGAN crash
    $conversation->update([
        'ai_enabled' => false,
        'status'     => 'needs_human',
    ]);

    // Notif pemilik toko via broadcast
    broadcast(new AIFailedEvent($conversation));

    Log::warning('AI gagal, dialihkan ke human', [
        'conversation_id' => $conversation->id
    ]);
}
```

---

## 🚫 Hal yang DILARANG
- ❌ Menyimpan API key Claude di database
- ❌ Memanggil Claude API secara sinkron di request lifecycle
- ❌ Mengirim seluruh history chat ke Claude (max 10 pesan)
- ❌ Mengabaikan error Claude API — selalu handle dengan graceful degradation
- ❌ Mengekspos error Claude ke pelanggan — cukup fallback ke pesan "hubungi admin"
- ❌ Ganti model tanpa benchmark terlebih dahulu
