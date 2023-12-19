<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Setting::create([
            'image_name' => 'company_logo.png',
            'header' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
            'subheader' => 'Lorem ipsum dolor sit amet',
            'address' => 'Baker street 221B',
            'contact' => '(941) 746-5111',
            'position_name' => 'CTO',
            'name' => 'Someone'
        ]);

        foreach(array('superadmin', 'admin', 'reviewer', 'user') as $role)
        {
            User::create([
                'name' => 'Shivu',
                'last_name' => "$role",
                'email' => "$role@gmail.com",
                'password' => Hash::make('semuasama'),
                'role' => $role
            ]);
        }
    }
}
