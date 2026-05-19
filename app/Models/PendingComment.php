<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingComment extends Model
{
    protected $table = 'pending_comments';

    protected $fillable = [
        'user_id',
        'order_id',
        'instrument_id',
        'has_commented',
    ];

    protected $casts = [
        'has_commented' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
