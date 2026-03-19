<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'channel',
        'channel_id',
        'name',
        'avatar_url',
        'tags',
        'notes',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeByChannel($query, string $channel)
    {
        return $query->where('channel', $channel);
    }
}
