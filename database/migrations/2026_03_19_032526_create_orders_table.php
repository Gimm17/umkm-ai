<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->json('items')->comment('Order items: [{"name": "product", "qty": 1, "price": 50000}]');
            $table->decimal('total_estimated', 12, 2)->default(0)->comment('Estimated total order value');
            $table->enum('status', ['detected', 'confirmed', 'cancelled'])->default('detected')->comment('Order confirmation status');
            $table->text('raw_text')->nullable()->comment('Original text where order was detected');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['conversation_id', 'status']);
            $table->index('contact_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
