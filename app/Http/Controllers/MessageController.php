<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\Contracts\ChannelServiceInterface;
use App\Services\InstagramService;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Get messages for a conversation.
     */
    public function index(Request $request, Conversation $conversation): JsonResponse
    {
        try {
            $messages = $conversation->messages()
                ->orderBy('created_at', 'asc')
                ->paginate($request->per_page ?? 50);

            return response()->json([
                'data' => $messages->items(),
                'meta' => [
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'per_page' => $messages->perPage(),
                    'total' => $messages->total(),
                ],
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to load messages', [
                'conversation_id' => $conversation->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal memuat pesan',
                'error' => app()->isProduction() ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send manual reply.
     */
    public function store(StoreMessageRequest $request, Conversation $conversation): JsonResponse
    {
        try {
            // Create message record
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'direction' => 'outbound',
                'content' => $request->content,
                'message_type' => 'text',
                'sent_by' => 'human',
            ]);

            // Get appropriate channel service
            $channelService = $this->getChannelService($conversation);

            if (! $channelService) {
                throw new \Exception("Unsupported channel: {$conversation->channel}");
            }

            // Send via channel service
            $sentMessage = $channelService->sendTextWithRetry(
                $conversation,
                $request->content
            );

            if (! $sentMessage) {
                throw new \Exception('Failed to send message after retries');
            }

            // Update conversation
            $conversation->update([
                'last_message_at' => now(),
            ]);

            Log::info('Manual reply sent successfully', [
                'message_id' => $message->id,
                'conversation_id' => $conversation->id,
                'channel' => $conversation->channel,
            ]);

            return response()->json([
                'data' => $message->fresh(),
                'message' => 'Pesan terkirim',
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Failed to send manual reply', [
                'conversation_id' => $conversation->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal mengirim pesan',
                'error' => app()->isProduction() ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get appropriate channel service based on conversation channel.
     */
    private function getChannelService(Conversation $conversation): ?ChannelServiceInterface
    {
        return match ($conversation->channel) {
            'whatsapp' => app(WhatsAppService::class),
            'instagram' => app(InstagramService::class),
            default => null,
        };
    }
}
