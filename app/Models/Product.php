<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price'];

//    public function price(): Attribute
//    {
//        return Attribute::make(
//            get: fn (string $value) => $value / 100,
//            set: fn (string $value) => $value * 100,
//        );
//    }
}
