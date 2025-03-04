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
            'Categories_id' => fake()->word(),
            'name' => fake()->name(),
            'description' => fake()->text(),
            'image' => 'https://picsum.photos/id/' . fake()->numberBetween(1, 1000) . '/200/300',
            'price' => fake()->numberBetween(-10000, 10000),
            'stock' => fake()->numberBetween(-10000, 10000),
        ];
    }
}
