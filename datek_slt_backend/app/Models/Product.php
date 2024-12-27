<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'brand_id',
        'name',
        'sku',
        'price',
        'quantity',
        'description',
        'status',
        'detailed_specifications'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImages::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetails::class);
    }

    public function orderdetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function relatedProducts()
    {
        $priceRange = 0.1 * $this->price;
        $minPrice = $this->price - $priceRange;
        $maxPrice = $this->price + $priceRange;

        return self::where('id', '!=', $this->id)
            ->where(function ($query) use ($minPrice, $maxPrice) {
                $query->where('category_id', $this->category_id)
                    ->orWhere('name', 'LIKE', '%' . $this->name . '%')
                    ->orWhereBetween('price', [$minPrice, $maxPrice]);
            })
            ->take(10)
            ->get();
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
