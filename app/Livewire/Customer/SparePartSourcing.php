<?php

namespace App\Livewire\Customer;

use App\Models\SparePartOrder;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SparePartSourcing extends Component
{
    use WithFileUploads;

    // Order type: single or bulk
    public $orderType = 'single';

    // Order items array
    public $orderItems = [];

    // Customer information
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    public $company = '';

    // Vehicle information
    public $vehicleMakeId = '';
    public $vehicleModelId = '';
    public $vehicleYear = '';
    public $vehicleVin = '';

    // Delivery information
    public $deliveryAddress = '';
    public $deliveryCity = '';
    public $deliveryRegion = '';
    public $deliveryCountry = 'Tanzania';
    public $deliveryPostalCode = '';

    public $vehicleMakes = [];
    public $vehicleModels = [];
    public $submitted = false;

    public function mount()
    {
        $this->vehicleMakes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Initialize with one empty order item
        $this->orderItems = [
            [
                'id' => 1,
                'part_number' => '',
                'part_name' => '',
                'quantity' => 1,
                'notes' => '',
            ]
        ];
        
        // Pre-fill user information if logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
        }
    }

    public function updatedVehicleMakeId()
    {
        $this->vehicleModelId = '';
        if ($this->vehicleMakeId) {
            $this->vehicleModels = VehicleModel::where('vehicle_make_id', $this->vehicleMakeId)
                ->orderBy('name')
                ->get();
        } else {
            $this->vehicleModels = [];
        }
    }

    public function updatedOrderType()
    {
        // Reset to single item if switching to single order
        if ($this->orderType === 'single') {
            $this->orderItems = [
                [
                    'id' => 1,
                    'part_number' => '',
                    'part_name' => '',
                    'quantity' => 1,
                    'notes' => '',
                ]
            ];
        }
    }

    public function addOrderItem()
    {
        $this->orderItems[] = [
            'id' => count($this->orderItems) + 1,
            'part_number' => '',
            'part_name' => '',
            'quantity' => 1,
            'notes' => '',
        ];
    }

    public function removeOrderItem($id)
    {
        if (count($this->orderItems) > 1) {
            $this->orderItems = array_values(array_filter($this->orderItems, function($item) use ($id) {
                return $item['id'] != $id;
            }));
        }
    }

    public function updateOrderItem($id, $field, $value)
    {
        foreach ($this->orderItems as $key => $item) {
            if ($item['id'] == $id) {
                $this->orderItems[$key][$field] = $value;
                break;
            }
        }
    }

    public function submitOrders()
    {
        // Validate
        $this->validate([
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => 'required|string|max:20',
            'vehicleMakeId' => 'required|exists:vehicle_makes,id',
            'vehicleModelId' => 'required|exists:vehicle_models,id',
            'deliveryAddress' => 'required|string',
            'orderItems' => 'required|array|min:1',
            'orderItems.*.part_number' => 'required|string|max:255',
            'orderItems.*.part_name' => 'required|string|max:255',
            'orderItems.*.quantity' => 'required|integer|min:1',
        ], [
            'vehicleMakeId.required' => 'Please select a vehicle make.',
            'vehicleModelId.required' => 'Please select a vehicle model.',
            'orderItems.*.part_number.required' => 'Part number is required for all items.',
            'orderItems.*.part_name.required' => 'Part name is required for all items.',
        ]);

        // Create orders - one order per item
        $createdOrders = [];
        foreach ($this->orderItems as $item) {
            $order = SparePartOrder::create([
                'order_number' => SparePartOrder::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'vehicle_make_id' => $this->vehicleMakeId,
                'vehicle_model_id' => $this->vehicleModelId,
                'condition' => 'new', // Default to new
                'part_name' => $item['part_name'],
                'description' => ($item['part_number'] ?? '') . ($item['notes'] ? ' - ' . $item['notes'] : ''),
                'images' => [],
                'delivery_address' => $this->deliveryAddress,
                'delivery_city' => $this->deliveryCity,
                'delivery_region' => $this->deliveryRegion,
                'delivery_country' => $this->deliveryCountry,
                'delivery_postal_code' => $this->deliveryPostalCode,
                'delivery_latitude' => null,
                'delivery_longitude' => null,
                'contact_name' => $this->customerName,
                'contact_phone' => $this->customerPhone,
                'contact_email' => $this->customerEmail,
                'status' => 'pending',
            ]);
            
            $createdOrders[] = $order;
        }

        $this->submitted = true;
        session()->flash('success', 'Your spare part order(s) have been submitted successfully! Order numbers: ' . implode(', ', array_column($createdOrders, 'order_number')));
        
        // Reset form after showing success
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->orderItems = [
            [
                'id' => 1,
                'part_number' => '',
                'part_name' => '',
                'quantity' => 1,
                'notes' => '',
            ]
        ];
        $this->reset(['customerName', 'customerEmail', 'customerPhone', 'company', 'vehicleMakeId', 'vehicleModelId', 'vehicleYear', 'vehicleVin', 'deliveryAddress', 'deliveryCity', 'deliveryRegion', 'deliveryPostalCode']);
        $this->submitted = false;
        $this->vehicleModels = [];
    }

    public function render()
    {
        return view('livewire.customer.spare-part-sourcing');
    }
}

