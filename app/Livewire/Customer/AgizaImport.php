<?php

namespace App\Livewire\Customer;

use App\Mail\AgizaImportRequestReceived;
use App\Models\AgizaImportRequest;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.customer', ['vehicleType' => 'agiza-import'])]
class AgizaImport extends Component
{
    use WithFileUploads;

    // Request type
    public string $requestType = 'with_link';

    // Customer info
    public string $customerName = '';
    public string $customerEmail = '';
    public string $customerPhone = '';

    // Vehicle details
    public ?int $vehicleMakeId = null;
    public ?int $vehicleModelId = null;
    public ?int $vehicleYear = null;
    public string $vehicleCondition = 'used';
    public ?string $vehicleLink = '';
    public string $sourceCountry = '';

    // Dealer info (for already_contacted type)
    public ?string $dealerContactInfo = '';

    // Pricing
    public ?float $estimatedPrice = null;
    public string $priceCurrency = 'USD';

    // Additional info
    public ?string $specialRequirements = '';
    public ?string $customerNotes = '';

    // File uploads
    public array $documents = [];
    public array $vehicleImages = [];

    // UI state
    public bool $showSuccessModal = false;
    public bool $showErrorModal = false;
    public string $successMessage = '';
    public string $errorMessage = '';
    public string $requestNumber = '';

    // Dropdown data
    public $vehicleMakes = [];
    public $vehicleModels = [];
    public array $countries = [];

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
            $this->customerPhone = $user->phone ?? '';
        }

        $this->vehicleMakes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get();

        $this->countries = [
            'Japan',
            'United Kingdom',
            'United States',
            'Germany',
            'South Africa',
            'United Arab Emirates',
            'Kenya',
            'Uganda',
            'Other',
        ];
    }

    public function updatedVehicleMakeId()
    {
        $this->vehicleModelId = null;
        $this->loadModels();
    }

    public function loadModels()
    {
        if ($this->vehicleMakeId) {
            $this->vehicleModels = VehicleModel::where('vehicle_make_id', $this->vehicleMakeId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        } else {
            $this->vehicleModels = [];
        }
    }

    public function submit()
    {
        if (!Auth::check()) {
            $this->errorMessage = 'Please login to submit a request.';
            $this->showErrorModal = true;
            return;
        }

        $rules = [
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => 'required|string|max:20',
            'requestType' => 'required|in:with_link,already_contacted',
            'vehicleMakeId' => 'required|exists:vehicle_makes,id',
            'vehicleModelId' => 'required|exists:vehicle_models,id',
            'sourceCountry' => 'required|string|max:255',
        ];

        if ($this->requestType === 'with_link') {
            $rules['vehicleLink'] = 'required|url|max:500';
        } else {
            $rules['dealerContactInfo'] = 'required|string|max:1000';
        }

        $this->validate($rules, [
            'vehicleMakeId.required' => 'Please select the vehicle make.',
            'vehicleMakeId.exists' => 'Please select a valid vehicle make.',
            'vehicleModelId.required' => 'Please select the vehicle model.',
            'vehicleModelId.exists' => 'Please select a valid vehicle model.',
            'vehicleLink.required' => 'Please provide the car listing link.',
            'vehicleLink.url' => 'Please provide a valid URL.',
            'dealerContactInfo.required' => 'Please provide dealer contact information.',
            'sourceCountry.required' => 'Please select the country where the vehicle is located.',
        ]);

        try {
            // Store documents
            $documentPaths = [];
            if (!empty($this->documents)) {
                foreach ($this->documents as $document) {
                    $documentPaths[] = $document->store('agiza-import-documents', 'public');
                }
            }

            // Store vehicle images
            $imagePaths = [];
            if (!empty($this->vehicleImages)) {
                foreach ($this->vehicleImages as $image) {
                    $imagePaths[] = $image->store('agiza-import-vehicles', 'public');
                }
            }

            $make = VehicleMake::find($this->vehicleMakeId);
            $model = \App\Models\VehicleModel::find($this->vehicleModelId);

            $request = AgizaImportRequest::create([
                'request_number' => AgizaImportRequest::generateRequestNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'vehicle_make' => $make->name,
                'vehicle_model' => $model->name,
                'vehicle_year' => $this->vehicleYear,
                'vehicle_condition' => $this->vehicleCondition,
                'vehicle_link' => $this->vehicleLink,
                'source_country' => $this->sourceCountry,
                'request_type' => $this->requestType,
                'dealer_contact_info' => $this->dealerContactInfo,
                'estimated_price' => $this->estimatedPrice,
                'price_currency' => $this->priceCurrency,
                'special_requirements' => $this->specialRequirements,
                'customer_notes' => $this->customerNotes,
                'documents' => $documentPaths,
                'vehicle_images' => $imagePaths,
                'status' => 'pending',
            ]);

            $this->requestNumber = $request->request_number;
            
            try {
                Mail::to($this->customerEmail)->send(new AgizaImportRequestReceived($request));
            } catch (\Exception $e) {
                \Log::error('Failed to send confirmation email: ' . $e->getMessage());
            }

            $this->successMessage = 'Your import request has been submitted successfully! Our team will review it and get back to you shortly.';
            $this->showSuccessModal = true;
            $this->resetForm();

        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while submitting your request. Please try again.';
            $this->showErrorModal = true;
            \Log::error('Agiza import submission error: ' . $e->getMessage());
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->successMessage = '';
        $this->requestNumber = '';
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function resetForm()
    {
        $this->reset([
            'vehicleMakeId',
            'vehicleModelId',
            'vehicleYear',
            'vehicleCondition',
            'vehicleLink',
            'sourceCountry',
            'dealerContactInfo',
            'estimatedPrice',
            'specialRequirements',
            'customerNotes',
            'documents',
            'vehicleImages',
        ]);

        $this->requestType = 'with_link';
        $this->priceCurrency = 'USD';
        $this->vehicleCondition = 'used';
        $this->vehicleModels = [];
    }

    public function render()
    {
        return view('livewire.customer.agiza-import');
    }
}
