<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MealCategory extends Model
{
    use HasFactory;

    /*
     * The restaurants that belong to the Meal Category.
     */
    public function restaurants(): BelongsToMany {
        return $this->belongsToMany(Restaurant::class);
    }
}
