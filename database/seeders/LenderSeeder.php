<?php

namespace Database\Seeders;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lenders = [
            [
                'name' => 'Prime Auto Finance',
                'type' => 'lender',
                'registration_number' => 'PAF-2024-001',
                'email' => 'info@primeautofinance.com',
                'phone' => '+255 22 211 5500',
                'address' => 'Samora Avenue, Dar es Salaam',
                'status' => 'active',
            ],
            [
                'name' => 'Quick Loan Motors',
                'type' => 'lender',
                'registration_number' => 'QLM-2024-002',
                'email' => 'support@quickloanmotors.com',
                'phone' => '+255 22 212 6600',
                'address' => 'Morogoro Road, Dar es Salaam',
                'status' => 'active',
            ],
            [
                'name' => 'Tanzania Vehicle Finance',
                'type' => 'lender',
                'registration_number' => 'TVF-2024-003',
                'email' => 'loans@tzvehiclefinance.com',
                'phone' => '+255 22 213 7700',
                'address' => 'Pugu Road, Dar es Salaam',
                'status' => 'active',
            ],
            [
                'name' => 'EasyDrive Financing',
                'type' => 'lender',
                'registration_number' => 'EDF-2024-004',
                'email' => 'contact@easydrivefinancing.com',
                'phone' => '+255 22 214 8800',
                'address' => 'Bagamoyo Road, Dar es Salaam',
                'status' => 'active',
            ],
            [
                'name' => 'Barclays Auto Loans',
                'type' => 'lender',
                'registration_number' => 'BAL-2024-005',
                'email' => 'autoloans@barclays.co.tz',
                'phone' => '+255 22 215 9900',
                'address' => 'Ohio Street, Dar es Salaam',
                'status' => 'active',
            ],
        ];

        foreach ($lenders as $lenderData) {
            // Create entity
            $entity = Entity::create($lenderData);

            // Create a user account for the lender
            User::create([
                'name' => $lenderData['name'] . ' Admin',
                'email' => str_replace('info@', 'admin@', str_replace('support@', 'admin@', str_replace('loans@', 'admin@', str_replace('contact@', 'admin@', $lenderData['email'])))),
                'password' => Hash::make('password'),
                'role' => 'lender',
                'entity_id' => $entity->id,
            ]);
        }

        $this->command->info('✅ Created ' . count($lenders) . ' lenders successfully!');
    }
}
