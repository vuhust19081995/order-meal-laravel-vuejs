<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Dish;
use App\Models\DishMealCategory;
use App\Models\MealCategory;
use App\Models\MealCategoryRestaurant;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        /* Seed meal categories */
        MealCategory::insert([
                ['name' => 'breakfast', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'lunch', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'dinner', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]
        );

        /* Seed related data table */
        $dishes = json_decode(File::get(database_path('data/dishes.json')))->dishes;
        DB::transaction(function () use ($dishes) {
            foreach ($dishes as $dish) {
                $dishModel = Dish::query()->firstOrCreate(['name' => $dish->name]);
                $restaurant = Restaurant::query()->firstOrCreate(['name' => $dish->restaurant]);
                $mealCategoryIds = MealCategory::query()->whereIn('name', $dish->availableMeals)->pluck('id');
                foreach ($mealCategoryIds as $mealCategoryId) {
                    $mealCategoryRes = MealCategoryRestaurant::query()->firstOrCreate([
                        'meal_category_id' => $mealCategoryId,
                        'restaurant_id' => $restaurant->id
                    ]);
                    DishMealCategory::query()->firstOrCreate([
                        'dish_id' => $dishModel->id,
                        'meal_category_restaurant_id' => $mealCategoryRes->id
                    ]);
                }
            }
        });
    }
}
