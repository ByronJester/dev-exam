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
            'first_name' => 'Admin',
            'middle_name' => 'Admin',
            'last_name' => 'Admin',
            'phone' => random_int(10000000000, 99999999999),
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'role' => '1',
        ]);
    }
}
