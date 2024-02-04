<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dish extends Model
{
    use HasFactory;

    /**
     * The meal categories of the restaurant that have the dish
     *
     * @return BelongsToMany
     */
    public function mealCategoryRestaurants(): BelongsToMany {
        return $this->belongsToMany(MealCategoryRestaurant::class, 'dish_meal_category');
    }
}
