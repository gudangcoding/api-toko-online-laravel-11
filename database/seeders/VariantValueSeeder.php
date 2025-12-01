<?php

namespace Database\Seeders;

use App\Models\VariantValue;
use Illuminate\Database\Seeder;

class VariantValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VariantValue::factory()->count(5)->create();
    }
}
