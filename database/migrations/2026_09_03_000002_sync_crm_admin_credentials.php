<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $targetEmail = env('ADMIN_EMAIL', 'rzcompanyidn@gmail.com');
        $targetName = env('ADMIN_NAME', 'Owner RZ Digital');
        $targetPassword = env('ADMIN_PASSWORD', '12345678');

        // Migrasikan akun admin lama jika ada
        $oldAdmin = User::where('email', 'rztechdevidn@gmail.com')->first();
        if ($oldAdmin && $oldAdmin->email !== $targetEmail) {
            $oldAdmin->update([
                'name' => $targetName,
                'email' => $targetEmail,
                'password' => Hash::make($targetPassword),
            ]);
            $admin = $oldAdmin;
        } else {
            $admin = User::updateOrCreate(
                ['email' => $targetEmail],
                [
                    'name' => $targetName,
                    'password' => Hash::make($targetPassword),
                    'email_verified_at' => now(),
                ]
            );
        }

        // Pastikan role admin tersedia dan terhubung
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
