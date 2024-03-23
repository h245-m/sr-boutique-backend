<?php

namespace App\Models;

use App\Http\Traits\CustomRateable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory , CustomRateable , InteractsWithMedia;

    public $fillable = ['name', 'long_description', 'short_description', 'price', 'type', 'category_id' , 'live' , 'quantity'];
    
    // public $casts = [
    //     "category_id" => "integer",
    //     "price" => "float",
    // ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // The users had this product in their cart
    public function users()
    {
        return $this->belongsToMany(User::class , 'product_user')->withPivot('quantity');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class , 'order_product')->withPivot('quantity');
    }

    public function attributes(){
        return $this->hasMany(Attribute::class);
    }

    public function scopeIsLive($query , bool $live)
    {
        return $query->where('live', $live);
    }

    
}
