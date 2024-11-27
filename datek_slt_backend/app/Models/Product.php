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
        'product_type_id',
        'status'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'product_sub_category');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImages::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }
}
