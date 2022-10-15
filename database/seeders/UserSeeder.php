<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserPlace;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => Str::random(10),
            'middle_name' => Str::random(10),
            'last_name' => Str::random(10),
            'phone' => random_int(10000000000, 99999999999),
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'role' => '1',
        ]);

        User::create([
            'first_name' => Str::random(10),
            'middle_name' => Str::random(10),
            'last_name' => Str::random(10),
            'phone' => random_int(10000000000, 99999999999),
            'email' => 'doctor@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 'doctor',
            'role' => '2',
        ]);

        $leader = User::create([
            'first_name' => Str::random(10),
            'middle_name' => Str::random(10),
            'last_name' => Str::random(10),
            'phone' => random_int(10000000000, 99999999999),
            'email' => 'leader@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 'leader',
            'role' => '3',
            'work_address' => 1
        ]);
    }
}
