<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'sub_category_id',
        'product_name',
        'slug',
        'description',
        'quantity',
        'price',
        'status'
    ];
}
