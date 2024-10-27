<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\UserAddresses;

class UserAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach(User::all() as $user) {
            for($i = 0; $i < rand(1,3); $i++) {
                UserAddresses::create([
                    'user_id' => $user->id,
                    'address' => $faker->streetAddress(),
                    'city' => $faker->city(),
                    'ward' => $faker->streetSuffix(),
                    'district' => $faker->state(),
                ]);
            }
        }
    }
}
