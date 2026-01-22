<?php

namespace App\Models;

use App\Repositories\ClientRepository;
use App\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;
    use HasPublicUuid;

    protected $fillable = [
        'email',
    ];

    protected static function booted(): void
    {
        // Clear clients cache when a client is created, updated, or deleted
        static::created(fn() => app(ClientRepository::class)->clearCache());
        static::updated(fn() => app(ClientRepository::class)->clearCache());
        static::deleted(fn() => app(ClientRepository::class)->clearCache());
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }
}
