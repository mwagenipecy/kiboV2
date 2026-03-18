<?php

use App\Livewire\Customer\SparePartSourcing;
use App\Models\SparePartOrder;
use App\Models\User;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('submits spare part request and creates orders', function () {
    $user = User::factory()->create();

    $make = VehicleMake::create(['name' => 'Toyota']);
    $model = VehicleModel::create(['name' => 'Hilux', 'vehicle_make_id' => $make->id]);

    Livewire::actingAs($user)
        ->test(SparePartSourcing::class)
        ->set('customerPhone', '0712345678')
        ->set('vehicleMakeId', (string) $make->id)
        ->set('vehicleModelId', (string) $model->id)
        ->set('deliveryAddress', 'Sinza kwa Remi, Tan House')
        ->set('deliveryCity', 'Dar es Salaam')
        ->set('orderItems', [
            [
                'id' => 1,
                'part_number' => 'OEM-123',
                'part_name' => 'Brake pads',
                'quantity' => 2,
                'notes' => 'Front axle, OEM preferred.',
                'condition' => 'new',
                'images' => [],
            ],
            [
                'id' => 2,
                'part_number' => '',
                'part_name' => 'Oil filter',
                'quantity' => 1,
                'notes' => 'Engine 2.8, if available.',
                'condition' => 'any',
                'images' => [],
            ],
        ])
        ->call('submitOrders')
        ->assertSet('showSuccessModal', true)
        ->assertSet('showErrorModal', false);

    expect(SparePartOrder::query()->count())->toBe(2);

    $this->assertDatabaseHas('spare_part_orders', [
        'user_id' => $user->id,
        'vehicle_make_id' => $make->id,
        'vehicle_model_id' => $model->id,
        'customer_phone' => '0712345678',
        'delivery_city' => 'Dar es Salaam',
        'part_name' => 'Brake pads',
        'condition' => 'new',
        'status' => 'pending',
    ]);
});

