<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
} catch (\Exception $e) {
    $app->boot();
}

use App\Models\BusinessSetting;

$settings = [
    ['key' => 'store_name', 'value' => 'Toko Sepatu Keren', 'group' => 'general'],
    ['key' => 'store_description', 'value' => 'Toko sepatu online dengan kualitas terbaik', 'group' => 'general'],
    ['key' => 'products', 'value' => "Sepatu Sneaker - Rp 250.000\nSepatu Formal - Rp 350.000\nSepatu Olahraga - Rp 300.000", 'group' => 'general'],
    ['key' => 'custom_prompt', 'value' => 'Kamu adalah AI assistant yang ramah dan profesional.', 'group' => 'ai_prompt'],
];

foreach ($settings as $setting) {
    BusinessSetting::updateOrCreate(
        ['key' => $setting['key']],
        ['value' => $setting['value'], 'group' => $setting['group']]
    );
}

echo "✅ Business settings have been successfully updated!\n";
