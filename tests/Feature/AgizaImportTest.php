<?php

namespace Tests\Feature;

use App\Models\AgizaImportRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AgizaImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_submit_agiza_import_request()
    {
        Livewire::test(\App\Livewire\Customer\AgizaImport::class)
            ->set('vehicleMake', 'Toyota')
            ->set('vehicleModel', 'Land Cruiser')
            ->set('sourceCountry', 'Japan')
            ->set('vehicleLink', 'https://example.com/car')
            ->call('submit')
            ->assertSet('showErrorModal', true);
    }

    public function test_authenticated_user_can_submit_agiza_import_request_with_link()
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $this->actingAs($user);

        Livewire::test(\App\Livewire\Customer\AgizaImport::class)
            ->set('customerName', $user->name)
            ->set('customerEmail', $user->email)
            ->set('customerPhone', '0712345678')
            ->set('requestType', 'with_link')
            ->set('vehicleMake', 'Toyota')
            ->set('vehicleModel', 'Land Cruiser')
            ->set('sourceCountry', 'Japan')
            ->set('vehicleLink', 'https://example.com/car')
            ->call('submit')
            ->assertSet('showSuccessModal', true);

        $this->assertDatabaseHas('agiza_import_requests', [
            'user_id' => $user->id,
            'vehicle_make' => 'Toyota',
            'vehicle_model' => 'Land Cruiser',
            'source_country' => 'Japan',
            'request_type' => 'with_link',
            'status' => 'pending',
        ]);
    }

    public function test_authenticated_user_can_submit_agiza_import_request_with_dealer_contact()
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $this->actingAs($user);

        Livewire::test(\App\Livewire\Customer\AgizaImport::class)
            ->set('customerName', $user->name)
            ->set('customerEmail', $user->email)
            ->set('customerPhone', '0712345678')
            ->set('requestType', 'already_contacted')
            ->set('vehicleMake', 'Toyota')
            ->set('vehicleModel', 'Land Cruiser')
            ->set('sourceCountry', 'Japan')
            ->set('dealerContactInfo', 'John Doe, +81-123-456-7890, john@dealer.com')
            ->call('submit')
            ->assertSet('showSuccessModal', true);

        $this->assertDatabaseHas('agiza_import_requests', [
            'user_id' => $user->id,
            'vehicle_make' => 'Toyota',
            'vehicle_model' => 'Land Cruiser',
            'request_type' => 'already_contacted',
            'status' => 'pending',
        ]);
    }

    public function test_request_number_is_generated_correctly()
    {
        $user = User::factory()->create(['role' => 'customer']);
        
        $request = AgizaImportRequest::create([
            'request_number' => AgizaImportRequest::generateRequestNumber(),
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '0712345678',
            'vehicle_make' => 'Toyota',
            'vehicle_model' => 'Land Cruiser',
            'source_country' => 'Japan',
            'request_type' => 'with_link',
            'vehicle_link' => 'https://example.com',
            'status' => 'pending',
        ]);

        $this->assertMatchesRegularExpression('/^AGZ-\d{8}-\d{4}$/', $request->request_number);
    }

    public function test_admin_can_view_agiza_import_requests()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'customer']);

        AgizaImportRequest::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\AgizaImportRequests::class)
            ->assertSee('Agiza/Import Requests')
            ->assertSee('pending');
    }

    public function test_admin_can_update_request_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'customer']);

        $request = AgizaImportRequest::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\AgizaImportRequestDetail::class, ['id' => $request->id])
            ->set('status', 'under_review')
            ->call('updateStatus')
            ->assertSet('showSuccessMessage', true);

        $this->assertDatabaseHas('agiza_import_requests', [
            'id' => $request->id,
            'status' => 'under_review',
        ]);
    }

    public function test_admin_can_provide_quote()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'customer']);

        $request = AgizaImportRequest::factory()->create([
            'user_id' => $user->id,
            'status' => 'under_review',
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\AgizaImportRequestDetail::class, ['id' => $request->id])
            ->set('quotedImportCost', 5000)
            ->set('quotedTotalCost', 7500)
            ->set('quoteCurrency', 'USD')
            ->call('provideQuote')
            ->assertSet('showSuccessMessage', true);

        $this->assertDatabaseHas('agiza_import_requests', [
            'id' => $request->id,
            'quoted_import_cost' => 5000,
            'quoted_total_cost' => 7500,
            'status' => 'quote_provided',
        ]);
    }
}
