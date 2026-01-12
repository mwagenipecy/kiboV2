<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use App\Models\VehicleMake;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for approved_by
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->error('No admin user found. Please run AdminUserSeeder first.');
            return;
        }

        // Get vehicle makes for garage owners and spare parts
        $vehicleMakes = VehicleMake::where('status', 'active')->get();
        
        if ($vehicleMakes->isEmpty()) {
            $this->command->error('No vehicle makes found. Please run VehicleMakeModelSeeder first.');
            return;
        }

        $agents = [
            [
                'name' => 'John Mwangi',
                'email' => 'john.mwangi@autogarage.co.tz',
                'phone_number' => '+255 712 345 678',
                'agent_type' => 'garage_owner',
                'vehicle_makes' => $vehicleMakes->whereIn('name', ['Toyota', 'Honda', 'Nissan'])->pluck('id')->toArray(),
                'services' => ['washing', 'oiling', 'repair', 'maintenance', 'diagnostics'],
                'spare_part_details' => null,
                'license_number' => 'GAR-2024-001',
                'address' => 'Mbezi Beach Road, Dar es Salaam',
                'company_name' => 'Mwangi Auto Garage',
                'status' => 'active',
            ],
            [
                'name' => 'Sarah Hassan',
                'email' => 'sarah.hassan@premiumgarage.co.tz',
                'phone_number' => '+255 713 456 789',
                'agent_type' => 'garage_owner',
                'vehicle_makes' => $vehicleMakes->whereIn('name', ['BMW', 'Mercedes-Benz', 'Audi'])->pluck('id')->toArray(),
                'services' => ['repair', 'maintenance', 'diagnostics', 'tire_service', 'battery_service', 'air_conditioning'],
                'spare_part_details' => null,
                'license_number' => 'GAR-2024-002',
                'address' => 'Mikocheni, Dar es Salaam',
                'company_name' => 'Premium Auto Services',
                'status' => 'active',
            ],
            [
                'name' => 'Ahmed Juma',
                'email' => 'ahmed.juma@lubricants.co.tz',
                'phone_number' => '+255 714 567 890',
                'agent_type' => 'lubricant_shop',
                'vehicle_makes' => null,
                'services' => null,
                'spare_part_details' => null,
                'license_number' => 'LUB-2024-001',
                'address' => 'Kariakoo Market, Dar es Salaam',
                'company_name' => 'Juma Lubricants & Oils',
                'status' => 'active',
            ],
            [
                'name' => 'Fatuma Ali',
                'email' => 'fatuma.ali@spareparts.co.tz',
                'phone_number' => '+255 715 678 901',
                'agent_type' => 'spare_part',
                'vehicle_makes' => $vehicleMakes->whereIn('name', ['Toyota', 'Honda', 'Ford', 'Nissan'])->pluck('id')->toArray(),
                'services' => null,
                'spare_part_details' => 'Specializes in engine parts, transmission components, brake pads, filters, and electrical parts for Japanese and American vehicles.',
                'license_number' => 'SP-2024-001',
                'address' => 'Temeke Industrial Area, Dar es Salaam',
                'company_name' => 'Ali Auto Spare Parts',
                'status' => 'active',
            ],
            [
                'name' => 'David Kimathi',
                'email' => 'david.kimathi@autoparts.co.tz',
                'phone_number' => '+255 716 789 012',
                'agent_type' => 'spare_part',
                'vehicle_makes' => $vehicleMakes->whereIn('name', ['BMW', 'Mercedes-Benz', 'Audi', 'Volvo'])->pluck('id')->toArray(),
                'services' => null,
                'spare_part_details' => 'Premium European car parts including body parts, suspension components, lighting systems, and interior accessories.',
                'license_number' => 'SP-2024-002',
                'address' => 'Upanga, Dar es Salaam',
                'company_name' => 'Kimathi European Parts',
                'status' => 'active',
            ],
        ];

        $this->command->info('Creating agents...');

        foreach ($agents as $agentData) {
            // Check if agent already exists
            $existingAgent = Agent::where('email', $agentData['email'])->first();
            if ($existingAgent) {
                $this->command->warn("Agent with email {$agentData['email']} already exists. Skipping...");
                continue;
            }

            // Generate random password
            $password = Str::random(12);

            // Create user account
            $user = User::create([
                'name' => $agentData['name'],
                'email' => $agentData['email'],
                'password' => Hash::make($password),
                'role' => 'agent',
            ]);

            // Create agent
            $agent = Agent::create([
                'name' => $agentData['name'],
                'email' => $agentData['email'],
                'phone_number' => $agentData['phone_number'],
                'agent_type' => $agentData['agent_type'],
                'vehicle_makes' => $agentData['vehicle_makes'],
                'services' => $agentData['services'],
                'spare_part_details' => $agentData['spare_part_details'],
                'license_number' => $agentData['license_number'],
                'address' => $agentData['address'],
                'company_name' => $agentData['company_name'],
                'status' => $agentData['status'],
                'user_id' => $user->id,
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $admin->id,
            ]);

            $this->command->info("✓ Created agent: {$agentData['name']} ({$agentData['agent_type']}) - Email: {$agentData['email']} - Password: {$password}");
        }

        $this->command->info('✓ Successfully created ' . count($agents) . ' agents!');
    }
}
