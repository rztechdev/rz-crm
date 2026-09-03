<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CrmDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Seed Roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'sales']);
        Role::firstOrCreate(['name' => 'project_manager']);
        Role::firstOrCreate(['name' => 'finance']);

        // 1. Create or update Default Admin Owner
        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'rzcompanyidn@gmail.com')],
            [
                'name' => env('ADMIN_NAME', 'Owner RZ Digital'),
                'password' => Hash::make(env('ADMIN_PASSWORD', '12345678')),
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['admin']);
    }
}
