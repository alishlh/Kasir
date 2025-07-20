<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'product_id', 'quantity', 'price', 'cost_price', 'total_profit'
    ];

   // Relasi normal (produk yang tidak dihapus saja)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi alternatif yang menyertakan produk terhapus
    public function productWithTrashed()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }


    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
