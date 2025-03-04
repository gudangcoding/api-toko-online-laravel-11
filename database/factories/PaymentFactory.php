<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Payment;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => fake()->word(),
            'payment_type' => fake()->word(),
            'payment_code' => fake()->word(),
            'payment_status' => fake()->randomElement(["pending","paid","failed"]),
        ];
    }
}
