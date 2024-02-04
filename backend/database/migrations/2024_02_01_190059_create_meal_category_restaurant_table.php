<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meal_category_restaurant', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('meal_category_id');
            $table->unsignedSmallInteger('restaurant_id');
            $table->timestamps();

            $table->foreign('meal_category_id')->references('id')->on('meal_categories');
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_category_restaurant');
    }
};
