<?php

namespace App\Livewire\Customer;

use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class VehicleListingForm extends Component
{
    use WithFileUploads;

    // Basic Information
    public $title;
    public $description;
    public $condition = 'used';
    public $registration_number;
    
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
    public $color_exterior;
    public $doors;
    public $seats;
    public $mileage;
    
    // Pricing
    public $price;
    public $currency = 'GBP';
    public $negotiable = true;
    
    // Images
    public $image_front;
    public $other_images = [];
    
    // Data for dropdowns
    public $makes = [];
    public $models = [];
    
    // UI State
    public $currentStep = 1;
    public $totalSteps = 3;

    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'condition' => 'required|in:new,used',
            'vehicle_make_id' => 'required|exists:vehicle_makes,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'negotiable' => 'boolean',
            'image_front' => 'required|image|max:5120',
        ];

        // Step 2 validation
        if ($this->currentStep >= 2) {
            $rules = array_merge($rules, [
                'body_type' => 'nullable|string|max:255',
                'fuel_type' => 'nullable|string|max:255',
                'transmission' => 'nullable|string|max:255',
                'engine_capacity' => 'nullable|string|max:255',
                'color_exterior' => 'nullable|string|max:255',
                'doors' => 'nullable|integer|min:2|max:6',
                'seats' => 'nullable|integer|min:1|max:50',
                'mileage' => 'nullable|integer|min:0',
            ]);
        }

        return $rules;
    }

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('cars.sell');
        }
        
        $this->loadMakes();
    }

    public function loadMakes()
    {
        $this->makes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function updatedVehicleMakeId()
    {
        $this->vehicle_model_id = '';
        $this->loadModels();
    }

    public function loadModels()
    {
        if ($this->vehicle_make_id) {
            $this->models = VehicleModel::where('vehicle_make_id', $this->vehicle_make_id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name']);
        } else {
            $this->models = [];
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'title' => 'required|string|max:255',
                'condition' => 'required|in:new,used',
                'vehicle_make_id' => 'required|exists:vehicle_makes,id',
                'vehicle_model_id' => 'required|exists:vehicle_models,id',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'price' => 'required|numeric|min:0',
                'currency' => 'required|string|max:3',
            ]);
        }
        
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'origin' => 'local',
            'condition' => $this->condition,
            'registration_number' => $this->registration_number,
            'vehicle_make_id' => $this->vehicle_make_id,
            'vehicle_model_id' => $this->vehicle_model_id,
            'variant' => $this->variant,
            'year' => $this->year,
            'body_type' => $this->body_type,
            'fuel_type' => $this->fuel_type,
            'transmission' => $this->transmission,
            'engine_capacity' => $this->engine_capacity,
            'color_exterior' => $this->color_exterior,
            'doors' => $this->doors,
            'seats' => $this->seats,
            'mileage' => $this->mileage,
            'price' => $this->price,
            'currency' => $this->currency,
            'negotiable' => $this->negotiable,
            'registered_by' => Auth::id(),
            'status' => 'pending',
        ];

        // Handle image uploads
        if ($this->image_front) {
            $data['image_front'] = $this->image_front->store('vehicles', 'public');
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

        Vehicle::create($data);
        
        session()->flash('success', 'Your vehicle listing has been submitted successfully! It will be reviewed and published soon.');
        
        return redirect()->route('my-adverts');
    }

    public function render()
    {
        return view('livewire.customer.vehicle-listing-form');
    }
}
