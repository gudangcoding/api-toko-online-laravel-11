<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ProductVariant;
use App\Models\VariantOption;
use App\Models\VariantValue;

class VariantValueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VariantValue::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_variant_id' => ProductVariant::factory(),
            'variant_option_id' => VariantOption::factory(),
            'value' => fake()->word(),
        ];
    }
}
