<?php

namespace App\Models;

use App\Traits\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory, BelongsToStore;

    protected $fillable = ['store_id', 'type', 'source', 'total', 'notes'];

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }
}
