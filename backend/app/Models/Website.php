<?php

namespace App\Models;

use App\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Website extends Model
{
    use HasFactory;
    use HasPublicUuid;

    protected $fillable = [
        'url',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function uptimeChecks(): HasMany
    {
        return $this->hasMany(UptimeCheck::class);
    }

    public function currentStatus(): HasOne
    {
        return $this->hasOne(WebsiteStatus::class);
    }
}
