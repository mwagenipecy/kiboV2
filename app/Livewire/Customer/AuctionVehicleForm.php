<?php

namespace App\Livewire\Customer;

use App\Models\AuctionVehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.customer', ['vehicleType' => 'cars'])]
class AuctionVehicleForm extends Component
{
    use WithFileUploads;

    // Basic Information
    public $description;
    public $condition = 'used';
    public $registration_number;
    public $vin;
    
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
    public $asking_price;
    public $minimum_price;
    public $currency = 'TZS';
    
    // Images
    public $image_front;
    public $other_images = [];
    public $newImages = []; // Temporary property for new uploads
    
    // Location
    public $location;
    public $city;
    public $region;
    
    // Contact
    public $contact_name;
    public $contact_phone;
    public $contact_email;
    
    // Data for dropdowns
    public $makes = [];
    public $models = [];
    
    // UI State
    public $currentStep = 1;
    public $totalSteps = 4;

    protected function rules()
    {
        return [
            'description' => 'nullable|string',
            'condition' => 'required|in:new,used',
            'vehicle_make_id' => 'required|exists:vehicle_makes,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'asking_price' => 'nullable|numeric|min:0',
            'minimum_price' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:3',
            'image_front' => 'required|image|max:5120',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email',
        ];
    }

    /**
     * Generate auto title from year, make, model, variant
     */
    public function getGeneratedTitleProperty()
    {
        $parts = [];
        
        if ($this->year) {
            $parts[] = $this->year;
        }
        
        if ($this->vehicle_make_id) {
            $make = $this->makes->firstWhere('id', $this->vehicle_make_id);
            if ($make) {
                $parts[] = $make->name;
            }
        }
        
        if ($this->vehicle_model_id) {
            $model = $this->models->firstWhere('id', $this->vehicle_model_id);
            if ($model) {
                $parts[] = $model->name;
            }
        }
        
        if ($this->variant) {
            $parts[] = $this->variant;
        }
        
        return implode(' ', $parts) ?: 'Vehicle Listing';
    }

    /**
     * Remove a specific image from other_images array
     */
    public function removeOtherImage($index)
    {
        if (isset($this->other_images[$index])) {
            $images = $this->other_images;
            unset($images[$index]);
            $this->other_images = array_values($images);
        }
    }

    /**
     * Handle when new images are uploaded - append them to existing images
     */
    public function updatedNewImages()
    {
        // Validate each new image
        $this->validate([
            'newImages.*' => 'image|max:5120',
        ]);

        // Append new images to existing other_images (max 10 total)
        foreach ($this->newImages as $image) {
            if (count($this->other_images) < 10) {
                $this->other_images[] = $image;
            }
        }

        // Clear the temporary upload field
        $this->newImages = [];
    }

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('cars.sell');
        }
        
        $user = Auth::user();
        $this->contact_name = $user->name;
        $this->contact_email = $user->email;
        $this->contact_phone = $user->phone ?? '';
        
        $this->loadMakes();
    }

    public function loadMakes()
    {
        $this->makes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Get available years for dropdown
     */
    public function getYearsProperty()
    {
        $years = [];
        for ($y = date('Y') + 1; $y >= 1900; $y--) {
            $years[] = $y;
        }
        return $years;
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
                'condition' => 'required|in:new,used',
                'vehicle_make_id' => 'required|exists:vehicle_makes,id',
                'vehicle_model_id' => 'required|exists:vehicle_models,id',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            ]);
        } elseif ($this->currentStep === 2) {
            // Optional fields, no strict validation
        } elseif ($this->currentStep === 3) {
            $this->validate([
                'image_front' => 'required|image|max:5120',
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
            'user_id' => Auth::id(),
            'title' => $this->generatedTitle,
            'description' => $this->description,
            'condition' => $this->condition,
            'registration_number' => $this->registration_number,
            'vin' => $this->vin,
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
            'asking_price' => $this->asking_price,
            'minimum_price' => $this->minimum_price,
            'currency' => $this->currency,
            'location' => $this->location,
            'city' => $this->city,
            'region' => $this->region,
            'contact_name' => $this->contact_name,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'status' => 'pending',
        ];

        // Handle image uploads
        if ($this->image_front) {
            $data['image_front'] = $this->image_front->store('auction-vehicles', 'public');
        }
        
        if (!empty($this->other_images)) {
            $otherImagePaths = [];
            foreach ($this->other_images as $image) {
                if ($image) {
                    $otherImagePaths[] = $image->store('auction-vehicles', 'public');
                }
            }
            $data['other_images'] = $otherImagePaths;
        }

        AuctionVehicle::create($data);
        
        session()->flash('success', 'Your vehicle has been submitted for auction! It will be reviewed and activated soon. Dealers will start making offers once approved.');
        
        return redirect()->route('my-auctions');
    }

    public function render()
    {
        return view('livewire.customer.auction-vehicle-form');
    }
}

