<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('conversation.{id}', function ($user, $id) {
    // For now, allow all authenticated users to listen to any conversation
    // TODO: Add proper authorization based on business ownership
    return true;
});

Broadcast::channel('orders', function ($user) {
    // Allow authenticated users to listen to order updates
    return true;
});
