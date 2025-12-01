<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'category_id' => fake()->word(),
            'slug' => fake()->slug(),
            'description' => fake()->text(1000),
            'base_price' => fake()->randomFloat(0, 1000, 99999999),
            'rating' => fake()->randomFloat(2, 0, 9.99),
            'likes' => fake()->numberBetween(0, 10000),
            'stock' => fake()->numberBetween(0, 10000),
        ];
    }
}
