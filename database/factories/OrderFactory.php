<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\User;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total' => fake()->numberBetween(-10000, 10000),
            'status' => fake()->randomElement(["pending","paid","shipped","delivered","canceled"]),
            'payment_status' => fake()->randomElement(["unpaid","paid"]),
            'shipping_status' => fake()->randomElement(["pending","shipped","delivered","canceled"]),
            'shipping_id' => Shipping::factory(),
            'payment_id' => Payment::factory(),
        ];
    }
}
