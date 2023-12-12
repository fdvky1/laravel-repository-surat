<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Shivu',
            'last_name' => 'Superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('semuasama'),
            'role' => 'superadmin'
        ]);

        User::create([
            'name' => 'Shivu',
            'last_name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('semuasama'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Shivu',
            'last_name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('semuasama'),
            'role' => 'user'
        ]);

        User::create([
            'name' => 'Shivu',
            'last_name' => 'Reviewer',
            'email' => 'reviewer@gmail.com',
            'password' => Hash::make('semuasama'),
            'role' => 'reviewer'
        ]);

    }
}
