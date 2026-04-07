<?php

use App\Jobs\SendSparePartOrderPlacedSms;
use App\Livewire\Customer\SparePartSourcing;
use App\Models\SparePartOrder;
use App\Models\User;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('submits spare part request and creates orders', function () {
    $user = User::factory()->create();

    $make = VehicleMake::create(['name' => 'Toyota']);
    $model = VehicleModel::create(['name' => 'Hilux', 'vehicle_make_id' => $make->id]);

    Livewire::actingAs($user)
        ->test(SparePartSourcing::class, ['offerGuestPhoneOtp' => true])
        ->set('customerPhone', '0712345678')
        ->set('vehicleMakeId', (string) $make->id)
        ->set('vehicleModelId', (string) $model->id)
        ->set('deliveryAddress', 'Sinza Mori, Near Kitambaa Cheupe')
        ->set('deliveryCity', 'Dar es Salaam')
        ->set('orderItemIds', [1, 2])
        ->set('partNames', ['Brake pads', 'Oil filter'])
        ->set('quantities', [2, 1])
        ->set('partNumbers', ['OEM-123', ''])
        ->set('notes', ['Front axle, OEM preferred.', 'Engine 2.8, if available.'])
        ->set('conditions', ['new', 'any'])
        ->set('orderItemImages', [[], []])
        ->call('submitOrders')
        ->assertSet('showSuccessModal', true)
        ->assertSet('showErrorModal', false);

    expect(SparePartOrder::query()->count())->toBe(2);

    $orders = SparePartOrder::query()->orderBy('id')->get();
    expect($orders->first()->public_token)->toBeString()->toHaveLength(40);
    expect($orders->last()->public_token)->toBeString()->toHaveLength(40);
    expect($orders->pluck('public_token')->unique()->count())->toBe(2);

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

it('allows session-verified guest to submit and queues tracking sms', function () {
    Bus::fake();

    $make = VehicleMake::create(['name' => 'Toyota']);
    $model = VehicleModel::create(['name' => 'Hilux', 'vehicle_make_id' => $make->id]);

    session(['spare_parts_guest_phone' => '0712345678']);

    Livewire::test(SparePartSourcing::class, ['offerGuestPhoneOtp' => true])
        ->assertSet('guestAccessVerified', true)
        ->set('customerName', 'Guest User')
        ->set('customerEmail', 'guest@example.com')
        ->set('customerPhone', '0712345678')
        ->set('vehicleMakeId', (string) $make->id)
        ->set('vehicleModelId', (string) $model->id)
        ->set('deliveryAddress', 'Test street 1')
        ->set('deliveryCity', 'Dar es Salaam')
        ->set('orderItemIds', [1])
        ->set('partNames', ['Filter'])
        ->set('quantities', [1])
        ->set('partNumbers', [''])
        ->set('notes', ['Need soon'])
        ->set('conditions', ['new'])
        ->set('orderItemImages', [[]])
        ->call('submitOrders')
        ->assertSet('showSuccessModal', true);

    expect(SparePartOrder::query()->count())->toBe(1);
    $order = SparePartOrder::query()->first();
    expect($order->user_id)->toBeNull()
        ->and($order->order_channel)->toBe('guest_phone');

    Bus::assertDispatched(SendSparePartOrderPlacedSms::class, function (SendSparePartOrderPlacedSms $job) use ($order) {
        return $job->sparePartOrderId === $order->id;
    });
});
