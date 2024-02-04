<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MealCategoryRestaurant extends Model
{
    use HasFactory;

    protected $table = 'meal_category_restaurant';

    /**
     * The dishes that belong to the meal category of restaurant
     *
     * @return BelongsToMany
     */
    public function dishes(): BelongsToMany {
        return $this->belongsToMany(Dish::class, 'dish_meal_category');
    }
}
