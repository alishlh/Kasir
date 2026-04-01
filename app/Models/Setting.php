<?php

namespace App\Models;

use App\Traits\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory, BelongsToStore;

    protected $fillable = [
        'store_id', 'logo', 'name', 'phone', 'address', 'print_via_bluetooth', 'name_printer_local'
    ];
}