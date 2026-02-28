<?php

namespace App\Livewire\Admin\TruckManagement;

use App\Enums\VehicleStatus;
use App\Helpers\ImageHelper;
use App\Models\Entity;
use App\Models\Truck;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class TruckForm extends Component
{
    use WithFileUploads;

    // Basic Information
    public $description;
    public $origin = 'local';
    public $registration_number;
    public $condition = 'used';
    
    // Make and Model
    public $vehicle_make_id;
    public $vehicle_model_id;
    public $variant;
    public $year;
    
    // Truck-Specific Specifications
    public $truck_type;
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
    
    // Truck-Specific Capacities
    public $cargo_capacity_kg;
    public $towing_capacity_kg;
    public $payload_capacity_kg;
    public $bed_length_m;
    public $bed_width_m;
    public $axle_configuration;
    
    // Pricing
    public $price;
    public $currency = 'TZS';
    public $original_price;
    public $negotiable = true;
    
    // Features
    public $features = [];
    public $safety_features = [];
    public $featureInput = '';
    public $safetyFeatureInput = '';
    
    // Images
    public $image_front;
    public $image_side;
    public $image_back;
    public $other_images = [];
    public $new_other_images = [];
    
    // Ownership
    public $entity_id;
    public $userIsAdmin;
    public $userEntityName;
    
    // Status
    public $status = 'pending';
    public $notes;
    
    // Error handling
    public $showErrorModal = false;
    public $errorMessage = '';
    public $errorTitle = 'Error';
    
    // Edit mode
    public $truckId;
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

    protected function rules()
    {
        return [
            'description' => 'nullable|string',
            'origin' => 'required|in:local,international',
            'registration_number' => 'nullable|string|max:255|unique:trucks,registration_number,' . $this->truckId,
            'condition' => 'required|in:new,used,certified_pre_owned',
            'vehicle_make_id' => 'required|exists:vehicle_makes,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'variant' => 'nullable|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 2),
            'truck_type' => 'nullable|string|max:255',
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
            'cargo_capacity_kg' => 'nullable|numeric|min:0',
            'towing_capacity_kg' => 'nullable|numeric|min:0',
            'payload_capacity_kg' => 'nullable|numeric|min:0',
            'bed_length_m' => 'nullable|numeric|min:0',
            'bed_width_m' => 'nullable|numeric|min:0',
            'axle_configuration' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'original_price' => 'nullable|numeric|min:0',
            'negotiable' => 'boolean',
            'entity_id' => $this->userIsAdmin ? 'nullable|exists:entities,id' : 'required|exists:entities,id',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'image_front' => 'nullable|image|max:5120',
            'image_side' => 'nullable|image|max:5120',
            'image_back' => 'nullable|image|max:5120',
            'other_images.*' => 'nullable|image|max:5120',
            'new_other_images.*' => 'nullable|image|max:5120',
        ];
    }

    public function mount($truckId = null)
    {
        $this->loadMakes();
        $this->loadDealers();
        
        $user = Auth::user();
        $this->userIsAdmin = $user->isAdmin();
        
        if (!$this->userIsAdmin && $user->entity) {
            $this->entity_id = $user->entity_id;
            $this->userEntityName = $user->entity->name;
        }
        
        if ($truckId) {
            $this->editMode = true;
            $this->truckId = $truckId;
            $this->loadTruck();
        }
        
        $this->year = date('Y');
    }

    public function loadTruck()
    {
        $truck = Truck::findOrFail($this->truckId);
        
        $this->description = $truck->description;
        $this->origin = $truck->origin;
        $this->registration_number = $truck->registration_number;
        $this->condition = $truck->condition;
        $this->vehicle_make_id = $truck->vehicle_make_id;
        $this->vehicle_model_id = $truck->vehicle_model_id;
        $this->variant = $truck->variant;
        $this->year = $truck->year;
        $this->truck_type = $truck->truck_type;
        $this->body_type = $truck->body_type;
        $this->fuel_type = strtolower($truck->fuel_type ?? '');
        $this->transmission = strtolower($truck->transmission ?? '');
        $this->engine_capacity = $truck->engine_capacity;
        $this->engine_cc = $truck->engine_cc;
        $this->drive_type = $truck->drive_type;
        $this->color_exterior = $truck->color_exterior;
        $this->color_interior = $truck->color_interior;
        $this->doors = $truck->doors;
        $this->seats = $truck->seats;
        $this->mileage = $truck->mileage;
        $this->vin = $truck->vin;
        $this->cargo_capacity_kg = $truck->cargo_capacity_kg;
        $this->towing_capacity_kg = $truck->towing_capacity_kg;
        $this->payload_capacity_kg = $truck->payload_capacity_kg;
        $this->bed_length_m = $truck->bed_length_m;
        $this->bed_width_m = $truck->bed_width_m;
        $this->axle_configuration = $truck->axle_configuration;
        $this->price = $truck->price;
        $this->currency = $truck->currency;
        $this->original_price = $truck->original_price;
        $this->negotiable = $truck->negotiable;
        $this->features = $truck->features ?? [];
        $this->safety_features = $truck->safety_features ?? [];
        $this->entity_id = $truck->entity_id;
        $this->status = $truck->status->value;
        $this->notes = $truck->notes;
        
        $this->loadModels();
        
        // Load existing images for preview
        if ($truck->image_front) {
            $this->tempImageFront = asset('storage/' . $truck->image_front);
        }
        if ($truck->image_side) {
            $this->tempImageSide = asset('storage/' . $truck->image_side);
        }
        if ($truck->image_back) {
            $this->tempImageBack = asset('storage/' . $truck->image_back);
        }
        if ($truck->other_images) {
            foreach ($truck->other_images as $image) {
                $this->tempOtherImages[] = asset('storage/' . $image);
            }
        }
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
        $this->vehicle_model_id = null;
    }

    public function addFeature()
    {
        if (!empty($this->featureInput)) {
            $this->features[] = $this->featureInput;
            $this->featureInput = '';
        }
    }

    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function addSafetyFeature()
    {
        if (!empty($this->safetyFeatureInput)) {
            $this->safety_features[] = $this->safetyFeatureInput;
            $this->safetyFeatureInput = '';
        }
    }

    public function removeSafetyFeature($index)
    {
        unset($this->safety_features[$index]);
        $this->safety_features = array_values($this->safety_features);
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
        $this->errorTitle = 'Error';
    }

    public function showError($title, $message)
    {
        $this->errorTitle = $title;
        $this->errorMessage = $message;
        $this->showErrorModal = true;
        $this->dispatch('error-modal-shown');
    }

    public function updatedImageFront()
    {
        if ($this->image_front) {
            $this->tempImageFront = $this->image_front->temporaryUrl();
        }
    }

    public function updatedImageSide()
    {
        if ($this->image_side) {
            $this->tempImageSide = $this->image_side->temporaryUrl();
        }
    }

    public function updatedImageBack()
    {
        if ($this->image_back) {
            $this->tempImageBack = $this->image_back->temporaryUrl();
        }
    }

    public function updatedNewOtherImages()
    {
        if (!empty($this->new_other_images)) {
            // Merge new images with existing ones
            $this->other_images = array_merge($this->other_images, $this->new_other_images);
            // Clear the new images input
            $this->new_other_images = [];
        }
    }

    public function removeOtherImage($index)
    {
        unset($this->other_images[$index]);
        $this->other_images = array_values($this->other_images);
    }

    public function save()
    {
        $user = Auth::user();
        
        // For non-admin users, ensure entity_id is set from user
        if (!$user->isAdmin()) {
            if (!$user->entity_id) {
                $this->showError('Entity Required', 'You cannot register a truck without an associated entity. Please contact an administrator.');
                return;
            }
            // Force entity_id to user's entity_id (prevent tampering)
            $this->entity_id = $user->entity_id;
        }

        $entity = $this->entity_id ? Entity::with('pricingPlan')->find($this->entity_id) : null;
        if ($entity && !$this->editMode) {
            if (!$entity->canAddTruck()) {
                $max = $entity->max_allowed_trucks;
                $current = $entity->trucksCountExcludingSold();
                $this->showError('Listing limit reached', "Your package allows up to {$max} truck listing(s) (excluding sold). You currently have {$current}. Please upgrade your plan from the pricing page to add more.");
                return;
            }
        }
        
        try {
        $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Show modal with validation errors for better visibility
            $errors = $e->validator->errors()->all();
            $errorCount = count($errors);
            if ($errorCount > 5) {
                $errorSummary = implode("\n• ", array_slice($errors, 0, 5)) . "\n• and " . ($errorCount - 5) . " more error(s)";
            } else {
                $errorSummary = implode("\n• ", $errors);
            }
            $this->showError('Validation Error', "Please fix the following errors:\n\n• " . $errorSummary);
            // Manually add errors to Livewire's error bag so they show inline too
            foreach ($e->validator->errors()->messages() as $key => $messages) {
                foreach ($messages as $message) {
                    $this->addError($key, $message);
                }
            }
            return;
        }

        try {
            // Auto-generate title from make, model, and year
            $make = VehicleMake::find($this->vehicle_make_id);
            $model = VehicleModel::find($this->vehicle_model_id);
            $title = ($make ? $make->name : '') . ' ' . ($model ? $model->name : '') . ' ' . $this->year;
            $title = trim($title);
            if (empty($title)) {
                $title = 'Truck ' . date('Y');
            }

            $data = [
                'title' => $title,
                'description' => $this->description,
                'origin' => $this->origin,
                'registration_number' => $this->registration_number ?: null,
                'condition' => strtolower($this->condition),
                'vehicle_make_id' => $this->vehicle_make_id,
                'vehicle_model_id' => $this->vehicle_model_id,
                'variant' => $this->variant ?: null,
                'year' => $this->year,
                'truck_type' => $this->truck_type ?: null,
                'body_type' => $this->body_type ? ucfirst(str_replace('_', ' ', $this->body_type)) : null,
                'fuel_type' => $this->fuel_type ? strtolower($this->fuel_type) : null,
                'transmission' => $this->transmission ? strtolower($this->transmission) : null,
                'engine_capacity' => $this->engine_capacity ?: null,
                'engine_cc' => $this->engine_cc ?: null,
                'drive_type' => $this->drive_type ?: null,
                'color_exterior' => $this->color_exterior ?: null,
                'color_interior' => $this->color_interior ?: null,
                'doors' => $this->doors ?: null,
                'seats' => $this->seats ?: null,
                'mileage' => $this->mileage ?: null,
                'vin' => $this->vin ?: null,
                'cargo_capacity_kg' => $this->cargo_capacity_kg ?: null,
                'towing_capacity_kg' => $this->towing_capacity_kg ?: null,
                'payload_capacity_kg' => $this->payload_capacity_kg ?: null,
                'bed_length_m' => $this->bed_length_m ?: null,
                'bed_width_m' => $this->bed_width_m ?: null,
                'axle_configuration' => $this->axle_configuration ?: null,
                'price' => $this->price,
                'currency' => $this->currency,
                'original_price' => $this->original_price ?: null,
                'negotiable' => $this->negotiable ?? true,
                'features' => $this->features,
                'safety_features' => $this->safety_features,
                'entity_id' => $this->userIsAdmin ? ($this->entity_id ?: null) : $user->entity_id,
                'status' => $this->status,
                'notes' => $this->notes ?: null,
            ];

            // Handle image uploads with optimization
            if ($this->image_front) {
                $oldImage = $this->editMode ? Truck::find($this->truckId)->image_front : null;
                $data['image_front'] = ImageHelper::optimizeAndResize($this->image_front, 'trucks', 1200);
                if ($oldImage && \Storage::disk('public')->exists($oldImage)) {
                    \Storage::disk('public')->delete($oldImage);
                }
            }
            
            if ($this->image_side) {
                $oldImage = $this->editMode ? Truck::find($this->truckId)->image_side : null;
                $data['image_side'] = ImageHelper::optimizeAndResize($this->image_side, 'trucks', 1200);
                if ($oldImage && \Storage::disk('public')->exists($oldImage)) {
                    \Storage::disk('public')->delete($oldImage);
                }
            }
            
            if ($this->image_back) {
                $oldImage = $this->editMode ? Truck::find($this->truckId)->image_back : null;
                $data['image_back'] = ImageHelper::optimizeAndResize($this->image_back, 'trucks', 1200);
                if ($oldImage && \Storage::disk('public')->exists($oldImage)) {
                    \Storage::disk('public')->delete($oldImage);
                }
            }
            
            if (!empty($this->other_images)) {
                $otherImagePaths = [];
                foreach ($this->other_images as $image) {
                    if ($image) {
                        $otherImagePaths[] = ImageHelper::optimizeAndResize($image, 'trucks', 1200);
                    }
                }
                if ($this->editMode && !empty($otherImagePaths)) {
                    $existingImages = Truck::find($this->truckId)->other_images ?? [];
                    $data['other_images'] = array_merge($existingImages, $otherImagePaths);
                } else {
                    $data['other_images'] = $otherImagePaths;
                }
            }

            if ($this->editMode) {
                $truck = Truck::findOrFail($this->truckId);
                $truck->update($data);
                
                session()->flash('success', 'Truck updated successfully!');
            } else {
                $data['registered_by'] = Auth::id();
                Truck::create($data);
                
                session()->flash('success', 'Truck registered successfully!');
            }

            return redirect()->route('admin.trucks.index');
            
        } catch (\Exception $e) {
            \Log::error('Truck save error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->showError('Save Error', 'An error occurred while saving the truck: ' . $e->getMessage() . '. Please check all fields and try again.');
        }
    }

    public function render()
    {
        return view('livewire.admin.truck-management.truck-form');
    }
}
