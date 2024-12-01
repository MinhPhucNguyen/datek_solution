<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_name',
        'slug',
        'description',
        'parent_id',
        'status'
    ];

    /**
     * Get the parent category that owns the category.
     */
    public function parentCategory(){
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the sub categories for the category.
     */
    public function subCategories(){
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the products for the category.
     */ 
    public function products(){
        return $this->belongsToMany(Product::class, 'product_categories');
    }

}
