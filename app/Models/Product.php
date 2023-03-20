<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Product extends Model
{
    use HasFactory;
    
    protected $fillable = ['name','description','price','merchant_price','status','image','name_ar_qa','description_ar_qa'];

    // protected function image(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) => 'm',
    //         // set: fn (string $value) => strtolower($value),
    //     );
    // }

    public function getImageAttribute($value)
    {
        return $value != null ? asset('images/'.$value) : asset('default/images.png');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    
    

}
