<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
$accessToken = env('WHATSAPP_ACCESS_TOKEN');

// Test number (ganti dengan nomor WhatsApp Anda, format: 628xxx)
$testNumber = '6281234567890'; // GANTI DENGAN NOMOR ANDA!

try {
    $response = Http::withToken($accessToken)
        ->timeout(30)
        ->post("https://graph.facebook.com/v18.0/{$phoneNumberId}/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $testNumber,
            'type' => 'text',
            'text' => [
                'body' => '🎉 Tes dari UmkmAI! Ini adalah pesan test.',
            ],
        ]);

    if ($response->successful()) {
        echo "✅ BERHASIL! Pesan terkirim ke {$testNumber}\n";
        echo 'Response: '.$response->body()."\n";
    } else {
        echo "❌ GAGAL! Status: {$response->status()}\n";
        echo 'Error: '.$response->body()."\n";
    }

} catch (Exception $e) {
    echo '❌ EXCEPTION: '.$e->getMessage()."\n";
}
