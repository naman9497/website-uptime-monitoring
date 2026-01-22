<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteStatus extends Model
{
    use HasFactory;
    protected $table = 'website_status';

    protected $fillable = [
        'website_id',
        'current_status',
        'last_checked_at',
        'alert_sent',
        'alert_sent_at',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
        'alert_sent_at' => 'datetime',
        'alert_sent' => 'boolean',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
