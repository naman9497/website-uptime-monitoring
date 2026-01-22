<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

trait HasPublicUuid
{
    // We "use" the core Laravel trait inside our custom one
    use HasUuids;

    /**
     * Define which columns should be treated as UUIDs.
     * By putting this here, every model using this trait
     * will automatically fill the 'uuid' column.
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
