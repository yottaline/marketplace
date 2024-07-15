<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $admin =  Role::create(['name' => 'admin']);
        Role::create(['name' => 'retailer']);

        $user = \App\Models\User::create([
            'user_code' => Str::random(8),
            'user_name' => 'Tech.Support',
            'user_email' => 'support@yottaline.com',
            'user_password' => Hash::make('Support@Yottaline'),
            'user_type'     => 1,
            'user_created'  => now()
        ]);

        $user->assignRole($admin);

        \App\Models\Admin::create([
            'admin_user' => 1
        ]);

    }
}