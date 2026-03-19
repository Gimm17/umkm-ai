<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    /**
     * List all conversations with pagination.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Conversation::with('contact')
                ->orderBy('last_message_at', 'desc');

            // Filter by channel
            if ($request->has('channel') && $request->channel !== 'all') {
                $query->where('channel', $request->channel);
            }

            // Filter by status
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Search by contact name
            if ($request->has('search') && $request->search) {
                $query->whereHas('contact', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                });
            }

            $conversations = $query->paginate($request->per_page ?? 20);

            return response()->json([
                'data' => $conversations->items(),
                'meta' => [
                    'current_page' => $conversations->currentPage(),
                    'last_page' => $conversations->lastPage(),
                    'per_page' => $conversations->perPage(),
                    'total' => $conversations->total(),
                ],
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to load conversations', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Gagal memuat percakapan',
                'error' => app()->isProduction() ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single conversation with messages.
     */
    public function show(Conversation $conversation): JsonResponse
    {
        try {
            $conversation->load(['contact', 'messages' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }]);

            // Mark as read
            if ($conversation->unread_count > 0) {
                $conversation->update(['unread_count' => 0]);
            }

            return response()->json([
                'data' => $conversation,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to load conversation', [
                'conversation_id' => $conversation->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal memuat percakapan',
                'error' => app()->isProduction() ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle AI on/off for conversation.
     */
    public function toggleAI(Request $request, Conversation $conversation): JsonResponse
    {
        $request->validate([
            'ai_enabled' => 'required|boolean',
        ]);

        try {
            $conversation->update([
                'ai_enabled' => $request->ai_enabled,
                'status' => $request->ai_enabled ? 'open' : 'needs_human',
            ]);

            // Log the action
            Log::info('AI toggled', [
                'conversation_id' => $conversation->id,
                'ai_enabled' => $request->ai_enabled,
                'status' => $conversation->status,
            ]);

            return response()->json([
                'data' => $conversation->fresh(),
                'message' => $request->ai_enabled ? 'AI diaktifkan' : 'AI dinonaktifkan',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to toggle AI', [
                'conversation_id' => $conversation->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal mengubah status AI',
                'error' => app()->isProduction() ? null : $e->getMessage(),
            ], 500);
        }
    }
}

