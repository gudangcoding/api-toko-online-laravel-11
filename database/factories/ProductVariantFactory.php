<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ProductVariant;

class ProductVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id ?? Product::factory(),
            'sku' => fake()->word(),
            'price' => fake()->randomFloat(2, 1000, 99999999),
            'stock' => fake()->numberBetween(0, 10000),
            'image'=>$this->faker->imageUrl(640,480,'clothes',true),
        ];
    }
}
