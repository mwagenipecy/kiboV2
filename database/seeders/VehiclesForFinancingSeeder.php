<?php

namespace Database\Seeders;

use App\Models\Entity;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VehiclesForFinancingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get admin user for vehicle registration
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'System Admin',
                'email' => 'admin@kibo.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        }

        // Create dealers if they don't exist
        $dealerData = [
            ['name' => 'Premium Auto Dealers', 'email' => 'contact@premiumauto.co.tz', 'phone' => '+255 22 123 4567', 'address' => 'Masaki, Dar es Salaam'],
            ['name' => 'City Motors Ltd', 'email' => 'info@citymotors.co.tz', 'phone' => '+255 22 234 5678', 'address' => 'Mikocheni, Dar es Salaam'],
            ['name' => 'Elite Car Center', 'email' => 'sales@elitecars.co.tz', 'phone' => '+255 22 345 6789', 'address' => 'Oysterbay, Dar es Salaam'],
        ];

        $dealers = [];
        foreach ($dealerData as $data) {
            $dealer = Entity::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'type' => 'dealer',
                    'registration_number' => 'DLR-' . rand(1000, 9999),
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'status' => 'active',
                ]
            );
            $dealers[] = $dealer;
        }

        // Create vehicle makes and models
        $makesData = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Land Cruiser', 'Hilux', 'Prius'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Fit', 'HR-V'],
            'BMW' => ['3 Series', '5 Series', 'X3', 'X5', '7 Series'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'GLC', 'GLE', 'S-Class'],
            'Nissan' => ['Altima', 'Sentra', 'Rogue', 'Pathfinder', 'Murano'],
            'Mazda' => ['Mazda3', 'Mazda6', 'CX-5', 'CX-9'],
            'Ford' => ['Focus', 'Fusion', 'Escape', 'Explorer', 'Ranger'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan', 'Polo'],
        ];

        $makeModels = [];
        foreach ($makesData as $makeName => $modelNames) {
            $make = VehicleMake::firstOrCreate(
                ['name' => $makeName],
                ['status' => 'active']
            );
            
            foreach ($modelNames as $modelName) {
                $model = VehicleModel::firstOrCreate(
                    ['name' => $modelName, 'vehicle_make_id' => $make->id],
                    ['status' => 'active']
                );
                $makeModels[] = ['make' => $make, 'model' => $model];
            }
        }

        // Now create vehicles matching different lending criteria
        $vehicles = [
            // Vehicles for Standard Auto Loan (2015-2024, $5k-$50k, <150k km, used)
            [
                'make_model' => ['Toyota', 'Corolla'],
                'year' => 2018,
                'price' => 15000,
                'mileage' => 85000,
                'condition' => 'used',
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'body_type' => 'sedan',
                'variant' => '1.8L LE',
                'color_exterior' => 'Silver',
                'engine_cc' => 1800,
                'doors' => 4,
                'seats' => 5,
            ],
            [
                'make_model' => ['Honda', 'CR-V'],
                'year' => 2019,
                'price' => 22000,
                'mileage' => 65000,
                'condition' => 'certified_pre_owned',
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'body_type' => 'suv',
                'variant' => 'EX AWD',
                'color_exterior' => 'Black',
                'engine_cc' => 2400,
                'doors' => 5,
                'seats' => 5,
            ],
            [
                'make_model' => ['Mazda', 'CX-5'],
                'year' => 2017,
                'price' => 18500,
                'mileage' => 95000,
                'condition' => 'used',
                'fuel_type' => 'diesel',
                'transmission' => 'automatic',
                'body_type' => 'suv',
                'variant' => 'Touring',
                'color_exterior' => 'Red',
                'engine_cc' => 2200,
                'doors' => 5,
                'seats' => 5,
            ],
            [
                'make_model' => ['Nissan', 'Altima'],
                'year' => 2020,
                'price' => 24000,
                'mileage' => 45000,
                'condition' => 'certified_pre_owned',
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'body_type' => 'sedan',
                'variant' => '2.5 SV',
                'color_exterior' => 'White',
                'engine_cc' => 2500,
                'doors' => 4,
                'seats' => 5,
            ],
            
            // Vehicles for Premium Vehicle Financing (2020-2025, $30k-$150k, <50k km, new/certified)
            [
                'make_model' => ['BMW', '5 Series'],
                'year' => 2022,
                'price' => 55000,
                'mileage' => 15000,
                'condition' => 'certified_pre_owned',
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'body_type' => 'sedan',
                'variant' => '530i M Sport',
                'color_exterior' => 'Black',
                'engine_cc' => 2000,
                'doors' => 4,
                'seats' => 5,
            ],
            [
                'make_model' => ['Mercedes-Benz', 'GLE'],
                'year' => 2023,
                'price' => 75000,
                'mileage' => 8000,
                'condition' => 'certified_pre_owned',
                'fuel_type' => 'hybrid',
                'transmission' => 'automatic',
                'body_type' => 'suv',
                'variant' => 'GLE 450 4MATIC',
                'color_exterior' => 'Silver',
                'engine_cc' => 3000,
                'doors' => 5,
                'seats' => 7,
            ],
            [
                'make_model' => ['BMW', 'X5'],
                'year' => 2021,
                'price' => 62000,
                'mileage' => 25000,
                'condition' => 'certified_pre_owned',
                'fuel_type' => 'diesel',
                'transmission' => 'automatic',
                'body_type' => 'suv',
                'variant' => 'xDrive40i',
                'color_exterior' => 'Blue',
                'engine_cc' => 3000,
                'doors' => 5,
                'seats' => 5,
            ],
            
            // Vehicles for First-Time Buyer Program (2012-2024, $3k-$25k, <120k km, used)
            [
                'make_model' => ['Toyota', 'Corolla'],
                'year' => 2014,
                'price' => 9500,
                'mileage' => 110000,
                'condition' => 'used',
                'fuel_type' => 'petrol',
                'transmission' => 'manual',
                'body_type' => 'sedan',
                'variant' => '1.6L',
                'color_exterior' => 'White',
                'engine_cc' => 1600,
                'doors' => 4,
                'seats' => 5,
            ],
            [
                'make_model' => ['Honda', 'Fit'],
                'year' => 2013,
                'price' => 7500,
                'mileage' => 105000,
                'condition' => 'used',
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'body_type' => 'hatchback',
                'variant' => 'LX',
                'color_exterior' => 'Blue',
                'engine_cc' => 1500,
                'doors' => 5,
                'seats' => 5,
            ],
            [
                'make_model' => ['Mazda', 'Mazda3'],
                'year' => 2015,
                'price' => 12000,
                'mileage' => 95000,
                'condition' => 'used',
                'fuel_type' => 'petrol',
                'transmission' => 'manual',
                'body_type' => 'sedan',
                'variant' => 'i Sport',
                'color_exterior' => 'Red',
                'engine_cc' => 2000,
                'doors' => 4,
                'seats' => 5,
            ],
            
            // Vehicles for Green Vehicle Financing (2018-2025, $15k-$80k, <60k km, electric/hybrid)
            [
                'make_model' => ['Toyota', 'Prius'],
                'year' => 2020,
                'price' => 28000,
                'mileage' => 35000,
                'condition' => 'certified_pre_owned',
                'fuel_type' => 'hybrid',
                'transmission' => 'automatic',
                'body_type' => 'hatchback',
                'variant' => 'Limited',
                'color_exterior' => 'Silver',
                'engine_cc' => 1800,
                'doors' => 5,
                'seats' => 5,
            ],
            [
                'make_model' => ['Honda', 'CR-V'],
                'year' => 2021,
                'price' => 35000,
                'mileage' => 22000,
                'condition' => 'certified_pre_owned',
                'fuel_type' => 'hybrid',
                'transmission' => 'automatic',
                'body_type' => 'suv',
                'variant' => 'Hybrid Touring',
                'color_exterior' => 'Blue',
                'engine_cc' => 2000,
                'doors' => 5,
                'seats' => 5,
            ],
            
            // Some vehicles that DON'T match any criteria (for testing)
            [
                'make_model' => ['Ford', 'Focus'],
                'year' => 2010,
                'price' => 4500,
                'mileage' => 180000,
                'condition' => 'used',
                'fuel_type' => 'petrol',
                'transmission' => 'manual',
                'body_type' => 'hatchback',
                'variant' => 'SE',
                'color_exterior' => 'Gray',
                'engine_cc' => 2000,
                'doors' => 5,
                'seats' => 5,
            ],
            [
                'make_model' => ['Volkswagen', 'Polo'],
                'year' => 2009,
                'price' => 3500,
                'mileage' => 200000,
                'condition' => 'used',
                'fuel_type' => 'petrol',
                'transmission' => 'manual',
                'body_type' => 'hatchback',
                'variant' => 'Comfortline',
                'color_exterior' => 'White',
                'engine_cc' => 1400,
                'doors' => 5,
                'seats' => 5,
            ],
        ];

        $createdCount = 0;
        foreach ($vehicles as $vehicleData) {
            // Find the make and model
            $make = VehicleMake::where('name', $vehicleData['make_model'][0])->first();
            $model = VehicleModel::where('name', $vehicleData['make_model'][1])
                ->where('vehicle_make_id', $make->id)
                ->first();
            
            // Randomly assign a dealer
            $dealer = $dealers[array_rand($dealers)];
            
            // Create the vehicle
            Vehicle::create([
                'title' => $vehicleData['year'] . ' ' . $vehicleData['make_model'][0] . ' ' . $vehicleData['make_model'][1],
                'description' => 'Well-maintained ' . $vehicleData['condition'] . ' ' . $vehicleData['make_model'][0] . ' ' . $vehicleData['make_model'][1] . ' with excellent performance and reliability.',
                'origin' => 'local',
                'vehicle_make_id' => $make->id,
                'vehicle_model_id' => $model->id,
                'variant' => $vehicleData['variant'],
                'year' => $vehicleData['year'],
                'body_type' => $vehicleData['body_type'],
                'fuel_type' => $vehicleData['fuel_type'],
                'transmission' => $vehicleData['transmission'],
                'engine_cc' => $vehicleData['engine_cc'],
                'drive_type' => in_array($vehicleData['body_type'], ['suv', 'wagon']) ? 'awd' : 'fwd',
                'color_exterior' => $vehicleData['color_exterior'],
                'color_interior' => 'Black',
                'doors' => $vehicleData['doors'],
                'seats' => $vehicleData['seats'],
                'mileage' => $vehicleData['mileage'],
                'price' => $vehicleData['price'],
                'currency' => 'GBP',
                'condition' => $vehicleData['condition'],
                'entity_id' => $dealer->id,
                'registered_by' => $admin->id,
                'approved_by' => $admin->id,
                'status' => 'approved',
                'approved_at' => now(),
                'features' => ['Air Conditioning', 'Power Windows', 'ABS', 'Airbags'],
                'safety_features' => ['ABS', 'Airbags', 'Stability Control'],
            ]);
            
            $createdCount++;
        }

        $this->command->info("✅ Created {$createdCount} vehicles matching lending criteria!");
        $this->command->info("📊 Breakdown:");
        $this->command->info("   - Standard Auto Loan eligible: 4 vehicles");
        $this->command->info("   - Premium Financing eligible: 3 vehicles");
        $this->command->info("   - First-Time Buyer eligible: 3 vehicles");
        $this->command->info("   - Green Vehicle eligible: 2 vehicles");
        $this->command->info("   - No financing available: 2 vehicles (for comparison)");
    }
}
