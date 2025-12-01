<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => ProductVariant::factory(),
            'quantity' => fake()->numberBetween(-10000, 10000),
            'price' => fake()->numberBetween(-10000, 10000),
            'variant_model' => fake()->word(),
            'variant_color' => fake()->word(),
            'variant_size' => fake()->word(),
        ];
    }
}
