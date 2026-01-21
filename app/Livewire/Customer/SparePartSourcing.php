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

    // Order items array (now includes make/model per item for bulk orders)
    public $orderItems = [];

    // Customer information
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    public $company = '';

    // Vehicle information (for single orders only)
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
    public $isLoggedIn = false;

    public function mount()
    {
        $this->isLoggedIn = Auth::check();
        
        $this->vehicleMakes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Initialize with one empty order item (with make/model for bulk)
        $this->orderItems = [
            [
                'id' => 1,
                'part_number' => '',
                'part_name' => '',
                'quantity' => 1,
                'notes' => '',
                'vehicle_make_id' => '',
                'vehicle_model_id' => '',
                'available_models' => [],
            ]
        ];
        
        // Pre-fill user information if logged in
        if ($this->isLoggedIn) {
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
        // Reset to single item with appropriate fields
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
        } else {
            // Bulk mode - add make/model fields
            $this->orderItems = [
                [
                    'id' => 1,
                    'part_number' => '',
                    'part_name' => '',
                    'quantity' => 1,
                    'notes' => '',
                    'vehicle_make_id' => '',
                    'vehicle_model_id' => '',
                    'available_models' => [],
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
            'vehicle_make_id' => '',
            'vehicle_model_id' => '',
            'available_models' => [],
        ];
    }
    
    public function updatedOrderItems($value, $key)
    {
        // Handle make/model updates for bulk orders
        if (str_contains($key, 'vehicle_make_id')) {
            $index = explode('.', $key)[0];
            $this->orderItems[$index]['vehicle_model_id'] = '';
            
            if ($this->orderItems[$index]['vehicle_make_id']) {
                $this->orderItems[$index]['available_models'] = VehicleModel::where('vehicle_make_id', $this->orderItems[$index]['vehicle_make_id'])
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->get()
                    ->toArray();
            } else {
                $this->orderItems[$index]['available_models'] = [];
            }
        }
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
        // Check if user is logged in
        if (!Auth::check()) {
            session()->flash('error', 'Please login to submit your spare part order.');
            return;
        }

        // Validate based on order type
        $rules = [
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => 'required|string|max:20',
            'deliveryAddress' => 'required|string',
            'orderItems' => 'required|array|min:1',
            'orderItems.*.part_number' => 'required|string|max:255',
            'orderItems.*.part_name' => 'required|string|max:255',
            'orderItems.*.quantity' => 'required|integer|min:1',
        ];
        
        // For single order, validate global make/model
        if ($this->orderType === 'single') {
            $rules['vehicleMakeId'] = 'required|exists:vehicle_makes,id';
            $rules['vehicleModelId'] = 'required|exists:vehicle_models,id';
        } else {
            // For bulk order, validate make/model per item
            $rules['orderItems.*.vehicle_make_id'] = 'required|exists:vehicle_makes,id';
            $rules['orderItems.*.vehicle_model_id'] = 'required|exists:vehicle_models,id';
        }
        
        $this->validate($rules, [
            'vehicleMakeId.required' => 'Please select a vehicle make.',
            'vehicleModelId.required' => 'Please select a vehicle model.',
            'orderItems.*.vehicle_make_id.required' => 'Please select a vehicle make for all items.',
            'orderItems.*.vehicle_model_id.required' => 'Please select a vehicle model for all items.',
            'orderItems.*.part_number.required' => 'Part number is required for all items.',
            'orderItems.*.part_name.required' => 'Part name is required for all items.',
        ]);

        // Create orders - one order per item
        $createdOrders = [];
        foreach ($this->orderItems as $item) {
            // Use item-specific make/model for bulk, or global for single
            $makeId = $this->orderType === 'bulk' ? $item['vehicle_make_id'] : $this->vehicleMakeId;
            $modelId = $this->orderType === 'bulk' ? $item['vehicle_model_id'] : $this->vehicleModelId;
            
            $order = SparePartOrder::create([
                'order_number' => SparePartOrder::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'vehicle_make_id' => $makeId,
                'vehicle_model_id' => $modelId,
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
        // Keep customer info but reset order items
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
        } else {
            $this->orderItems = [
                [
                    'id' => 1,
                    'part_number' => '',
                    'part_name' => '',
                    'quantity' => 1,
                    'notes' => '',
                    'vehicle_make_id' => '',
                    'vehicle_model_id' => '',
                    'available_models' => [],
                ]
            ];
        }
        
        $this->reset(['vehicleMakeId', 'vehicleModelId', 'vehicleYear', 'vehicleVin', 'deliveryAddress', 'deliveryCity', 'deliveryRegion', 'deliveryPostalCode']);
        $this->submitted = false;
        $this->vehicleModels = [];
        
        // Re-populate user info if logged in
        if ($this->isLoggedIn) {
            $user = Auth::user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
        }
    }

    public function render()
    {
        return view('livewire.customer.spare-part-sourcing');
    }
}

