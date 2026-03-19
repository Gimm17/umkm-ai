<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * List all orders with pagination and filtering.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Order::with(['conversation.contact', 'conversation'])
                ->orderBy('created_at', 'desc');

            // Filter by status
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Filter by channel
            if ($request->has('channel') && $request->channel !== 'all') {
                $query->whereHas('conversation', function ($q) use ($request) {
                    $q->where('channel', $request->channel);
                });
            }

            $orders = $query->paginate($request->per_page ?? 20);

            return response()->json([
                'data' => $orders->items(),
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ],
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to load orders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Gagal memuat order',
                'error' => app()->isProduction() ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single order.
     */
    public function show(Order $order): JsonResponse
    {
        try {
            $order->load(['conversation.contact', 'conversation']);

            return response()->json([
                'data' => $order,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to load order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal memuat order',
                'error' => app()->isProduction() ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update order status.
     */
    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        try {
            $order->update([
                'status' => $request->status,
            ]);

            // Log the action
            Log::info('Order status updated', [
                'order_id' => $order->id,
                'old_status' => $order->getOriginal('status'),
                'new_status' => $request->status,
            ]);

            return response()->json([
                'data' => $order->fresh(),
                'message' => 'Order berhasil diupdate',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to update order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal mengupdate order',
                'error' => app()->isProduction() ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get order statistics.
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total' => Order::count(),
                'detected' => Order::where('status', 'detected')->count(),
                'confirmed' => Order::where('status', 'confirmed')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
                'total_value' => Order::where('status', 'confirmed')->sum('total_estimate'),
            ];

            return response()->json([
                'data' => $stats,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to load order stats', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal memuat statistik',
                'error' => app()->isProduction() ? null : $e->getMessage(),
            ], 500);
        }
    }
}

