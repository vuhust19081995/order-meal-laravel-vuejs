<?php

namespace App\Http\Controllers;

use App\Models\MealCategory;
use App\Models\MealCategoryRestaurant;
use Illuminate\Http\JsonResponse;

class MealCategoryController extends Controller
{
    /**
     * Return list of meal categories
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $mealCategories = MealCategory::all(['id', 'name']);

        return response()->json(['success' => true, 'data' => $mealCategories]);
    }

    /**
     * @param MealCategory $mealCategory
     * @return JsonResponse
     */
    public function getRestaurantsByMealCategory(MealCategory $mealCategory): \Illuminate\Http\JsonResponse
    {
        $restaurants = $mealCategory->restaurants->map(function ($item) {
            return ['id' => $item->id, 'name' => $item->name];
        });

        return response()->json(['success' => true, 'data' => $restaurants]);
    }

    /**
     * @param $mealCategoryId
     * @param $restaurantId
     * @return JsonResponse
     */
    public function getDishesByMealCategoryRestaurant($mealCategoryId, $restaurantId): \Illuminate\Http\JsonResponse
    {
        $dishes = MealCategoryRestaurant::query()
            ->where(['meal_category_id' => $mealCategoryId, 'restaurant_id' => $restaurantId])
            ->first()->dishes;

        return response()->json(['success' => true, 'data' => $dishes]);
    }
}
