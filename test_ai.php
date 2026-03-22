<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

// Try booting via console kernel or Http kernel depending on Laravel 11/12 changes
try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
} catch (\Exception $e) {
    // Laravel 11/12
    $app->boot();
}

use App\Services\AIProviderFactory;

$provider = AIProviderFactory::getProvider();
echo "Provider: " . $provider->getProviderName() . "\n";
echo "Configured: " . ($provider->isConfigured() ? 'YES ✅' : 'NO ❌') . "\n";

echo "\n=== TEST AI GENERATE ===\n";

try {
    $response = $provider->generate(
        'Kamu adalah AI assistant untuk Toko Sepatu Keren.',
        'Halo! Apa ada sepatu size 43?',
        []
    );
    echo "Response: " . substr($response, 0, 200) . "...\n";
    echo "\n✅ AI PROVIDER BERHASIL!\n";
} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
}
