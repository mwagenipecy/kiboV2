<?php

namespace App\Livewire\Admin\LeasingCars;

use App\Models\Entity;
use App\Models\LeasingCar;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeasingCarForm extends Component
{
    use WithFileUploads;

    // Basic Information
    public $title;
    public $description;
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
    
    // Leasing Pricing
    public $daily_rate;
    public $weekly_rate;
    public $monthly_rate;
    public $security_deposit;
    public $currency = 'TZS';
    public $negotiable = false;
    
    // Leasing Terms
    public $min_lease_days = 1;
    public $max_lease_days;
    public $mileage_limit_per_day;
    public $excess_mileage_charge;
    public $min_driver_age = 21;
    public $insurance_included = true;
    public $fuel_included = false;
    public $lease_terms;
    
    // Features
    public $features = [];
    public $safety_features = [];
    
    // Images
    public $image_front;
    public $image_side;
    public $image_back;
    public $image_interior;
    public $other_images = [];
    
    // Ownership
    public $entity_id;
    
    // Status
    public $status = 'pending';
    public $notes;
    
    // Edit mode
    public $carId;
    public $editMode = false;
    
    // Data for dropdowns
    public $makes = [];
    public $models = [];
    public $dealers = [];
    
    // Temporary images
    public $tempImageFront;
    public $tempImageSide;
    public $tempImageBack;
    public $tempImageInterior;
    public $tempOtherImages = [];

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
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
            'daily_rate' => 'required|numeric|min:0',
            'weekly_rate' => 'nullable|numeric|min:0',
            'monthly_rate' => 'nullable|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'negotiable' => 'boolean',
            'min_lease_days' => 'required|integer|min:1',
            'max_lease_days' => 'nullable|integer|min:1',
            'mileage_limit_per_day' => 'nullable|numeric|min:0',
            'excess_mileage_charge' => 'nullable|numeric|min:0',
            'min_driver_age' => 'required|integer|min:18|max:100',
            'insurance_included' => 'boolean',
            'fuel_included' => 'boolean',
            'lease_terms' => 'nullable|string',
            'entity_id' => 'nullable|exists:entities,id',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'image_front' => 'nullable|image|max:5120',
            'image_side' => 'nullable|image|max:5120',
            'image_back' => 'nullable|image|max:5120',
            'image_interior' => 'nullable|image|max:5120',
            'other_images.*' => 'nullable|image|max:5120',
        ];
    }

    public function mount($carId = null)
    {
        $this->loadMakes();
        $this->loadDealers();
        
        if ($carId) {
            $this->editMode = true;
            $this->carId = $carId;
            $this->loadCar();
        } else {
            // Set default entity for non-admin users
            $user = Auth::user();
            if ($user->entity_id) {
                $this->entity_id = $user->entity_id;
            }
        }
        
        $this->year = date('Y');
    }

    public function loadCar()
    {
        $car = LeasingCar::findOrFail($this->carId);
        
        $this->title = $car->title;
        $this->description = $car->description;
        $this->registration_number = $car->registration_number;
        $this->condition = $car->condition;
        $this->vehicle_make_id = $car->vehicle_make_id;
        $this->vehicle_model_id = $car->vehicle_model_id;
        
        // Load models for this make
        $this->updatedVehicleMakeId();
        
        $this->variant = $car->variant;
        $this->year = $car->year;
        $this->body_type = $car->body_type;
        $this->fuel_type = $car->fuel_type;
        $this->transmission = $car->transmission;
        $this->engine_capacity = $car->engine_capacity;
        $this->engine_cc = $car->engine_cc;
        $this->drive_type = $car->drive_type;
        $this->color_exterior = $car->color_exterior;
        $this->color_interior = $car->color_interior;
        $this->doors = $car->doors;
        $this->seats = $car->seats;
        $this->mileage = $car->mileage;
        $this->vin = $car->vin;
        $this->daily_rate = $car->daily_rate;
        $this->weekly_rate = $car->weekly_rate;
        $this->monthly_rate = $car->monthly_rate;
        $this->security_deposit = $car->security_deposit;
        $this->currency = $car->currency;
        $this->negotiable = $car->negotiable;
        $this->min_lease_days = $car->min_lease_days;
        $this->max_lease_days = $car->max_lease_days;
        $this->mileage_limit_per_day = $car->mileage_limit_per_day;
        $this->excess_mileage_charge = $car->excess_mileage_charge;
        $this->min_driver_age = $car->min_driver_age;
        $this->insurance_included = $car->insurance_included;
        $this->fuel_included = $car->fuel_included;
        $this->lease_terms = $car->lease_terms;
        $this->features = $car->features ?? [];
        $this->safety_features = $car->safety_features ?? [];
        $this->entity_id = $car->entity_id;
        $this->status = $car->status;
        $this->notes = $car->notes;
        
        // Store existing image paths for preview
        $this->tempImageFront = $car->image_front;
        $this->tempImageSide = $car->image_side;
        $this->tempImageBack = $car->image_back;
        $this->tempImageInterior = $car->image_interior;
        $this->tempOtherImages = $car->other_images ?? [];
    }

    public function loadMakes()
    {
        $this->makes = VehicleMake::orderBy('name')->get();
    }

    public function loadDealers()
    {
        $this->dealers = Entity::orderBy('name')->get();
    }

    public function updatedVehicleMakeId()
    {
        $this->models = VehicleModel::where('vehicle_make_id', $this->vehicle_make_id)
            ->orderBy('name')
            ->get();
        
        // Reset model selection if make changes
        if (!$this->editMode) {
            $this->vehicle_model_id = null;
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'description' => $this->description,
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
                'daily_rate' => $this->daily_rate,
                'weekly_rate' => $this->weekly_rate,
                'monthly_rate' => $this->monthly_rate,
                'security_deposit' => $this->security_deposit,
                'currency' => $this->currency,
                'negotiable' => $this->negotiable,
                'min_lease_days' => $this->min_lease_days,
                'max_lease_days' => $this->max_lease_days,
                'mileage_limit_per_day' => $this->mileage_limit_per_day,
                'excess_mileage_charge' => $this->excess_mileage_charge,
                'min_driver_age' => $this->min_driver_age,
                'insurance_included' => $this->insurance_included,
                'fuel_included' => $this->fuel_included,
                'lease_terms' => $this->lease_terms,
                'features' => $this->features,
                'safety_features' => $this->safety_features,
                'entity_id' => $this->entity_id,
                'status' => $this->status,
                'notes' => $this->notes,
            ];

            // Handle image uploads
            if ($this->image_front && is_object($this->image_front)) {
                $data['image_front'] = $this->image_front->store('leasing-cars', 'public');
            }
            if ($this->image_side && is_object($this->image_side)) {
                $data['image_side'] = $this->image_side->store('leasing-cars', 'public');
            }
            if ($this->image_back && is_object($this->image_back)) {
                $data['image_back'] = $this->image_back->store('leasing-cars', 'public');
            }
            if ($this->image_interior && is_object($this->image_interior)) {
                $data['image_interior'] = $this->image_interior->store('leasing-cars', 'public');
            }

            // Handle other images
            if ($this->other_images && count($this->other_images) > 0) {
                $otherImagePaths = [];
                foreach ($this->other_images as $image) {
                    if (is_object($image)) {
                        $otherImagePaths[] = $image->store('leasing-cars', 'public');
                    }
                }
                if (count($otherImagePaths) > 0) {
                    $data['other_images'] = $otherImagePaths;
                }
            }

            if ($this->editMode) {
                $car = LeasingCar::findOrFail($this->carId);
                $car->update($data);
                $message = 'Leasing car updated successfully!';
            } else {
                $data['registered_by'] = Auth::id();
                LeasingCar::create($data);
                $message = 'Leasing car registered successfully!';
            }

            session()->flash('success', $message);
            return redirect()->route('admin.leasing-cars.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save leasing car. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.admin.leasing-cars.leasing-car-form');
    }
}
