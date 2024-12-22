<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'user_id',
        'order_total_price',
        'order_status',
        'order_date',
        'payment_method',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shippingAddress()
    {
        return $this->hasOne(ShippingAddress::class, 'order_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('order_id', 'like', '%' . $search . '%')
            ->orWhere('order_status', 'like', '%' . $search . '%')
            ->orWhere('order_date', 'like', '%' . $search . '%')
            ->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
    }
}
