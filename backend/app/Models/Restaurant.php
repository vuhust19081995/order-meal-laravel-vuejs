<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Restaurant extends Model
{
    use HasFactory;

    /*
     * The meal categories that belong to the restaurant.
     */
    public function mealCategories(): BelongsToMany {
        return $this->belongsToMany(MealCategory::class);
    }
}
