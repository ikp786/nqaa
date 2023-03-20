<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',        
        'unique_id',
        'product_quantity',
        'product_amount',
        'total_amount',
        'device_token',
        'address',
        'address_type'
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTotalPrice()
    {
        return $this->product_quantity * $this->product->amount;
    }

}
