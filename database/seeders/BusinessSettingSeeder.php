<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Business Information
        DB::table('business_settings')->updateOrInsert(
            ['key' => 'business_name'],
            [
                'value' => 'Toko Sepatu Keren',
                'group' => 'ai_prompt',
            ]
        );

        DB::table('business_settings')->updateOrInsert(
            ['key' => 'business_description'],
            [
                'value' => 'Toko sepatu online dengan kualitas terbaik dan harga terjangkau. Kami menyediakan berbagai model sepatu untuk kebutuhan sehari-hari dan olahraga.',
                'group' => 'ai_prompt',
            ]
        );

        // Product Catalog
        DB::table('business_settings')->updateOrInsert(
            ['key' => 'product_list'],
            [
                'value' => 'Sepatu Sneaker - Rp 250.000
Sepatu Formal - Rp 350.000
Sepatu Olahraga - Rp 300.000
Sepatu Casual - Rp 275.000
Sepatu Anak - Rp 200.000',
                'group' => 'ai_prompt',
            ]
        );

        // AI System Prompt
        DB::table('business_settings')->updateOrInsert(
            ['key' => 'ai_system_prompt'],
            [
                'value' => 'Kamu adalah AI assistant untuk Toko Sepatu Keren. Tugasmu membantu pelanggan dengan informasi produk dan membantu mereka memesan sepatu.

CARA RESPON:
1. Greet pelanggan dengan ramah
2. Tanyakan kebutuhan mereka
3. Rekomendasikan produk yang sesuai
4. Bantu proses pemesanan

DETEKSI ORDER:
Kalau pelanggan menunjukkan intent membeli (kata kunci: "pesan", "beli", "ambil", "mau", "saya ambil"), deteksi order dengan format:
ORDER_DETECTED
{"product": "nama produk", "quantity": jumlah, "total": "harga total"}

Contoh:
Pelanggan: "Saya mau pesan 2 sepatu sneaker"
Kamu: Baik, 2 Sepatu Sneaker sudah dicatat. Total: Rp 500.000
ORDER_DETECTED
{"product": "Sepatu Sneaker", "quantity": 2, "total": 500000}

PENTING:
- Jawab dalam Bahasa Indonesia
- Ramah dan profesional
- Fokus membantu pelanggan memesan',
                'group' => 'ai_prompt',
            ]
        );

        $this->command->info('✅ Business settings seeded successfully!');
        $this->command->info('   - Business Name: Toko Sepatu Keren');
        $this->command->info('   - Product Catalog: 5 products');
        $this->command->info('   - AI System Prompt: Configured');
    }
}
