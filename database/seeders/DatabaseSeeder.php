<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VariantValue;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::create([
            'name' => 'Admin',
            'email' => 'a@a.com',
            'password' => bcrypt('123'),
        ]);
        $this->call([
            UserSeeder::class,
            CategoriesSeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            VariantOptionSeeder::class,
            VariantValueSeeder::class
        ]);


        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
