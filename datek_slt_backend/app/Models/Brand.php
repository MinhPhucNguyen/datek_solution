<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'brands';

    protected $primaryKey = 'id';

    protected $fillable = ['brand_name', 'brand_logo', 'brand_description', 'status'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
