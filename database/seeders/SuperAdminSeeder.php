<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds the primary Super Administrator account for production readiness.
     */
    public function run(): void
    {
        $email = env('SUPER_ADMIN_EMAIL', 'superadmin@feasibility.test');
        $password = env('SUPER_ADMIN_PASSWORD', 'Password123!');
        $name = env('SUPER_ADMIN_NAME', 'Super Administrator');
        $phone = env('SUPER_ADMIN_PHONE', '+91 9876543210');

        $superAdmin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'phone' => $phone,
                'designation' => 'Super Administrator & Governing Board Approver',
                'email_verified_at' => now(),
            ]
        );

        $superAdmin->syncRoles(['Super Admin']);
    }
}
