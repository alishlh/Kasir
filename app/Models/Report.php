<?php

namespace App\Models;

use App\Traits\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory, BelongsToStore;

    protected $fillable = ['store_id', 'name', 'report_type', 'start_date', 'end_date', 'path_file'];
}
