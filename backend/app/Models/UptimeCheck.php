<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UptimeCheck extends Model
{
    protected $fillable = [
        'website_id',
        'status',
        'response_time_ms',
        'http_status_code',
        'error_type',
        'error_message',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'response_time_ms' => 'integer',
        'http_status_code' => 'integer',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function scopeLast24Hours($query)
    {
        return $query->where('checked_at', '>=', now()->subDay());
    }

    public function scopeLast7Days($query)
    {
        return $query->where('checked_at', '>=', now()->subDays(7));
    }

    public function scopeDown($query)
    {
        return $query->where('status', 'down');
    }
}
