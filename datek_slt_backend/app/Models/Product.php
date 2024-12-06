<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_type_id',
        'brand_id',
        'name',
        'sku',
        'price',
        'quantity',
        'description',
        'status',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImages::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function saledetails()
    {
        return $this->hasMany(SaleDetails::class);
    }

    public function scopeSearch($query, $search)
    {
        $search = "%$search%";

        $query->where(function ($query) use ($search) {
            $query->where('products.name', 'like', $search)
                ->orWhere('products.id', 'like', $search)
                ->orWhere('products.sku', 'like', $search);
        })->orWhereHas('brand', function ($query) use ($search) {
            $query->where('brand_name', 'like', $search);
        });
    }
}
