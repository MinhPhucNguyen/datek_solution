<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $passwordMatch = Hash::check('password', Hash::make('password'));

        for ($i = 0; $i < 25; $i++) {
            User::create([
                'firstname' => $faker->firstName(),
                'lastname' => $faker->lastName(),
                'username' => $faker->userName(),
                'gender' => $faker->numberBetween(0,1),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->numerify('##########'),
                'password' => Hash::make('password'),
                'confirm_password' => $passwordMatch ? 'true' : 'false',
                'avatar' => 'default.jpg',
                'birth' => $faker->date(),
                'status' => $faker->numberBetween(0,1),
                'role_as' => $faker->numberBetween(0,1),
            ]);
        }
    }
}
