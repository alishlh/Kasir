<?php

namespace App\Traits;

use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToStore
{
    public static function bootBelongsToStore(): void
    {
        static::addGlobalScope('store', function ($builder) {
            if (Filament::getTenant()) {
                $builder->where('store_id', Filament::getTenant()->id);
            }
        });

        static::creating(function ($model) {
            if (! $model->store_id && Filament::getTenant()) {
                $model->store_id = Filament::getTenant()->id;
            }
        });
    }

    public function stores(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
