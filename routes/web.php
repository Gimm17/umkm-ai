<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::prefix('inbox')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Inbox/Index');
    })->name('inbox.index');

    Route::get('/{conversation}', function ($conversationId) {
        // Fetch conversation data
        $conversation = \App\Models\Conversation::with('contact')->findOrFail($conversationId);

        return Inertia::render('Inbox/Show', [
            'conversation' => $conversation,
        ]);
    })->name('inbox.show');
});

Route::prefix('orders')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Orders/Index');
    })->name('orders.index');
});

Route::prefix('settings')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Settings/Index');
    })->name('settings.index');
});
