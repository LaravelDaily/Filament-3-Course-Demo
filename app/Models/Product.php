<?php

namespace App\Models;

use App\Enums\ProductStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'status', 'category_id'];

    protected $casts = [
        'status' => ProductStatusEnum::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

//    public function price(): Attribute
//    {
//        return Attribute::make(
//            get: fn (string $value) => $value / 100,
//            set: fn (string $value) => $value * 100,
//        );
//    }
}
