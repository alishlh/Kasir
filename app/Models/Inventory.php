<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'source', 'total', 'notes'];

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }
}

