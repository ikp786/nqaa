<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'order_id',
        'product_id',
        'product_name',
        'product_name_ar_qa',
        'product_description',
        'product_description_ar_qa',
        'price',
        'merchant_price',
        'quantity',
        'address',
        'address_type'
    ];

    function product(){
        return $this->belongsTo(Product::class);
    }
}
