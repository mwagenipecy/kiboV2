<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminExists = User::where('email', 'admin@kibo.com')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'System Administrator',
                'email' => 'admin@kibo.com',
                'password' => Hash::make('password'), // Default password
                'role' => 'admin',
                'entity_id' => null, // Admins don't belong to any entity
                'email_verified_at' => now(),
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@kibo.com');
            $this->command->info('Password: password');
            $this->command->warn('Please change the password after first login!');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
