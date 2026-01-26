<?php

namespace App\Livewire\Customer;

use App\Models\Agent;
use App\Models\GarageServiceOrder;
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
            // Available services for the selected garage
            $this->availableServices = (!empty($data['availableServices']) && is_array($data['availableServices']))
                ? array_values($data['availableServices'])
                : [];

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
            $this->agentId = $data['agentId'] ?? null;
            $this->agent_id = $data['agentId'] ?? null;
            $this->agentName = $data['agentName'] ?? '';

            // If we have available services, default the selection to the first available service
            // when nothing is preselected.
            if (empty($this->services) && !empty($this->availableServices)) {
                $this->services = [$this->availableServices[0]];
                $this->serviceType = $this->availableServices[0];
                $this->service_type = $this->availableServices[0];
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
        $this->vehicle_year = '';
        $this->vehicle_registration = '';
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
            'vehicle_registration' => $this->vehicle_registration ?: null,
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
