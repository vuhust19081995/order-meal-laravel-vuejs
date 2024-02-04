<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meal_category_id',
        'restaurant_id',
        'number_of_people'
    ];

    public function dishOrders(): HasMany
    {
        return $this->hasMany(DishOrder::class);
    }
}
