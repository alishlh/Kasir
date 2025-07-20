<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'stock',
        'cost_price',
        'price',
        'image',
        'barcode',
        'sku',
        'description',
        'is_active',
        'expiry_date',
    ];

    public function isNearExpiry(): bool
    {
        return $this->expiry_date && $this->expiry_date <= now()->addMonths(3);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
