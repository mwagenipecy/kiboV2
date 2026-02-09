<?php

namespace App\Livewire\Customer;

use App\Models\CarExchangeRequest;
use App\Models\Customer;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CarExchangeRequest extends Component
{
    use WithFileUploads;

    // Current vehicle fields
    public $current_vehicle_make = '';
    public $current_vehicle_model = '';
    public $current_vehicle_year = '';
    public $current_vehicle_registration = '';
    public $current_vehicle_mileage = '';
    public $current_vehicle_condition = '';
    public $current_vehicle_description = '';
    public $current_vehicle_images = [];

    // Desired vehicle fields
    public $desired_vehicle_make_id = '';
    public $desired_vehicle_model_id = '';
    public $desired_min_year = '';
    public $desired_max_year = '';
    public $desired_fuel_type = '';
    public $desired_transmission = '';
    public $desired_body_type = '';
    public $max_budget = '';

    // Additional fields
    public $notes = '';
    public $location = '';

    public $models = [];

    public function mount(): void
    {
        if (auth()->check()) {
            $customer = Customer::where('user_id', auth()->id())->first();
            if ($customer && $customer->address) {
                $this->location = $customer->address;
            }
        }
    }

    public function updatedDesiredVehicleMakeId(): void
    {
        $this->desired_vehicle_model_id = '';
        $this->models = [];

        if ($this->desired_vehicle_make_id) {
            $this->models = VehicleModel::where('vehicle_make_id', $this->desired_vehicle_make_id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name'])
                ->all();
        }
    }

    public function submit()
    {
        $validated = $this->validate([
            // Current vehicle
            'current_vehicle_make' => ['required', 'string', 'max:100'],
            'current_vehicle_model' => ['required', 'string', 'max:100'],
            'current_vehicle_year' => ['required', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'current_vehicle_registration' => ['nullable', 'string', 'max:50'],
            'current_vehicle_mileage' => ['nullable', 'integer', 'min:0'],
            'current_vehicle_condition' => ['required', 'string', 'in:excellent,good,fair,poor'],
            'current_vehicle_description' => ['nullable', 'string', 'max:2000'],
            'current_vehicle_images.*' => ['nullable', 'image', 'max:5120'], // 5MB max per image

            // Desired vehicle
            'desired_vehicle_make_id' => ['nullable', 'integer', 'exists:vehicle_makes,id'],
            'desired_vehicle_model_id' => ['nullable', 'integer', 'exists:vehicle_models,id'],
            'desired_min_year' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'desired_max_year' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'desired_fuel_type' => ['nullable', 'string', 'max:50'],
            'desired_transmission' => ['nullable', 'string', 'max:50'],
            'desired_body_type' => ['nullable', 'string', 'max:50'],
            'max_budget' => ['nullable', 'integer', 'min:0'],

            // Additional
            'notes' => ['nullable', 'string', 'max:2000'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        if (!empty($validated['desired_min_year']) && !empty($validated['desired_max_year']) && $validated['desired_min_year'] > $validated['desired_max_year']) {
            $this->addError('desired_min_year', 'Min year cannot be greater than max year.');
            return;
        }

        if (!empty($validated['desired_vehicle_model_id']) && !empty($validated['desired_vehicle_make_id'])) {
            $model = VehicleModel::where('id', $validated['desired_vehicle_model_id'])
                ->where('vehicle_make_id', $validated['desired_vehicle_make_id'])
                ->exists();
            
            if (!$model) {
                $this->addError('desired_vehicle_model_id', 'The selected model does not belong to the selected make.');
                return;
            }
        }

        if (!empty($validated['desired_vehicle_model_id']) && empty($validated['desired_vehicle_make_id'])) {
            $this->addError('desired_vehicle_model_id', 'Please select a make first.');
            return;
        }

        $user = auth()->user();
        if (!$user) {
            $this->dispatch('open-auth-modal');
            $this->addError('auth', 'Please sign in or create an account before submitting your exchange request.');
            return;
        }

        // Handle image uploads
        $imagePaths = [];
        if (!empty($this->current_vehicle_images)) {
            foreach ($this->current_vehicle_images as $image) {
                $path = $image->store('car-exchange-requests', 'public');
                $imagePaths[] = $path;
            }
        }

        // Convert empty strings to null
        $validated = array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $validated);

        $validated['customer_name'] = $user->name;
        $validated['customer_email'] = $user->email;
        $customer = Customer::where('user_id', $user->id)->first();
        $validated['customer_phone'] = $customer?->phone_number ?? null;
        $validated['public_token'] = Str::uuid()->toString();
        $validated['status'] = 'pending';
        $validated['user_id'] = auth()->id();
        $validated['current_vehicle_images'] = $imagePaths;

        CarExchangeRequest::create($validated);

        session()->flash('exchange_success', 'Your exchange request has been submitted. Our admin team will review it and send it to dealers soon.');

        return redirect()->route('car-exchange.index');
    }

    public function render()
    {
        $makes = VehicleMake::where('status', 'active')->orderBy('name')->get(['id', 'name']);

        return view('livewire.customer.car-exchange-request', [
            'makes' => $makes,
        ])->layout('layouts.customer');
    }
}
