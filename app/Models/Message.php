<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'direction',
        'content',
        'message_type',
        'sent_by',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function scopeInbound($query)
    {
        return $query->where('direction', 'inbound');
    }

    public function scopeOutbound($query)
    {
        return $query->where('direction', 'outbound');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('message_type', $type);
    }

    public function isAiGenerated(): bool
    {
        return $this->direction === 'outbound' && $this->sent_by === 'ai';
    }

    public function isFromCustomer(): bool
    {
        return $this->direction === 'inbound';
    }
}
