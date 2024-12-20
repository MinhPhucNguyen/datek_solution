<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductType;
use Faker\Factory as Faker;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 0; $i < 3; $i++) {
            ProductType::create([
                'type_name' => $faker->name(),
                'description' => $faker->sentence(),
            ]);
        }
    }
}
