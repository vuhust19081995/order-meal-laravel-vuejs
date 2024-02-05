<?php

use App\Http\Controllers\MealCategoryController;
use App\Http\Controllers\MealOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(MealCategoryController::class)->group(function (){
    Route::get('/meal-categories', 'index')->name('meal-category.index');
    Route::get('/meal-categories/{meal_category}/restaurants', 'getRestaurantsByMealCategory')->name('meal-category.getRestaurants');
    Route::get('/meal-categories/{meal_category_id}/{restaurant_id}/dishes', 'getDishesByMealCategoryRestaurant')->name('meal-category.getDishes');
});

Route::controller(MealOrderController::class)->group(function (){
    Route::get('/meal-orders', 'index')->name('meal-order.index');
    Route::post('/meal-orders', 'store')->name('meal-order.store');
});
