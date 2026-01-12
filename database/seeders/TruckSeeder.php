<?php

namespace Database\Seeders;

use App\Enums\VehicleStatus;
use App\Models\Truck;
use App\Models\User;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class TruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create admin user for registered_by
        $adminUser = User::where('role', 'admin')->first();
        if (!$adminUser) {
            $adminUser = User::first();
        }

        if (!$adminUser) {
            $this->command->error('No users found. Please seed users first.');
            return;
        }

        // Get truck makes (Toyota, Ford, Chevrolet are common for trucks)
        $toyotaMake = VehicleMake::where('name', 'Toyota')->first();
        $fordMake = VehicleMake::where('name', 'Ford')->first();
        $chevroletMake = VehicleMake::where('name', 'Chevrolet')->first();
        $ramMake = VehicleMake::where('name', 'RAM')->first();
        $gmcMake = VehicleMake::where('name', 'GMC')->first();

        // Get or create truck models
        $trucks = [];

        // Truck 1: Toyota Hilux Pickup
        if ($toyotaMake) {
            $hiluxModel = VehicleModel::firstOrCreate(
                ['name' => 'Hilux', 'vehicle_make_id' => $toyotaMake->id],
                ['status' => 'active']
            );

            $trucks[] = [
                'title' => '2023 Toyota Hilux Double Cab Pickup',
                'description' => 'Excellent condition Toyota Hilux with low mileage. Perfect for commercial use or personal transport. Well maintained with full service history.',
                'origin' => 'local',
                'registration_number' => 'T123ABC',
                'condition' => 'used',
                'vehicle_make_id' => $toyotaMake->id,
                'vehicle_model_id' => $hiluxModel->id,
                'variant' => 'SR5',
                'year' => 2023,
                'truck_type' => 'Pickup',
                'body_type' => 'Double Cab',
                'fuel_type' => 'Diesel',
                'transmission' => 'Automatic',
                'engine_capacity' => '2.8L',
                'engine_cc' => 2755,
                'drive_type' => '4WD',
                'color_exterior' => 'White',
                'color_interior' => 'Black',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 25000,
                'vin' => 'JTMHY05J504123456',
                'cargo_capacity_kg' => 1100,
                'towing_capacity_kg' => 3500,
                'payload_capacity_kg' => 1100,
                'bed_length_m' => 1.54,
                'bed_width_m' => 1.62,
                'axle_configuration' => '4x4',
                'price' => 85000000,
                'currency' => 'TZS',
                'original_price' => 95000000,
                'negotiable' => true,
                'features' => ['Air Conditioning', 'Power Steering', 'Power Windows', 'Touchscreen Display', 'Bluetooth', 'USB Ports', 'Cruise Control', 'Backup Camera'],
                'safety_features' => ['ABS', 'Airbags', 'Traction Control', 'Stability Control', 'Hill Start Assist', 'Parking Sensors'],
                'status' => VehicleStatus::APPROVED->value,
                'registered_by' => $adminUser->id,
            ];
        }

        // Truck 2: Ford F-150
        if ($fordMake) {
            $f150Model = VehicleModel::firstOrCreate(
                ['name' => 'F-150', 'vehicle_make_id' => $fordMake->id],
                ['status' => 'active']
            );

            $trucks[] = [
                'title' => '2022 Ford F-150 Lariat Crew Cab',
                'description' => 'Premium Ford F-150 in excellent condition. Low mileage, single owner. Perfect for both work and recreation. Comes with all original documentation.',
                'origin' => 'international',
                'registration_number' => 'T456DEF',
                'condition' => 'used',
                'vehicle_make_id' => $fordMake->id,
                'vehicle_model_id' => $f150Model->id,
                'variant' => 'Lariat',
                'year' => 2022,
                'truck_type' => 'Pickup',
                'body_type' => 'Crew Cab',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_capacity' => '3.5L',
                'engine_cc' => 3496,
                'drive_type' => '4WD',
                'color_exterior' => 'Magnetic Gray',
                'color_interior' => 'Black Leather',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 18000,
                'vin' => '1FTFW1E53NF123789',
                'cargo_capacity_kg' => 850,
                'towing_capacity_kg' => 4500,
                'payload_capacity_kg' => 850,
                'bed_length_m' => 1.68,
                'bed_width_m' => 1.65,
                'axle_configuration' => '4x4',
                'price' => 120000000,
                'currency' => 'TZS',
                'original_price' => null,
                'negotiable' => true,
                'features' => ['Leather Seats', 'Premium Sound System', 'Navigation System', 'Sunroof', 'Heated Seats', 'Power Seats', 'Remote Start', 'Trailer Brake Controller'],
                'safety_features' => ['ABS', 'Airbags', 'Blind Spot Monitoring', 'Lane Keeping Assist', 'Adaptive Cruise Control', 'Backup Camera', 'Parking Sensors', 'Collision Warning'],
                'status' => VehicleStatus::APPROVED->value,
                'registered_by' => $adminUser->id,
            ];
        }

        // Truck 3: Chevrolet Silverado
        if ($chevroletMake) {
            $silveradoModel = VehicleModel::firstOrCreate(
                ['name' => 'Silverado', 'vehicle_make_id' => $chevroletMake->id],
                ['status' => 'active']
            );

            $trucks[] = [
                'title' => '2021 Chevrolet Silverado 2500 HD',
                'description' => 'Heavy-duty Chevrolet Silverado perfect for towing and heavy loads. Well maintained commercial vehicle with service records. Ideal for construction or agriculture.',
                'origin' => 'local',
                'registration_number' => 'T789GHI',
                'condition' => 'used',
                'vehicle_make_id' => $chevroletMake->id,
                'vehicle_model_id' => $silveradoModel->id,
                'variant' => 'LTZ',
                'year' => 2021,
                'truck_type' => 'Pickup',
                'body_type' => 'Double Cab',
                'fuel_type' => 'Diesel',
                'transmission' => 'Automatic',
                'engine_capacity' => '6.6L',
                'engine_cc' => 6592,
                'drive_type' => '4WD',
                'color_exterior' => 'Summit White',
                'color_interior' => 'Jet Black',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 45000,
                'vin' => '1GCVKREC8MZ456123',
                'cargo_capacity_kg' => 1500,
                'towing_capacity_kg' => 8000,
                'payload_capacity_kg' => 1500,
                'bed_length_m' => 1.98,
                'bed_width_m' => 1.68,
                'axle_configuration' => '4x4',
                'price' => 150000000,
                'currency' => 'TZS',
                'original_price' => null,
                'negotiable' => true,
                'features' => ['Air Conditioning', 'Power Windows', 'Power Locks', 'Touchscreen', 'USB Ports', 'Towing Package', 'Bed Liner', 'Tool Box'],
                'safety_features' => ['ABS', 'Airbags', 'Traction Control', 'Stability Control', 'Hill Start Assist', 'Trailer Sway Control', 'Backup Camera'],
                'status' => VehicleStatus::APPROVED->value,
                'registered_by' => $adminUser->id,
            ];
        }

        // Truck 4: RAM 1500
        if ($ramMake) {
            $ram1500Model = VehicleModel::firstOrCreate(
                ['name' => '1500', 'vehicle_make_id' => $ramMake->id],
                ['status' => 'active']
            );

            $trucks[] = [
                'title' => '2023 RAM 1500 Rebel Crew Cab',
                'description' => 'Rugged and capable RAM 1500 Rebel with off-road package. Perfect for adventurous drivers. Like new condition with all features.',
                'origin' => 'international',
                'registration_number' => 'T321JKL',
                'condition' => 'used',
                'vehicle_make_id' => $ramMake->id,
                'vehicle_model_id' => $ram1500Model->id,
                'variant' => 'Rebel',
                'year' => 2023,
                'truck_type' => 'Pickup',
                'body_type' => 'Crew Cab',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'engine_capacity' => '5.7L',
                'engine_cc' => 5654,
                'drive_type' => '4WD',
                'color_exterior' => 'Bright White',
                'color_interior' => 'Black',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 12000,
                'vin' => '1C6RRFKT1NS987654',
                'cargo_capacity_kg' => 750,
                'towing_capacity_kg' => 4100,
                'payload_capacity_kg' => 750,
                'bed_length_m' => 1.68,
                'bed_width_m' => 1.65,
                'axle_configuration' => '4x4',
                'price' => 130000000,
                'currency' => 'TZS',
                'original_price' => 145000000,
                'negotiable' => true,
                'features' => ['Off-Road Package', 'LED Headlights', 'Uconnect System', 'Premium Sound', 'Heated Seats', 'Power Seats', 'Bed Step', 'Spray-In Bed Liner'],
                'safety_features' => ['ABS', 'Airbags', 'Electronic Stability Control', 'Traction Control', 'Hill Start Assist', 'Parking Sensors', 'Blind Spot Monitoring', 'Backup Camera'],
                'status' => VehicleStatus::APPROVED->value,
                'registered_by' => $adminUser->id,
            ];
        }

        // Truck 5: GMC Sierra
        if ($gmcMake) {
            $sierraModel = VehicleModel::firstOrCreate(
                ['name' => 'Sierra', 'vehicle_make_id' => $gmcMake->id],
                ['status' => 'active']
            );

            $trucks[] = [
                'title' => '2020 GMC Sierra 1500 AT4 Crew Cab',
                'description' => 'Premium GMC Sierra AT4 with off-road capabilities and luxury features. Excellent condition, well maintained. Perfect for both work and weekend adventures.',
                'origin' => 'local',
                'registration_number' => 'T654MNO',
                'condition' => 'used',
                'vehicle_make_id' => $gmcMake->id,
                'vehicle_model_id' => $sierraModel->id,
                'variant' => 'AT4',
                'year' => 2020,
                'truck_type' => 'Pickup',
                'body_type' => 'Crew Cab',
                'fuel_type' => 'Diesel',
                'transmission' => 'Automatic',
                'engine_capacity' => '3.0L',
                'engine_cc' => 2998,
                'drive_type' => '4WD',
                'color_exterior' => 'Black',
                'color_interior' => 'Jet Black Leather',
                'doors' => 4,
                'seats' => 5,
                'mileage' => 38000,
                'vin' => '1GT12CE90LZ789456',
                'cargo_capacity_kg' => 900,
                'towing_capacity_kg' => 4200,
                'payload_capacity_kg' => 900,
                'bed_length_m' => 1.68,
                'bed_width_m' => 1.65,
                'axle_configuration' => '4x4',
                'price' => 110000000,
                'currency' => 'TZS',
                'original_price' => null,
                'negotiable' => true,
                'features' => ['Leather Seats', 'Heated and Ventilated Seats', 'Premium Sound System', 'Navigation', 'Sunroof', 'Power Tailgate', 'Bed Liner', 'Tool Box'],
                'safety_features' => ['ABS', 'Airbags', 'Forward Collision Alert', 'Lane Departure Warning', 'Blind Spot Alert', 'Rear Cross Traffic Alert', 'Parking Sensors', 'Backup Camera'],
                'status' => VehicleStatus::APPROVED->value,
                'registered_by' => $adminUser->id,
            ];
        }

        // Create trucks
        foreach ($trucks as $truckData) {
            Truck::create($truckData);
        }

        $this->command->info('Successfully seeded ' . count($trucks) . ' trucks!');
    }
}

