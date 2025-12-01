<?php

namespace Database\Seeders;

use App\Models\VariantOption;
use Illuminate\Database\Seeder;

class VariantOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VariantOption::factory()->count(5)->create();
    }
}
