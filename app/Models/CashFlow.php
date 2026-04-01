<?php

namespace App\Models;

use App\Traits\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashFlow extends Model
{
    use HasFactory, BelongsToStore;

    protected $fillable = ['store_id', 'date', 'type', 'source', 'amount', 'notes'];

    
}
