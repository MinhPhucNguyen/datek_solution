<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'coupon_code',
        'sale_percentage',
        'is_active',
        'sale_begin_at',
        'sale_end_at',
        'sale_name',
        'is_active'
    ];
}
