<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    /**
     * @return BelongsToMany
     */
    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class)
            ->as('extra')
            ->withPivot('quantity');
    }

    /**
     * @return BelongsTo
     */
    public function mealCategory(): BelongsTo
    {
        return $this->belongsTo(MealCategory::class);
    }

    /**
     * @return BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
