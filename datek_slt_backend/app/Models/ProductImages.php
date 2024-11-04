<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'image_path'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
