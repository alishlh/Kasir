<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo', 'name', 'phone', 'address', 'print_via_bluetooth', 'name_printer_local'
    ];
}