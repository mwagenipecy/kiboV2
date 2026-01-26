<?php

namespace App\Livewire\Admin\Leasing;

use App\Helpers\ImageHelper;
use App\Models\Entity;
use App\Models\Vehicle;
use App\Models\VehicleLease;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeaseForm extends Component
{
    use WithFileUploads;

    public $leaseId = null;
    public $lease = null;
    
    // Vehicle Information
    public $vehicle_title = '';
    public $vehicle_year = '';
    public $vehicle_make_id = '';
    public $vehicle_model_id = '';
    public $vehicle_make = '';
    public $vehicle_model = '';
    public $vehicle_variant = '';
    public $body_type = '';
    public $fuel_type = 'petrol';
    public $transmission = 'automatic';
    public $engine_capacity = '';
    public $mileage = 0;
    public $condition = 'new';
    public $color_exterior = '';
    public $seats = 5;
    public $vehicle_description = '';
    public $features = [];
    public $new_feature = '';
    
    // Images
    public $image_front;
    public $image_side;
    public $image_back;
    public $other_images = [];
    
    // Temporary images for preview
    public $tempImageFront;
    public $tempImageSide;
    public $tempImageBack;
    public $tempOtherImages = [];
    
    // Basic Info
    public $entity_id = '';
    public $lease_title = '';
    public $lease_description = '';
    
    // Lease Terms
    public $monthly_payment = '';
    public $currency = 'TZS';
    public $lease_term_months = 36;
    public $down_payment = 0;
    public $security_deposit = 0;
    public $mileage_limit_per_year = 15000;
    public $excess_mileage_charge = 0.25;
    
    // Additional Costs
    public $acquisition_fee = 0;
    public $disposition_fee = 0;
    public $maintenance_included = false;
    public $insurance_included = false;
    
    // Eligibility
    public $min_credit_score = null;
    public $min_monthly_income = null;
    public $min_age = 21;
    public $additional_requirements = '';
    
    // Purchase Options
    public $purchase_option_available = true;
    public $residual_value = '';
    public $early_termination_fee = '';
    
    // Status
    public $status = 'active';
    public $is_featured = false;
    public $priority = 0;
    public $available_from = '';
    public $available_until = '';
    
    // Notes
    public $notes = '';
    
    // Services (array)
    public $included_services = [];
    public $new_service = '';
    
    // Error handling
    public $showErrorModal = false;
    public $errorMessage = '';
    public $errorTitle = 'Error';

    public function mount($id = null)
    {
        $user = auth()->user();
        $userRole = $user->role ?? null;
        
        if ($id) {
            $this->leaseId = $id;
            $this->lease = VehicleLease::findOrFail($id);
            $this->loadLeaseData();
        } else {
            // Set default entity for non-admin users
            if ($userRole !== 'admin') {
                if ($user->entity_id) {
                    $this->entity_id = $user->entity_id;
                } else {
                    $this->entity_id = null;
                }
            }
        }
    }

    public function updatedVehicleMakeId()
    {
        // Reset model when make changes
        $this->vehicle_model_id = '';
        $this->vehicle_model = '';
    }

    protected function loadLeaseData()
    {
        // Vehicle Information
        $this->vehicle_title = $this->lease->vehicle_title;
        $this->vehicle_year = $this->lease->vehicle_year;
        $this->vehicle_make = $this->lease->vehicle_make;
        $this->vehicle_model = $this->lease->vehicle_model;
        
        // Find make and model IDs if they exist (case-insensitive)
        if ($this->vehicle_make) {
            $make = VehicleMake::whereRaw('LOWER(name) = ?', [strtolower(trim($this->vehicle_make))])
                ->where('status', 'active')
                ->first();
            if ($make) {
                $this->vehicle_make_id = $make->id;
                
                // Find model if exists (case-insensitive)
                if ($this->vehicle_model) {
                    $model = VehicleModel::whereRaw('LOWER(name) = ?', [strtolower(trim($this->vehicle_model))])
                        ->where('vehicle_make_id', $make->id)
                        ->where('status', 'active')
                        ->first();
                    if ($model) {
                        $this->vehicle_model_id = $model->id;
                    }
                }
            }
        }
        $this->vehicle_variant = $this->lease->vehicle_variant;
        $this->body_type = $this->lease->body_type;
        $this->fuel_type = $this->lease->fuel_type ?? 'petrol';
        $this->transmission = $this->lease->transmission ?? 'automatic';
        $this->engine_capacity = $this->lease->engine_capacity;
        $this->mileage = $this->lease->mileage ?? 0;
        $this->condition = $this->lease->condition ?? 'new';
        $this->color_exterior = $this->lease->color_exterior;
        $this->seats = $this->lease->seats ?? 5;
        $this->vehicle_description = $this->lease->vehicle_description;
        $this->features = $this->lease->features ?? [];
        
        // Lease Information
        $this->entity_id = $this->lease->entity_id;
        $this->lease_title = $this->lease->lease_title;
        $this->lease_description = $this->lease->lease_description;
        $this->monthly_payment = $this->lease->monthly_payment;
        $this->currency = $this->lease->currency ?? 'TZS';
        $this->lease_term_months = $this->lease->lease_term_months;
        $this->down_payment = $this->lease->down_payment;
        $this->security_deposit = $this->lease->security_deposit;
        $this->mileage_limit_per_year = $this->lease->mileage_limit_per_year;
        $this->excess_mileage_charge = $this->lease->excess_mileage_charge;
        $this->acquisition_fee = $this->lease->acquisition_fee;
        $this->disposition_fee = $this->lease->disposition_fee;
        $this->maintenance_included = $this->lease->maintenance_included;
        $this->insurance_included = $this->lease->insurance_included;
        $this->min_credit_score = $this->lease->min_credit_score;
        $this->min_monthly_income = $this->lease->min_monthly_income;
        $this->min_age = $this->lease->min_age;
        $this->additional_requirements = $this->lease->additional_requirements;
        $this->purchase_option_available = $this->lease->purchase_option_available;
        $this->residual_value = $this->lease->residual_value;
        $this->early_termination_fee = $this->lease->early_termination_fee;
        $this->status = $this->lease->status;
        $this->is_featured = $this->lease->is_featured;
        $this->priority = $this->lease->priority;
        $this->available_from = $this->lease->available_from?->format('Y-m-d');
        $this->available_until = $this->lease->available_until?->format('Y-m-d');
        $this->notes = $this->lease->notes;
        $this->included_services = $this->lease->included_services ?? [];
        
        // Load existing images for preview
        $this->tempImageFront = $this->lease->image_front;
        $this->tempImageSide = $this->lease->image_side;
        $this->tempImageBack = $this->lease->image_back;
        $this->tempOtherImages = $this->lease->other_images ?? [];
    }

    public function addService()
    {
        if ($this->new_service) {
            $this->included_services[] = $this->new_service;
            $this->new_service = '';
        }
    }

    public function removeService($index)
    {
        unset($this->included_services[$index]);
        $this->included_services = array_values($this->included_services);
    }

    public function addFeature()
    {
        if ($this->new_feature) {
            $this->features[] = $this->new_feature;
            $this->new_feature = '';
        }
    }

    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
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

    public function save()
    {
        $user = auth()->user();
        $userRole = $user->role ?? null;
        
        // For non-admin users, ensure entity_id is set from user
        if ($userRole !== 'admin') {
            if (!$user->entity_id) {
                $this->showError('Entity Required', 'You cannot create a lease without an associated entity. Please contact an administrator.');
                return;
            }
            // Force entity_id to user's entity_id (prevent tampering)
            $this->entity_id = $user->entity_id;
        }
        
        try {
            $this->validate([
            // Vehicle validation
            'vehicle_title' => 'required|string|max:255',
            'vehicle_year' => 'required|integer|min:1900|max:' . (date('Y') + 2),
            'vehicle_make_id' => 'required|exists:vehicle_makes,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
            'transmission' => 'required|in:automatic,manual',
            'condition' => 'required|in:new,used,certified_pre_owned',
            // Image validation
            'image_front' => 'nullable|image|max:5120',
            'image_side' => 'nullable|image|max:5120',
            'image_back' => 'nullable|image|max:5120',
            'other_images.*' => 'nullable|image|max:5120',
            // Lease validation
            'entity_id' => $userRole === 'admin' ? 'nullable|exists:entities,id' : 'required|exists:entities,id',
            'currency' => 'required|string|max:3',
            'lease_title' => 'required|string|max:255',
            'lease_description' => 'nullable|string',
            'monthly_payment' => 'required|numeric|min:0',
            'lease_term_months' => 'required|integer|min:1|max:120',
            'down_payment' => 'nullable|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'mileage_limit_per_year' => 'nullable|integer|min:0',
            'excess_mileage_charge' => 'nullable|numeric|min:0',
            'acquisition_fee' => 'nullable|numeric|min:0',
            'disposition_fee' => 'nullable|numeric|min:0',
            'min_credit_score' => 'nullable|integer|min:300|max:850',
            'min_monthly_income' => 'nullable|numeric|min:0',
            'min_age' => 'required|integer|min:18|max:100',
            'residual_value' => 'nullable|numeric|min:0',
            'early_termination_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,reserved',
            'priority' => 'nullable|integer|min:0',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date',
        ]);
        
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
        
        // Custom validation for date range
        if ($this->available_from && $this->available_until) {
            if (strtotime($this->available_until) <= strtotime($this->available_from)) {
                $this->showError('Invalid Date Range', 'The available until date must be after the available from date.');
                return;
            }
        }

        // Get make and model names from IDs
        $make = VehicleMake::find($this->vehicle_make_id);
        $model = VehicleModel::find($this->vehicle_model_id);
        
        if (!$make || !$model) {
            $this->showError('Invalid Selection', 'Invalid make or model selected. Please select a valid make and model.');
            return;
        }
        
        // Verify model belongs to make
        if ($model->vehicle_make_id != $make->id) {
            $this->showError('Invalid Selection', 'Selected model does not belong to the selected make. Please select a matching make and model.');
            return;
        }

        $data = [
            // Vehicle Information
            'vehicle_title' => $this->vehicle_title,
            'vehicle_year' => $this->vehicle_year,
            'vehicle_make' => $make->name,
            'vehicle_model' => $model->name,
            'vehicle_variant' => $this->vehicle_variant,
            'body_type' => $this->body_type,
            'fuel_type' => strtolower($this->fuel_type),
            'transmission' => strtolower($this->transmission),
            'engine_capacity' => $this->engine_capacity,
            'mileage' => $this->mileage ?: 0,
            'condition' => strtolower($this->condition),
            'color_exterior' => $this->color_exterior,
            'seats' => $this->seats ?: 5,
            'vehicle_description' => $this->vehicle_description,
            'features' => $this->features,
            // Lease Information
            'entity_id' => $this->entity_id ?: null,
            'lease_title' => $this->lease_title,
            'lease_description' => $this->lease_description,
            'monthly_payment' => $this->monthly_payment,
            'currency' => $this->currency,
            'lease_term_months' => $this->lease_term_months,
            'down_payment' => $this->down_payment ?: 0,
            'security_deposit' => $this->security_deposit ?: 0,
            'mileage_limit_per_year' => $this->mileage_limit_per_year,
            'excess_mileage_charge' => $this->excess_mileage_charge,
            'acquisition_fee' => $this->acquisition_fee ?: 0,
            'disposition_fee' => $this->disposition_fee ?: 0,
            'maintenance_included' => $this->maintenance_included,
            'insurance_included' => $this->insurance_included,
            'min_credit_score' => $this->min_credit_score ?: null,
            'min_monthly_income' => $this->min_monthly_income ?: null,
            'min_age' => $this->min_age,
            'additional_requirements' => $this->additional_requirements,
            'purchase_option_available' => $this->purchase_option_available,
            'residual_value' => $this->residual_value ?: null,
            'early_termination_fee' => $this->early_termination_fee ?: null,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'priority' => $this->priority ?: 0,
            'available_from' => !empty($this->available_from) ? $this->available_from : null,
            'available_until' => !empty($this->available_until) ? $this->available_until : null,
            'notes' => $this->notes,
            'included_services' => $this->included_services,
        ];

        // Handle image uploads with optimization
        if ($this->image_front && is_object($this->image_front)) {
            // Delete old image if exists
            if ($this->leaseId && $this->lease && $this->lease->image_front && Storage::disk('public')->exists($this->lease->image_front)) {
                Storage::disk('public')->delete($this->lease->image_front);
            }
            $data['image_front'] = ImageHelper::optimizeAndResize($this->image_front, 'vehicle-leases', 1200);
        } elseif ($this->leaseId && $this->lease) {
            // Keep existing image when editing and no new image uploaded
            $data['image_front'] = $this->lease->image_front;
        } else {
            // New lease without image - leave as null
            $data['image_front'] = null;
        }
        
        if ($this->image_side && is_object($this->image_side)) {
            // Delete old image if exists
            if ($this->leaseId && $this->lease && $this->lease->image_side && Storage::disk('public')->exists($this->lease->image_side)) {
                Storage::disk('public')->delete($this->lease->image_side);
            }
            $data['image_side'] = ImageHelper::optimizeAndResize($this->image_side, 'vehicle-leases', 1200);
        } elseif ($this->leaseId && $this->lease) {
            $data['image_side'] = $this->lease->image_side;
        } else {
            $data['image_side'] = null;
        }
        
        if ($this->image_back && is_object($this->image_back)) {
            // Delete old image if exists
            if ($this->leaseId && $this->lease && $this->lease->image_back && Storage::disk('public')->exists($this->lease->image_back)) {
                Storage::disk('public')->delete($this->lease->image_back);
            }
            $data['image_back'] = ImageHelper::optimizeAndResize($this->image_back, 'vehicle-leases', 1200);
        } elseif ($this->leaseId && $this->lease) {
            $data['image_back'] = $this->lease->image_back;
        } else {
            $data['image_back'] = null;
        }
        
        // Handle other images
        if (!empty($this->other_images) && is_array($this->other_images)) {
            $otherImagePaths = ($this->leaseId && $this->lease) ? ($this->lease->other_images ?? []) : [];
            foreach ($this->other_images as $image) {
                if (is_object($image)) {
                    $otherImagePaths[] = ImageHelper::optimizeAndResize($image, 'vehicle-leases', 1200);
                }
            }
            $data['other_images'] = $otherImagePaths;
        } elseif ($this->leaseId && $this->lease) {
            $data['other_images'] = $this->lease->other_images ?? [];
        } else {
            $data['other_images'] = [];
        }

        try {
            if ($this->leaseId && $this->lease) {
                $this->lease->update($data);
                session()->flash('success', 'Lease updated successfully.');
            } else {
                VehicleLease::create($data);
                session()->flash('success', 'Lease created successfully.');
            }

            return redirect()->route('admin.leasing.index');
        } catch (\Exception $e) {
            \Log::error('Lease save error: ' . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
            $this->showError('Save Error', 'An error occurred while saving the lease: ' . $e->getMessage() . '. Please check all fields and try again.');
        }
    }

    public function render()
    {
        $entities = Entity::where('type', 'dealer')->get();
        $makes = VehicleMake::where('status', 'active')->orderBy('name')->get();
        
        $models = collect();
        if ($this->vehicle_make_id) {
            $models = VehicleModel::where('vehicle_make_id', $this->vehicle_make_id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }

        return view('livewire.admin.leasing.lease-form', [
            'entities' => $entities,
            'makes' => $makes,
            'models' => $models,
        ]);
    }
}
