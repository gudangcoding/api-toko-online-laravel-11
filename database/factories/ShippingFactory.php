<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Shipping;

class ShippingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shipping::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => fake()->word(),
            'courier' => fake()->word(),
            'service' => fake()->word(),
            'cost' => fake()->numberBetween(-10000, 10000),
            'tracking_number' => fake()->word(),
        ];
    }
}
