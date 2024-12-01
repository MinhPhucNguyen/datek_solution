<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'sale_begin_at',
        'sale_end_at'
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetails::class);
    }
}
