<?php

namespace App\Services;

use App\Models\BusinessSetting;
use App\Models\Conversation;

class ContextBuilderService
{
    private const MAX_HISTORY = 10;

    /**
     * Build context untuk Claude API.
     */
    public function build(Conversation $conversation): array
    {
        $settings = BusinessSetting::getGroup('ai_prompt');
        $history = $conversation->messages()
            ->latest()
            ->limit(self::MAX_HISTORY)
            ->get()
            ->reverse();

        $systemPrompt = $this->buildSystemPrompt($settings);

        $messages = $history->map(fn (Message $msg) => [
            'role' => $msg->direction === 'inbound' ? 'user' : 'assistant',
            'content' => $msg->content,
        ])->values()->toArray();

        return [
            'conversation_id' => $conversation->id,
            'system' => $systemPrompt,
            'messages' => $messages,
        ];
    }

    /**
     * Build system prompt dari business settings.
     */
    private function buildSystemPrompt(array $settings): string
    {
        $businessName = $settings['business_name'] ?? 'Toko Kami';
        $businessDescription = $settings['business_description'] ?? 'Toko online terpercaya';
        $productList = $settings['product_list'] ?? 'Hubungi admin untuk info produk';

        return <<<PROMPT
Kamu adalah asisten AI untuk {$businessName}.

Tentang bisnis kami:
{$businessDescription}

Daftar produk:
{$productList}

Panduan menjawab:
1. Gunakan bahasa Indonesia yang santai, ramah, dan singkat
2. Jika pelanggan bertanya harga, berikan info akurat sesuai daftar produk
3. Jika pelanggan ingin memesan, tanyakan: nama produk, jumlah, dan alamat pengiriman
4. Jika ada pertanyaan yang tidak bisa dijawab, katakan: "Saya sambungkan ke admin kami ya 😊" — JANGAN mengarang jawaban
5. Balas maksimal 3-4 kalimat, jangan terlalu panjang

Deteksi Order (INTERNAL — jangan tampilkan ke pelanggan):
Jika kamu mendeteksi intent pembelian yang jelas dari percakapan, tambahkan tepat di baris terakhir responsmu (setelah teks balasan):
[ORDER_DETECTED: {"items": [{"name": "nama produk", "qty": 1, "price": 50000}], "total_estimate": 50000, "shipping_address": "alamat jika ada atau null"}]
PROMPT;
    }
}
