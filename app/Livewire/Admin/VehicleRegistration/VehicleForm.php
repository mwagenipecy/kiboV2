<?php

namespace App\Livewire\Admin\VehicleRegistration;

use App\Enums\VehicleStatus;
use App\Models\Entity;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class VehicleForm extends Component
{
    use WithFileUploads;

    // Basic Information
    public $title;
    public $description;
    public $origin = 'local';
    public $registration_number;
    public $condition = 'used';
    
    // Make and Model
    public $vehicle_make_id;
    public $vehicle_model_id;
    public $variant;
    public $year;
    
    // Specifications
    public $body_type;
    public $fuel_type;
    public $transmission;
    public $engine_capacity;
    public $engine_cc;
    public $drive_type;
    public $color_exterior;
    public $color_interior;
    public $doors;
    public $seats;
    public $mileage;
    public $vin;
    
    // Pricing
    public $price;
    public $currency = 'TZS';
    public $original_price;
    public $negotiable = true;
    
    // Features
    public $features = [];
    public $safety_features = [];
    
    // Images
    public $image_front;
    public $image_side;
    public $image_back;
    public $other_images = [];
    
    // Ownership
    public $entity_id;
    
    // Status
    public $status = 'pending';
    public $notes;
    
    // Edit mode
    public $vehicleId;
    public $editMode = false;
    
    // Data for dropdowns
    public $makes = [];
    public $models = [];
    public $dealers = [];
    
    // Temporary images for preview
    public $tempImageFront;
    public $tempImageSide;
    public $tempImageBack;
    public $tempOtherImages = [];

    /**
     * Get the validation rules
     */
    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'origin' => 'required|in:local,international',
            'registration_number' => 'nullable|string|max:255',
            'condition' => 'required|in:new,used,certified_pre_owned',
            'vehicle_make_id' => 'required|exists:vehicle_makes,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'variant' => 'nullable|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 2),
            'body_type' => 'nullable|string|max:255',
            'fuel_type' => 'nullable|string|max:255',
            'transmission' => 'nullable|string|max:255',
            'engine_capacity' => 'nullable|string|max:255',
            'engine_cc' => 'nullable|integer',
            'drive_type' => 'nullable|string|max:255',
            'color_exterior' => 'nullable|string|max:255',
            'color_interior' => 'nullable|string|max:255',
            'doors' => 'nullable|integer|min:2|max:6',
            'seats' => 'nullable|integer|min:1|max:50',
            'mileage' => 'nullable|integer|min:0',
            'vin' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'original_price' => 'nullable|numeric|min:0',
            'negotiable' => 'boolean',
            'entity_id' => 'nullable|exists:entities,id',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'image_front' => 'nullable|image|max:5120',
            'image_side' => 'nullable|image|max:5120',
            'image_back' => 'nullable|image|max:5120',
            'other_images.*' => 'nullable|image|max:5120',
        ];
    }

    public function mount($vehicleId = null)
    {
        $this->loadMakes();
        $this->loadDealers();
        
        if ($vehicleId) {
            $this->editMode = true;
            $this->vehicleId = $vehicleId;
            $this->loadVehicle();
        } else {
            // Set default entity for non-admin users
            $user = Auth::user();
            if (!$user->isAdmin() && $user->entity_id) {
                $this->entity_id = $user->entity_id;
            }
        }
        
        $this->year = date('Y');
    }

    public function loadVehicle()
    {
        $vehicle = Vehicle::findOrFail($this->vehicleId);
        
        $this->title = $vehicle->title;
        $this->description = $vehicle->description;
        $this->origin = $vehicle->origin;
        $this->registration_number = $vehicle->registration_number;
        $this->condition = $vehicle->condition;
        $this->vehicle_make_id = $vehicle->vehicle_make_id;
        $this->vehicle_model_id = $vehicle->vehicle_model_id;
        $this->variant = $vehicle->variant;
        $this->year = $vehicle->year;
        $this->body_type = $vehicle->body_type;
        $this->fuel_type = $vehicle->fuel_type;
        $this->transmission = $vehicle->transmission;
        $this->engine_capacity = $vehicle->engine_capacity;
        $this->engine_cc = $vehicle->engine_cc;
        $this->drive_type = $vehicle->drive_type;
        $this->color_exterior = $vehicle->color_exterior;
        $this->color_interior = $vehicle->color_interior;
        $this->doors = $vehicle->doors;
        $this->seats = $vehicle->seats;
        $this->mileage = $vehicle->mileage;
        $this->vin = $vehicle->vin;
        $this->price = $vehicle->price;
        $this->currency = $vehicle->currency;
        $this->original_price = $vehicle->original_price;
        $this->negotiable = $vehicle->negotiable;
        $this->features = $vehicle->features ?? [];
        $this->safety_features = $vehicle->safety_features ?? [];
        $this->entity_id = $vehicle->entity_id;
        $this->status = $vehicle->status->value;
        $this->notes = $vehicle->notes;
        
        $this->loadModels();
    }

    public function loadMakes()
    {
        $this->makes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    public function loadModels()
    {
        if ($this->vehicle_make_id) {
            $this->models = VehicleModel::where('vehicle_make_id', $this->vehicle_make_id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        } else {
            $this->models = [];
            $this->vehicle_model_id = null;
        }
    }

    public function loadDealers()
    {
        $this->dealers = Entity::where('type', 'dealer')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();
    }

    public function updatedVehicleMakeId()
    {
        $this->loadModels();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'origin' => $this->origin,
            'registration_number' => $this->registration_number,
            'condition' => $this->condition,
            'vehicle_make_id' => $this->vehicle_make_id,
            'vehicle_model_id' => $this->vehicle_model_id,
            'variant' => $this->variant,
            'year' => $this->year,
            'body_type' => $this->body_type,
            'fuel_type' => $this->fuel_type,
            'transmission' => $this->transmission,
            'engine_capacity' => $this->engine_capacity,
            'engine_cc' => $this->engine_cc,
            'drive_type' => $this->drive_type,
            'color_exterior' => $this->color_exterior,
            'color_interior' => $this->color_interior,
            'doors' => $this->doors,
            'seats' => $this->seats,
            'mileage' => $this->mileage,
            'vin' => $this->vin,
            'price' => $this->price,
            'currency' => $this->currency,
            'original_price' => $this->original_price,
            'negotiable' => $this->negotiable,
            'features' => $this->features,
            'safety_features' => $this->safety_features,
            'entity_id' => $this->entity_id,
            'status' => $this->status,
            'notes' => $this->notes,
        ];

        // Handle image uploads
        if ($this->image_front) {
            $data['image_front'] = $this->image_front->store('vehicles', 'public');
        }
        
        if ($this->image_side) {
            $data['image_side'] = $this->image_side->store('vehicles', 'public');
        }
        
        if ($this->image_back) {
            $data['image_back'] = $this->image_back->store('vehicles', 'public');
        }
        
        if (!empty($this->other_images)) {
            $otherImagePaths = [];
            foreach ($this->other_images as $image) {
                if ($image) {
                    $otherImagePaths[] = $image->store('vehicles', 'public');
                }
            }
            $data['other_images'] = $otherImagePaths;
        }

        if ($this->editMode) {
            $vehicle = Vehicle::findOrFail($this->vehicleId);
            $vehicle->update($data);
            
            session()->flash('success', 'Vehicle updated successfully!');
        } else {
            $data['registered_by'] = Auth::id();
            Vehicle::create($data);
            
            session()->flash('success', 'Vehicle registered successfully!');
        }

        return redirect()->route('admin.vehicles.registration.index');
    }

    public function render()
    {
        return view('livewire.admin.vehicle-registration.vehicle-form');
    }
}
