<?php

namespace App\Console\Commands;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\User;
use Illuminate\Console\Command;

class CreateTestVehicle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicle:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test vehicles with different statuses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->error('No admin user found. Please run the AdminUserSeeder first.');
            return 1;
        }

        $makes = VehicleMake::with('vehicleModels')->get();
        
        if ($makes->isEmpty()) {
            $this->error('No vehicle makes found. Please run the VehicleMakeModelSeeder first.');
            return 1;
        }

        $statuses = [
            VehicleStatus::PENDING,
            VehicleStatus::AWAITING_APPROVAL,
            VehicleStatus::APPROVED,
            VehicleStatus::HOLD,
        ];

        $this->info('Creating test vehicles...');

        foreach ($statuses as $status) {
            // Create 2 vehicles for each status
            for ($i = 1; $i <= 2; $i++) {
                $make = $makes->random();
                $model = $make->vehicleModels->random();

                Vehicle::create([
                    'title' => $make->name . ' ' . $model->name . ' ' . date('Y') . ' - ' . $status->label(),
                    'description' => 'This is a test vehicle with status: ' . $status->label(),
                    'origin' => rand(0, 1) ? 'local' : 'international',
                    'condition' => 'used',
                    'vehicle_make_id' => $make->id,
                    'vehicle_model_id' => $model->id,
                    'year' => rand(2018, 2024),
                    'body_type' => 'Sedan',
                    'fuel_type' => 'Petrol',
                    'transmission' => 'Automatic',
                    'engine_capacity' => '2.0L',
                    'color_exterior' => 'Silver',
                    'color_interior' => 'Black',
                    'doors' => 4,
                    'seats' => 5,
                    'mileage' => rand(10000, 100000),
                    'price' => rand(15000000, 50000000),
                    'currency' => 'TZS',
                    'negotiable' => true,
                    'registered_by' => $admin->id,
                    'status' => $status,
                ]);

                $this->info('Created: ' . $make->name . ' ' . $model->name . ' (' . $status->label() . ')');
            }
        }

        $this->info('✓ Successfully created ' . (count($statuses) * 2) . ' test vehicles!');
        $this->newLine();
        $this->info('Vehicle counts by status:');
        $this->info('- Pending: ' . Vehicle::where('status', VehicleStatus::PENDING)->count());
        $this->info('- Awaiting Approval: ' . Vehicle::where('status', VehicleStatus::AWAITING_APPROVAL)->count());
        $this->info('- Approved: ' . Vehicle::where('status', VehicleStatus::APPROVED)->count());
        $this->info('- On Hold: ' . Vehicle::where('status', VehicleStatus::HOLD)->count());

        return 0;
    }
}
