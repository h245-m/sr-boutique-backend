<?php

namespace App\Models;

use App\Enums\AttributeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'value' , 'product_id'];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => AttributeType::fromValue($value)->key,
            set: fn ($value) => AttributeType::fromKey($value),
        );
    }
}
