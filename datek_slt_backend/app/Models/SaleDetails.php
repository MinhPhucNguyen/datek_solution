<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'sale_id',
        'product_id',
        'sale_percentage'
    ];

    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
