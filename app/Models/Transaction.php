<?php

namespace App\Models;

use App\Traits\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, BelongsToStore;

    protected $fillable = [
        'store_id',
        'payment_method_id',
        'transaction_number',
        'name',
        'email',
        'phone',
        'address',
        'notes',
        'total',
        'cash_received',
        'change',
        'is_bpjs',
        'user_id',
        'jasa_dokter',
        'jasa_tindakan',
        'payment_status',
        'snap_token',
        // 'payment_type'
    ];
    protected $casts = [
        'is_bpjs' => 'boolean',
    ];

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function products()
    {
        return $this->transactionItems()->with('product');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
