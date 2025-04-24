<?php

namespace App\Models;

use App\Enums\ProductStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'price', 'status', 'category_id', 'is_active'];

    protected $casts = [
        'status' => ProductStatusEnum::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

//    public function price(): Attribute
//    {
//        return Attribute::make(
//            get: fn (string $value) => $value / 100,
//            set: fn (string $value) => $value * 100,
//        );
//    }
}
