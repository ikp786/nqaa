<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'order_number',
        'transaction_number',
        'mobile',
        'quantity',
        'price',
        'merchant_price',
        'status',
        'address',
        'address_type',
        'image',
        'image2',
    ];

    function orderProducts(){
        return $this->hasMany(OrderProduct::class);
    }

    public function getImageAttribute($value)
    {
        return $value != null ? asset('images/'.$value) : asset('default/images.png');
    }

    public function getImage2Attribute($value)
    {
        return $value != null ? asset('images/'.$value) : asset('default/images.png');
    }
}
