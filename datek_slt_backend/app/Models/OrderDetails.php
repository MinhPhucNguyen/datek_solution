<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_detail_id';

    protected $fillable = [
        'order_id',
        'product_id',
        'order_detail_quantity',
        'order_detail_price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
