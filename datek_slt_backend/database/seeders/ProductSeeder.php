<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 0; $i < 20; $i++) {
            Product::create([
                'name' => $faker->name(),
                'sku' => $faker->unique()->randomNumber(8),
                'price' => $faker->randomFloat(2, 0, 1000),
                'quantity' => $faker->numberBetween(0, 100),
                'description' => $faker->sentence(),
                'status' => $faker->numberBetween(0,1),
                'product_type_id' => $faker->numberBetween(1, 3),
                'brand_id' =>  Brand::all()->random()->id
            ]);
        }
    }
}
