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

    // Order items (multiple orders)
    public $orders = [];
    public $currentOrderIndex = 0;

    // Current order fields
    public $vehicleMakeId = '';
    public $vehicleModelId = '';
    public $condition = 'new';
    public $partName = '';
    public $description = '';
    public $images = [];
    public $tempImages = [];

    // Customer information (shared across orders)
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';

    // Delivery information (shared across orders)
    public $deliveryAddress = '';
    public $deliveryCity = '';
    public $deliveryRegion = '';
    public $deliveryCountry = 'Tanzania';
    public $deliveryPostalCode = '';
    public $deliveryLatitude = '';
    public $deliveryLongitude = '';

    // Contact information (shared across orders)
    public $contactName = '';
    public $contactPhone = '';
    public $contactEmail = '';

    public $vehicleMakes = [];
    public $vehicleModels = [];
    public $showLocationModal = false;
    public $allVehicleModels = []; // Store all models for table display

    public function mount()
    {
        $this->vehicleMakes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get();
        
        $this->allVehicleModels = VehicleModel::with('vehicleMake')
            ->orderBy('name')
            ->get();
        
        // Initialize with one empty order
        $this->addNewOrder();
        
        // Pre-fill user information if logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
            $this->contactName = $user->name;
            $this->contactEmail = $user->email;
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
        // Auto-save when make changes
        $this->saveCurrentOrder();
    }

    public function addNewOrder()
    {
        // Save current order before adding new one
        $this->saveCurrentOrder();
        
        $this->orders[] = [
            'vehicle_make_id' => '',
            'vehicle_model_id' => '',
            'condition' => 'new',
            'part_name' => '',
            'description' => '',
            'images' => [],
        ];
        $this->currentOrderIndex = count($this->orders) - 1;
        $this->resetCurrentOrder();
    }

    public function removeOrder($index)
    {
        unset($this->orders[$index]);
        $this->orders = array_values($this->orders); // Re-index array
        
        if ($this->currentOrderIndex >= count($this->orders)) {
            $this->currentOrderIndex = max(0, count($this->orders) - 1);
        }
        
        if (count($this->orders) === 0) {
            $this->addNewOrder();
        }
    }

    public function selectOrder($index)
    {
        if ($this->currentOrderIndex !== $index) {
            $this->saveCurrentOrder();
            $this->currentOrderIndex = $index;
            $this->loadCurrentOrder();
        }
    }

    public function resetCurrentOrder()
    {
        $this->vehicleMakeId = '';
        $this->vehicleModelId = '';
        $this->condition = 'new';
        $this->partName = '';
        $this->description = '';
        $this->images = [];
        $this->tempImages = [];
        $this->vehicleModels = [];
    }

    public function loadCurrentOrder()
    {
        if (isset($this->orders[$this->currentOrderIndex])) {
            $order = $this->orders[$this->currentOrderIndex];
            $this->vehicleMakeId = $order['vehicle_make_id'] ?? '';
            $this->vehicleModelId = $order['vehicle_model_id'] ?? '';
            $this->condition = $order['condition'] ?? 'new';
            $this->partName = $order['part_name'] ?? '';
            $this->description = $order['description'] ?? '';
            $this->images = $order['images'] ?? [];
            
            // Load models for the selected make without triggering save
            if ($this->vehicleMakeId) {
                $this->vehicleModels = VehicleModel::where('vehicle_make_id', $this->vehicleMakeId)
                    ->orderBy('name')
                    ->get();
            } else {
                $this->vehicleModels = [];
            }
        }
    }

    public function saveCurrentOrder()
    {
        if (isset($this->orders[$this->currentOrderIndex])) {
            $this->orders[$this->currentOrderIndex] = [
                'vehicle_make_id' => $this->vehicleMakeId,
                'vehicle_model_id' => $this->vehicleModelId,
                'condition' => $this->condition,
                'part_name' => $this->partName,
                'description' => $this->description,
                'images' => $this->images,
            ];
        }
    }

    public function updatedVehicleModelId()
    {
        $this->saveCurrentOrder();
    }

    public function updatedCondition()
    {
        $this->saveCurrentOrder();
    }

    public function updatedPartName()
    {
        $this->saveCurrentOrder();
    }

    public function updatedDescription()
    {
        $this->saveCurrentOrder();
    }

    public function updatedTempImages()
    {
        $this->validate([
            'tempImages.*' => 'image|max:5120', // 5MB max
        ]);

        foreach ($this->tempImages as $image) {
            $path = $image->store('spare-part-orders', 'public');
            $this->images[] = $path;
        }
        
        $this->tempImages = [];
        // Auto-save when images are added
        $this->saveCurrentOrder();
    }

    public function removeImage($index)
    {
        if (isset($this->images[$index])) {
            $path = $this->images[$index];
            Storage::disk('public')->delete($path);
            unset($this->images[$index]);
            $this->images = array_values($this->images);
            // Auto-save when image is removed
            $this->saveCurrentOrder();
        }
    }

    public function getCurrentLocation()
    {
        $this->showLocationModal = true;
        $this->dispatch('request-location');
    }

    public function setLocation($latitude, $longitude)
    {
        $this->deliveryLatitude = $latitude;
        $this->deliveryLongitude = $longitude;
        $this->showLocationModal = false;
    }

    public function submitOrders()
    {
        $this->saveCurrentOrder();

        // Validate all orders
        $this->validate([
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => 'required|string|max:20',
            'deliveryAddress' => 'required|string',
            'deliveryCity' => 'nullable|string|max:255',
            'deliveryRegion' => 'nullable|string|max:255',
            'contactName' => 'required|string|max:255',
            'contactPhone' => 'required|string|max:20',
            'contactEmail' => 'nullable|email|max:255',
            'orders' => 'required|array|min:1',
            'orders.*.vehicle_make_id' => 'required|exists:vehicle_makes,id',
            'orders.*.vehicle_model_id' => 'required|exists:vehicle_models,id',
            'orders.*.condition' => 'required|in:new,used',
        ], [
            'orders.*.vehicle_make_id.required' => 'Please select a vehicle make for all orders.',
            'orders.*.vehicle_model_id.required' => 'Please select a vehicle model for all orders.',
        ]);

        // Create orders
        $createdOrders = [];
        foreach ($this->orders as $orderData) {
            $order = SparePartOrder::create([
                'order_number' => SparePartOrder::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'vehicle_make_id' => $orderData['vehicle_make_id'],
                'vehicle_model_id' => $orderData['vehicle_model_id'],
                'condition' => $orderData['condition'],
                'part_name' => $orderData['part_name'] ?? null,
                'description' => $orderData['description'] ?? null,
                'images' => $orderData['images'] ?? [],
                'delivery_address' => $this->deliveryAddress,
                'delivery_city' => $this->deliveryCity,
                'delivery_region' => $this->deliveryRegion,
                'delivery_country' => $this->deliveryCountry,
                'delivery_postal_code' => $this->deliveryPostalCode,
                'delivery_latitude' => $this->deliveryLatitude ?: null,
                'delivery_longitude' => $this->deliveryLongitude ?: null,
                'contact_name' => $this->contactName,
                'contact_phone' => $this->contactPhone,
                'contact_email' => $this->contactEmail,
                'status' => 'pending',
            ]);
            
            $createdOrders[] = $order;
        }

        session()->flash('success', 'Your spare part order(s) have been submitted successfully! Order numbers: ' . implode(', ', array_column($createdOrders, 'order_number')));
        
        // Reset form
        $this->orders = [];
        $this->addNewOrder();
        $this->reset(['customerName', 'customerEmail', 'customerPhone', 'deliveryAddress', 'deliveryCity', 'deliveryRegion', 'deliveryPostalCode', 'deliveryLatitude', 'deliveryLongitude', 'contactName', 'contactPhone', 'contactEmail']);
        
        return redirect()->route('spare-parts.sourcing');
    }

    public function render()
    {
        return view('livewire.customer.spare-part-sourcing');
    }
}

