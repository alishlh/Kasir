<?php

namespace App\Models;

use App\Traits\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes, BelongsToStore;

    protected $fillable = ['store_id', 'name'];

    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
}
