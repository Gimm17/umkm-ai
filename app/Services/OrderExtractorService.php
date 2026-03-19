<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderExtractorService
{
    /**
     * Save order dari AI detection.
     */
    public function save(Conversation $conversation, Message $message, array $orderData): Order
    {
        try {
            $order = Order::create([
                'conversation_id' => $conversation->id,
                'contact_id' => $conversation->contact_id,
                'items' => $orderData['items'] ?? [],
                'total_estimated' => $orderData['total_estimate'] ?? 0,
                'status' => 'detected',
                'raw_text' => $message->content,
            ]);

            Log::info('Order detected and saved', [
                'order_id' => $order->id,
                'conversation_id' => $conversation->id,
                'total_estimate' => $order->total_estimated,
            ]);

            return $order;

        } catch (\Throwable $e) {
            Log::error('Failed to save order', [
                'conversation_id' => $conversation->id,
                'error' => $e->getMessage(),
                'order_data' => $orderData,
            ]);

            throw $e;
        }
    }
}
