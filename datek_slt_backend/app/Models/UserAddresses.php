<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserAddresses extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'address',
        'city',
        'ward',
        'district',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
