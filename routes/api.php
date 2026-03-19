<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Webhook endpoints (no middleware, CSRF excluded)
Route::get('/webhook/whatsapp', [WebhookController::class, 'verifyWhatsApp']);
Route::post('/webhook/whatsapp', [WebhookController::class, 'handleWhatsApp']);

Route::get('/webhook/instagram', [WebhookController::class, 'verifyInstagram']);
Route::post('/webhook/instagram', [WebhookController::class, 'handleInstagram']);

// Conversation endpoints
Route::prefix('conversations')->group(function () {
    Route::get('/', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::patch('/{conversation}/toggle-ai', [ConversationController::class, 'toggleAI'])->name('conversations.toggle-ai');

    // Message endpoints (nested under conversations)
    Route::prefix('{conversation}/messages')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('conversations.messages.index');
        Route::post('/', [MessageController::class, 'store'])->name('conversations.messages.store');
    });
});

// Order endpoints
Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/stats', [OrderController::class, 'stats'])->name('orders.stats');
    Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/{order}', [OrderController::class, 'update'])->name('orders.update');
});

// Settings endpoints
Route::prefix('settings')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/test-whatsapp', [SettingsController::class, 'testWhatsApp'])->name('settings.test-whatsapp');
    Route::post('/test-instagram', [SettingsController::class, 'testInstagram'])->name('settings.test-instagram');
});
