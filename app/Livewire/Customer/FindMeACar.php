<?php

namespace App\Livewire\Customer;

use App\Mail\NewCarRequestMail;
use App\Models\CarRequest;
use App\Models\Entity;
use App\Models\Customer;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class FindMeACar extends Component
{
    public $vehicle_make_id = '';
    public $vehicle_model_id = '';

    public $min_year = '';
    public $max_year = '';
    public $min_budget = '';
    public $max_budget = '';

    public $fuel_type = '';
    public $transmission = '';
    public $body_type = '';
    public $color = '';
    public $location = '';
    public $notes = '';

    public $models = [];

    public ?string $helperSummary = null;

    public function mount(): void
    {
        if (auth()->check()) {
            // Pre-fill location / context if available
            $customer = Customer::where('user_id', auth()->id())->first();
            if ($customer && $customer->address) {
                $this->location = $customer->address;
            }
        }
    }

    public function updatedVehicleMakeId(): void
    {
        $this->vehicle_model_id = '';
        $this->models = [];

        if ($this->vehicle_make_id) {
            $this->models = VehicleModel::where('vehicle_make_id', $this->vehicle_make_id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name'])
                ->all();
        }
    }

    public function submit()
    {
        $validated = $this->validate([
            'vehicle_make_id' => ['nullable', 'integer', 'exists:vehicle_makes,id'],
            'vehicle_model_id' => ['nullable', 'integer', 'exists:vehicle_models,id'],
            'min_year' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'max_year' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'min_budget' => ['nullable', 'integer', 'min:0'],
            'max_budget' => ['nullable', 'integer', 'min:0'],
            'fuel_type' => ['nullable', 'string', 'max:50'],
            'transmission' => ['nullable', 'string', 'max:50'],
            'body_type' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if (!empty($validated['min_year']) && !empty($validated['max_year']) && $validated['min_year'] > $validated['max_year']) {
            $this->addError('min_year', 'Min year cannot be greater than max year.');
            return;
        }

        if (!empty($validated['min_budget']) && !empty($validated['max_budget']) && $validated['min_budget'] > $validated['max_budget']) {
            $this->addError('min_budget', 'Min budget cannot be greater than max budget.');
            return;
        }

        // Validate that model belongs to selected make
        if (!empty($validated['vehicle_model_id']) && !empty($validated['vehicle_make_id'])) {
            $model = VehicleModel::where('id', $validated['vehicle_model_id'])
                ->where('vehicle_make_id', $validated['vehicle_make_id'])
                ->exists();
            
            if (!$model) {
                $this->addError('vehicle_model_id', 'The selected model does not belong to the selected make.');
                return;
            }
        }

        // If model is selected but make is not, that's invalid
        if (!empty($validated['vehicle_model_id']) && empty($validated['vehicle_make_id'])) {
            $this->addError('vehicle_model_id', 'Please select a make first.');
            return;
        }

        $user = auth()->user();
        if (!$user) {
            // Dispatch browser event to open auth modal
            $this->dispatch('open-auth-modal');
            $this->addError('auth', 'Please sign in or create an account before sending your request to dealers.');
            return;
        }

        // Convert empty strings to null for optional fields
        $validated = array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $validated);

        // Fill customer identity from logged in user
        $validated['customer_name'] = $user->name;
        $validated['customer_email'] = $user->email;

        $customer = Customer::where('user_id', $user->id)->first();
        $validated['customer_phone'] = $customer?->phone_number ?? null;

        $validated['public_token'] = Str::uuid()->toString();
        $validated['status'] = 'open';
        $validated['user_id'] = auth()->id();

        $carRequest = CarRequest::create($validated);

        // Notify all dealer users by email
        $dealerUsers = Entity::where('type', 'dealer')
            ->where('status', 'active')
            ->with('users:id,entity_id,email')
            ->get()
            ->flatMap(fn ($e) => $e->users)
            ->filter(fn ($u) => !empty($u->email));

        foreach ($dealerUsers as $dealerUser) {
            Mail::to($dealerUser->email)->send(new NewCarRequestMail($carRequest));
        }

        session()->flash('find_me_success', 'Your request was sent to dealers. You will receive offers soon.');

        return redirect()->route('cars.find');
    }

    public function render()
    {
        $makes = VehicleMake::where('status', 'active')->orderBy('name')->get(['id', 'name']);

        return view('livewire.customer.find-me-a-car', [
            'makes' => $makes,
        ])->layout('layouts.customer');
    }
}


