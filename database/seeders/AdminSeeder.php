<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =============== CREATE USER ADMIN =================
        $user = User::create([
            'email'    => 'admin@example.com',
            'password' => 'password',
            'role'     => 'admin',
        ]);

        // =============== CREATE ADMIN PROFILE ===============
        Admin::create([
            'user_id'      => $user->id,
            'fullname'     => 'Super Admin',
            'phone_number' => '081234567890',
            'address'      => 'Jakarta, Indonesia',
            'avatar'       => null,  
        ]);
    }
}
