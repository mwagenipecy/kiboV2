<?php

namespace Database\Seeders;

use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Enums\VehicleStatus;
use App\Models\Entity;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting vehicle seeding...');

        // First, ensure we have some dealers
        $this->createDealersIfNeeded();

        // Get dealers and admin user
        $dealers = Entity::where('type', EntityType::DEALER)
            ->where('status', EntityStatus::ACTIVE)
            ->get();

        $adminUser = User::where('role', 'admin')->first();

        if ($dealers->isEmpty()) {
            $this->command->error('No dealers found! Please create dealers first.');
            return;
        }

        if (!$adminUser) {
            $this->command->error('No admin user found! Please run AdminUserSeeder first.');
            return;
        }

        $this->command->info("Found {$dealers->count()} dealers. Creating vehicles...");

        // Get all makes and models
        $makes = VehicleMake::with('models')->get();

        if ($makes->isEmpty()) {
            $this->command->error('No vehicle makes found! Please run VehicleMakeModelSeeder first.');
            return;
        }

        $vehicleData = $this->getVehicleData();
        $createdCount = 0;

        foreach ($vehicleData as $data) {
            try {
                // Find the make and model
                $make = $makes->firstWhere('name', $data['make']);
                if (!$make) continue;

                $model = $make->models->firstWhere('name', $data['model']);
                if (!$model) continue;

                // Random dealer
                $dealer = $dealers->random();

                Vehicle::create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'origin' => $data['origin'],
                    'registration_number' => $this->generateRegistrationNumber(),
                    'condition' => $data['condition'],
                    'vehicle_make_id' => $make->id,
                    'vehicle_model_id' => $model->id,
                    'variant' => $data['variant'] ?? null,
                    'year' => $data['year'],
                    'body_type' => $data['body_type'],
                    'fuel_type' => $data['fuel_type'],
                    'transmission' => $data['transmission'],
                    'engine_capacity' => $data['engine_capacity'] ?? null,
                    'engine_cc' => $data['engine_cc'],
                    'drive_type' => $data['drive_type'] ?? 'FWD',
                    'color_exterior' => $data['color_exterior'],
                    'color_interior' => $data['color_interior'] ?? 'Black',
                    'doors' => $data['doors'],
                    'seats' => $data['seats'],
                    'mileage' => $data['mileage'],
                    'vin' => $this->generateVIN(),
                    'price' => $data['price'],
                    'currency' => 'GBP',
                    'negotiable' => $data['negotiable'] ?? true,
                    'features' => $data['features'] ?? [],
                    'safety_features' => $data['safety_features'] ?? [],
                    'entity_id' => $dealer->id,
                    'registered_by' => $dealer->user_id ?? $adminUser->id,
                    'status' => VehicleStatus::APPROVED,
                    'approved_at' => now(),
                    'approved_by' => $adminUser->id,
                ]);

                $createdCount++;
            } catch (\Exception $e) {
                $this->command->error("Failed to create vehicle: {$data['title']} - " . $e->getMessage());
            }
        }

        $this->command->info("Successfully created {$createdCount} vehicles!");
    }

    /**
     * Create sample dealers if none exist
     */
    private function createDealersIfNeeded(): void
    {
        $dealerCount = Entity::where('type', EntityType::DEALER)->count();

        if ($dealerCount === 0) {
            $this->command->info('No dealers found. Creating sample dealers...');

            $dealers = [
                ['name' => 'Premium Auto Sales', 'email' => 'contact@premiumauto.com', 'phone' => '+255712345678'],
                ['name' => 'City Motors Tanzania', 'email' => 'info@citymotors.co.tz', 'phone' => '+255723456789'],
                ['name' => 'Elite Car Dealers', 'email' => 'sales@elitecars.com', 'phone' => '+255734567890'],
                ['name' => 'Dar Auto Hub', 'email' => 'info@darautohub.tz', 'phone' => '+255745678901'],
                ['name' => 'Tanzania Car Center', 'email' => 'contact@tzcarcenter.com', 'phone' => '+255756789012'],
            ];

            foreach ($dealers as $dealerData) {
                Entity::create([
                    'type' => EntityType::DEALER,
                    'name' => $dealerData['name'],
                    'email' => $dealerData['email'],
                    'phone' => $dealerData['phone'],
                    'address' => 'Dar es Salaam, Tanzania',
                    'city' => 'Dar es Salaam',
                    'country' => 'Tanzania',
                    'status' => EntityStatus::ACTIVE,
                ]);
            }

            $this->command->info('Created 5 sample dealers.');
        }
    }

    /**
     * Generate a random registration number
     */
    private function generateRegistrationNumber(): string
    {
        $letters = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
        $numbers = rand(100, 999);
        return "T {$letters} {$numbers}";
    }

    /**
     * Generate a random VIN
     */
    private function generateVIN(): string
    {
        return strtoupper(substr(md5(uniqid(rand(), true)), 0, 17));
    }

    /**
     * Get sample vehicle data
     */
    private function getVehicleData(): array
    {
        return [
            // Toyota vehicles
            [
                'make' => 'Toyota',
                'model' => 'Corolla',
                'title' => '2022 Toyota Corolla SE',
                'variant' => 'SE',
                'description' => 'Excellent condition Toyota Corolla with low mileage. Perfect for city driving with great fuel economy.',
                'origin' => 'international',
                'condition' => 'used',
                'year' => 2022,
                'body_type' => 'Sedan',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_cc' => 1800,
                'color_exterior' => 'Silver',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 15000,
                'price' => 18500,
                'features' => ['Air Conditioning', 'Power Windows', 'Bluetooth', 'Backup Camera'],
                'safety_features' => ['ABS', 'Airbags', 'Stability Control'],
                'negotiable' => true,
            ],
            [
                'make' => 'Toyota',
                'model' => 'RAV4',
                'title' => '2023 Toyota RAV4 XLE',
                'variant' => 'XLE',
                'description' => 'Brand new Toyota RAV4 with all the latest features. Perfect family SUV.',
                'origin' => 'international',
                'condition' => 'new',
                'year' => 2023,
                'body_type' => 'SUV',
                'fuel_type' => 'Hybrid',
                'transmission' => 'Automatic',
                'engine_cc' => 2500,
                'color_exterior' => 'Blue',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 50,
                'price' => 32000,
                'features' => ['Leather Seats', 'Sunroof', 'Navigation', 'Apple CarPlay'],
                'safety_features' => ['Toyota Safety Sense', 'Lane Departure Warning', 'Adaptive Cruise Control'],
                'negotiable' => false,
            ],
            [
                'make' => 'Toyota',
                'model' => 'Land Cruiser',
                'title' => '2021 Toyota Land Cruiser VX',
                'variant' => 'VX',
                'description' => 'Powerful and reliable Land Cruiser. Perfect for both city and off-road adventures.',
                'origin' => 'international',
                'condition' => 'used',
                'year' => 2021,
                'body_type' => 'SUV',
                'fuel_type' => 'Diesel',
                'transmission' => 'Automatic',
                'engine_cc' => 4500,
                'color_exterior' => 'White',
                'doors' => 5,
                'seats' => 7,
                'mileage' => 45000,
                'price' => 65000,
                'features' => ['4WD', 'Leather Interior', 'Premium Sound System', 'Multi-zone Climate Control'],
                'safety_features' => ['Advanced Safety Package', '360 Camera', 'Hill Descent Control'],
                'negotiable' => true,
            ],

            // Honda vehicles
            [
                'make' => 'Honda',
                'model' => 'Civic',
                'title' => '2022 Honda Civic Sport',
                'variant' => 'Sport',
                'description' => 'Sporty and fuel-efficient Honda Civic in excellent condition.',
                'origin' => 'international',
                'condition' => 'used',
                'year' => 2022,
                'body_type' => 'Sedan',
                'fuel_type' => 'Petrol',
                'transmission' => 'Manual',
                'engine_cc' => 2000,
                'color_exterior' => 'Red',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 22000,
                'price' => 21000,
                'features' => ['Sport Mode', 'Digital Display', 'Cruise Control'],
                'safety_features' => ['Honda Sensing', 'Collision Mitigation'],
                'negotiable' => true,
            ],
            [
                'make' => 'Honda',
                'model' => 'CR-V',
                'title' => '2023 Honda CR-V EX',
                'variant' => 'EX',
                'description' => 'Spacious and comfortable CR-V with advanced safety features.',
                'origin' => 'international',
                'condition' => 'new',
                'year' => 2023,
                'body_type' => 'SUV',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_cc' => 1500,
                'color_exterior' => 'Black',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 100,
                'price' => 28500,
                'features' => ['Sunroof', 'Remote Start', 'Wireless Charging'],
                'safety_features' => ['Honda Sensing Suite', 'Blind Spot Monitoring'],
                'negotiable' => false,
            ],

            // BMW vehicles
            [
                'make' => 'BMW',
                'model' => '3 Series',
                'title' => '2021 BMW 330i M Sport',
                'variant' => 'M Sport',
                'description' => 'Luxury sports sedan with exceptional performance and comfort.',
                'origin' => 'international',
                'condition' => 'used',
                'year' => 2021,
                'body_type' => 'Sedan',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_cc' => 2000,
                'color_exterior' => 'Black',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 28000,
                'price' => 38000,
                'features' => ['M Sport Package', 'Premium Sound', 'Ambient Lighting', 'Gesture Control'],
                'safety_features' => ['Active Driving Assistant', 'Parking Assistant'],
                'negotiable' => true,
            ],
            [
                'make' => 'BMW',
                'model' => 'X5',
                'title' => '2022 BMW X5 xDrive40i',
                'variant' => 'xDrive40i',
                'description' => 'Premium luxury SUV with cutting-edge technology.',
                'origin' => 'international',
                'condition' => 'used',
                'year' => 2022,
                'body_type' => 'SUV',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_cc' => 3000,
                'color_exterior' => 'White',
                'doors' => 5,
                'seats' => 7,
                'mileage' => 18000,
                'price' => 72000,
                'features' => ['Panoramic Roof', 'Harman Kardon Audio', 'Massage Seats', 'Head-Up Display'],
                'safety_features' => ['Driving Assistant Professional', 'Night Vision'],
                'negotiable' => true,
            ],

            // Mercedes-Benz vehicles
            [
                'make' => 'Mercedes-Benz',
                'model' => 'C-Class',
                'title' => '2022 Mercedes-Benz C300',
                'variant' => 'C300',
                'description' => 'Elegant and sophisticated luxury sedan.',
                'origin' => 'international',
                'condition' => 'used',
                'year' => 2022,
                'body_type' => 'Sedan',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_cc' => 2000,
                'color_exterior' => 'Silver',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 15000,
                'price' => 42000,
                'features' => ['MBUX Infotainment', 'Burmester Sound', 'Ambient Lighting'],
                'safety_features' => ['Active Brake Assist', 'Attention Assist'],
                'negotiable' => true,
            ],

            // Nissan vehicles
            [
                'make' => 'Nissan',
                'model' => 'Rogue',
                'title' => '2023 Nissan Rogue SV',
                'variant' => 'SV',
                'description' => 'Reliable and spacious family SUV with modern features.',
                'origin' => 'international',
                'condition' => 'new',
                'year' => 2023,
                'body_type' => 'SUV',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_cc' => 2500,
                'color_exterior' => 'Blue',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 0,
                'price' => 26000,
                'features' => ['ProPILOT Assist', 'Around View Monitor', 'Tri-Zone Climate'],
                'safety_features' => ['Nissan Safety Shield 360'],
                'negotiable' => false,
            ],

            // Ford vehicles
            [
                'make' => 'Ford',
                'model' => 'F-150',
                'title' => '2022 Ford F-150 XLT',
                'variant' => 'XLT',
                'description' => 'Powerful and versatile pickup truck. Perfect for work and play.',
                'origin' => 'international',
                'condition' => 'used',
                'year' => 2022,
                'body_type' => 'Truck',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_cc' => 3500,
                'color_exterior' => 'Grey',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 35000,
                'price' => 42000,
                'features' => ['4x4', 'Towing Package', 'Bed Liner', 'FordPass Connect'],
                'safety_features' => ['Co-Pilot360', 'Blind Spot Monitoring'],
                'negotiable' => true,
            ],

            // More diverse vehicles
            [
                'make' => 'Hyundai',
                'model' => 'Tucson',
                'title' => '2023 Hyundai Tucson SEL',
                'variant' => 'SEL',
                'description' => 'Modern compact SUV with excellent fuel economy.',
                'origin' => 'international',
                'condition' => 'new',
                'year' => 2023,
                'body_type' => 'SUV',
                'fuel_type' => 'Hybrid',
                'transmission' => 'Automatic',
                'engine_cc' => 1600,
                'color_exterior' => 'White',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 0,
                'price' => 27500,
                'features' => ['Smart Key', 'Wireless Charging', 'Digital Key'],
                'safety_features' => ['Hyundai SmartSense'],
                'negotiable' => false,
            ],
            [
                'make' => 'Mazda',
                'model' => 'CX-5',
                'title' => '2022 Mazda CX-5 Touring',
                'variant' => 'Touring',
                'description' => 'Stylish and fun-to-drive crossover SUV.',
                'origin' => 'international',
                'condition' => 'used',
                'year' => 2022,
                'body_type' => 'SUV',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_cc' => 2500,
                'color_exterior' => 'Red',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 18000,
                'price' => 24500,
                'features' => ['Bose Audio', 'Power Liftgate', 'Heated Seats'],
                'safety_features' => ['i-ACTIVSENSE Safety Suite'],
                'negotiable' => true,
            ],
            [
                'make' => 'Volkswagen',
                'model' => 'Tiguan',
                'title' => '2021 Volkswagen Tiguan SE',
                'variant' => 'SE',
                'description' => 'German-engineered SUV with spacious interior.',
                'origin' => 'international',
                'condition' => 'used',
                'year' => 2021,
                'body_type' => 'SUV',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_cc' => 2000,
                'color_exterior' => 'Blue',
                'doors' => 4,
                'seats' => 7,
                'mileage' => 32000,
                'price' => 23000,
                'features' => ['Third Row Seating', 'App-Connect', 'Adaptive Cruise'],
                'safety_features' => ['IQ.DRIVE Safety'],
                'negotiable' => true,
            ],
            [
                'make' => 'Tesla',
                'model' => 'Model 3',
                'title' => '2023 Tesla Model 3 Long Range',
                'variant' => 'Long Range',
                'description' => 'Electric vehicle with cutting-edge technology and autopilot.',
                'origin' => 'international',
                'condition' => 'new',
                'year' => 2023,
                'body_type' => 'Sedan',
                'fuel_type' => 'Electric',
                'transmission' => 'Automatic',
                'engine_cc' => 0,
                'color_exterior' => 'White',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 0,
                'price' => 45000,
                'features' => ['Autopilot', 'Premium Audio', 'Glass Roof', 'Over-the-Air Updates'],
                'safety_features' => ['Active Safety Features', 'Automatic Emergency Braking'],
                'negotiable' => false,
            ],
            [
                'make' => 'Subaru',
                'model' => 'Outback',
                'title' => '2022 Subaru Outback Limited',
                'variant' => 'Limited',
                'description' => 'Adventure-ready wagon with legendary AWD system.',
                'origin' => 'international',
                'condition' => 'used',
                'year' => 2022,
                'body_type' => 'Wagon',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_cc' => 2400,
                'color_exterior' => 'Green',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 25000,
                'price' => 28000,
                'features' => ['Roof Rails', 'Harman Kardon Audio', 'X-Mode'],
                'safety_features' => ['EyeSight Driver Assist'],
                'negotiable' => true,
            ],
        ];
    }
}
