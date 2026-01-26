<?php

namespace App\Livewire\Customer;

use App\Models\Agent;
use App\Models\GarageServiceOrder;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GarageServiceBooking extends Component
{
    public $showModal = false;
    public $serviceType = '';
    public $service_type = '';
    public array $services = [];
    public array $availableServices = [];
    public $agentId = null;
    public $agent_id = null;
    public $agentName = '';
    
    // Form fields
    public $customer_name = '';
    public $customer_email = '';
    public $customer_phone = '';
    public $vehicle_make = '';
    public $vehicle_model = '';
    public $vehicle_make_id = '';
    public $vehicle_model_id = '';
    public array $availableMakes = [];
    public array $availableModels = [];
    public $vehicle_year = '';
    public $vehicle_registration = '';
    public $booking_type = 'scheduled';
    public $scheduled_date = '';
    public $scheduled_time = '';
    public $service_description = '';
    public $customer_notes = '';

    public function mount($serviceType = '', $agentId = null, $agentName = '')
    {
        $this->serviceType = $serviceType;
        $this->agentId = $agentId;
        $this->agentName = $agentName;
        
        // Pre-fill user data if logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->customer_name = $user->name;
            $this->customer_email = $user->email;
            if ($user->phone) {
                $this->customer_phone = $user->phone;
            }
        }
    }

    public function openModal($serviceType, $agentId = null, $agentName = '')
    {
        $this->serviceType = $serviceType;
        $this->agentId = $agentId;
        $this->agentName = $agentName;
        $this->showModal = true;
    }

    protected $listeners = ['openBookingModal'];

    public function openBookingModal(...$params)
    {
        // Handle both array and single parameter
        $data = $params[0] ?? null;
        
        if ($data && is_array($data)) {
            $this->agentId = $data['agentId'] ?? null;
            $this->agent_id = $data['agentId'] ?? null;
            $this->agentName = $data['agentName'] ?? '';

            // Fetch available services from database if agentId is provided
            if ($this->agentId) {
                $garage = Agent::find($this->agentId);
                if ($garage && !empty($garage->services)) {
                    $this->availableServices = is_array($garage->services) 
                        ? array_values($garage->services) 
                        : [];
                }

                // Fetch available vehicle makes for this garage (based on agent.vehicle_makes)
                $this->availableMakes = [];
                $this->availableModels = [];
                $this->vehicle_make_id = '';
                $this->vehicle_model_id = '';

                $makeIds = is_array($garage?->vehicle_makes) ? array_filter($garage->vehicle_makes) : [];
                if (!empty($makeIds)) {
                    $this->availableMakes = VehicleMake::query()
                        ->whereIn('id', $makeIds)
                        ->where('status', 'active')
                        ->orderBy('name')
                        ->get(['id', 'name'])
                        ->map(fn($m) => ['id' => $m->id, 'name' => $m->name])
                        ->all();
                }
            }

            // Override with provided availableServices if they exist
            if (!empty($data['availableServices']) && is_array($data['availableServices'])) {
                $this->availableServices = array_values($data['availableServices']);
            }

            // Multiple services
            if (!empty($data['services']) && is_array($data['services'])) {
                $this->services = $data['services'];
                $this->serviceType = $this->services[0] ?? '';
                $this->service_type = $this->serviceType;
            } else {
                $this->serviceType = $data['serviceType'] ?? '';
                $this->service_type = $this->serviceType;
                $this->services = $this->serviceType ? [$this->serviceType] : [];
            }

            // If we have available services but no preselected services, don't auto-select
            // Let user choose from the list
            if (empty($this->services) && !empty($this->availableServices)) {
                // Don't auto-select - let user choose
                $this->services = [];
            }
        } elseif ($data) {
            $this->serviceType = $data;
            $this->service_type = $data;
            $this->services = [$data];
            $this->availableServices = [];
        }
        
        // Pre-fill user data if logged in and not already set
        if (Auth::check() && empty($this->customer_name)) {
            $user = Auth::user();
            $this->customer_name = $user->name;
            $this->customer_email = $user->email;
            if ($user->phone) {
                $this->customer_phone = $user->phone;
            }
        }
        
        $this->showModal = true;
    }

    public function updatedVehicleMakeId(): void
    {
        $this->vehicle_model_id = '';
        $this->availableModels = [];

        if (!$this->vehicle_make_id) {
            return;
        }

        $this->availableModels = VehicleModel::query()
            ->where('vehicle_make_id', $this->vehicle_make_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn($m) => ['id' => $m->id, 'name' => $m->name])
            ->all();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->customer_name = '';
        $this->customer_email = '';
        $this->customer_phone = '';
        $this->vehicle_make = '';
        $this->vehicle_model = '';
        $this->vehicle_make_id = '';
        $this->vehicle_model_id = '';
        $this->availableMakes = [];
        $this->availableModels = [];
        $this->vehicle_year = '';
        $this->booking_type = 'scheduled';
        $this->scheduled_date = '';
        $this->scheduled_time = '';
        $this->service_description = '';
        $this->customer_notes = '';
        $this->services = [];
        $this->availableServices = [];
    }

    public function save()
    {
        $this->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'services' => 'required|array|min:1',
            'services.*' => 'string',
            'agent_id' => 'required|exists:agents,id',
            'booking_type' => 'required|in:immediate,scheduled',
            'scheduled_date' => 'required_if:booking_type,scheduled|nullable|date|after_or_equal:today',
            'scheduled_time' => 'required_if:booking_type,scheduled|nullable',
            'vehicle_make_id' => 'nullable|integer|exists:vehicle_makes,id',
            'vehicle_model_id' => 'nullable|integer|exists:vehicle_models,id',
        ], [], [
            'services' => 'services',
            'agent_id' => 'garage',
        ]);

        $selectedServices = $this->services;
        if (empty($selectedServices) && $this->service_type) {
            $selectedServices = [$this->service_type];
        }
        $primaryService = $selectedServices[0] ?? null;

        $servicesLabel = '';
        if (!empty($selectedServices)) {
            $servicesLabel = collect($selectedServices)
                ->map(fn($s) => ucwords(str_replace('_', ' ', $s)))
                ->implode(', ');
        }

        // Fill labels for storage (existing schema stores strings)
        $this->vehicle_make = '';
        $this->vehicle_model = '';
        if ($this->vehicle_make_id) {
            $make = VehicleMake::find($this->vehicle_make_id);
            $this->vehicle_make = $make?->name ?? '';
        }
        if ($this->vehicle_model_id) {
            $model = VehicleModel::find($this->vehicle_model_id);
            $this->vehicle_model = $model?->name ?? '';
        }

        $data = [
            'user_id' => Auth::id(),
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'agent_id' => $this->agentId ?? $this->agent_id,
            'service_type' => $primaryService ?? $this->serviceType ?? $this->service_type ?? '',
            'vehicle_make' => $this->vehicle_make ?: null,
            'vehicle_model' => $this->vehicle_model ?: null,
            'vehicle_year' => $this->vehicle_year ?: null,
            'booking_type' => $this->booking_type,
            'scheduled_date' => $this->booking_type === 'scheduled' ? $this->scheduled_date : null,
            'scheduled_time' => $this->booking_type === 'scheduled' ? $this->scheduled_time : null,
            'service_description' => $servicesLabel
                ? trim(($this->service_description ? $this->service_description . "\n\n" : '') . 'Requested services: ' . $servicesLabel)
                : ($this->service_description ?: null),
            'customer_notes' => $this->customer_notes ?: null,
            'status' => 'pending',
        ];

        GarageServiceOrder::create($data);

        session()->flash('success', 'Service booking submitted successfully! You can track your order below.');
        $this->closeModal();
        $this->dispatch('booking-created');
    }

    public function render()
    {
        return view('livewire.customer.garage-service-booking');
    }
}
