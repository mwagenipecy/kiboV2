<?php

namespace App\Livewire\Customer;

use App\Mail\AgizaImportRequestReceived;
use App\Models\AgizaImportRequest;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Services\CarListingFromLinkParser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer', ['vehicleType' => 'agiza-import'])]
class AgizaImport extends Component
{
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

    public ?string $listingParseHint = null;

    /** @var 'info'|'warning'|null */
    public ?string $listingParseHintTone = null;

    // Pricing
    public ?float $estimatedPrice = null;

    public string $priceCurrency = 'USD';

    // Additional info
    public ?string $specialRequirements = '';

    public ?string $customerNotes = '';

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

    public function updatedVehicleLink(?string $value): void
    {
        $this->applyListingLinkParse($value);
    }

    /**
     * Fetch listing page and auto-fill make/model/year when the URL is valid.
     * Does not clear existing make/model if the parser cannot infer them (user may fill manually).
     */
    public function applyListingLinkParse(?string $value): void
    {
        $this->listingParseHint = null;
        $this->listingParseHintTone = null;
        $trimmed = $value !== null ? trim($value) : '';
        if ($trimmed === '' || ! filter_var($trimmed, FILTER_VALIDATE_URL)) {
            return;
        }

        $result = app(CarListingFromLinkParser::class)->extract($trimmed);

        if (! empty($result['error'])) {
            $this->listingParseHint = $result['error'];
            $this->listingParseHintTone = 'warning';

            return;
        }

        if (($result['vehicle_make_id'] ?? null) !== null) {
            $this->vehicleMakeId = (int) $result['vehicle_make_id'];
            $this->loadModels();
            $this->vehicleModelId = ($result['vehicle_model_id'] ?? null) !== null
                ? (int) $result['vehicle_model_id']
                : null;
        }

        if (($result['year'] ?? null) !== null) {
            $this->vehicleYear = (int) $result['year'];
        }

        if (($result['vehicle_make_id'] ?? null) !== null && ($result['vehicle_model_id'] ?? null) !== null) {
            $this->listingParseHint = 'Filled from the listing where possible — please confirm or adjust.';
            $this->listingParseHintTone = 'info';
        } elseif (($result['vehicle_make_id'] ?? null) !== null) {
            $this->listingParseHint = 'Make detected from the listing. Pick the model if it is not selected.';
            $this->listingParseHintTone = 'info';
        } elseif (($result['title'] ?? null) !== null) {
            $this->listingParseHint = 'Could not detect make from this page. Select make and model below.';
            $this->listingParseHintTone = 'warning';
        }
    }

    public function refreshFromLink(): void
    {
        $this->applyListingLinkParse($this->vehicleLink);
    }

    public function submit()
    {
        if (! Auth::check()) {
            $this->errorMessage = 'Please login to submit a request.';
            $this->showErrorModal = true;

            return;
        }

        $rules = [
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => 'required|string|max:20',
            'vehicleLink' => 'required|url|max:500',
            'vehicleMakeId' => 'required|exists:vehicle_makes,id',
            'vehicleModelId' => 'required|exists:vehicle_models,id',
            'sourceCountry' => 'required|string|max:255',
        ];

        $this->validate($rules, [
            'vehicleMakeId.required' => 'Please select the vehicle make.',
            'vehicleMakeId.exists' => 'Please select a valid vehicle make.',
            'vehicleModelId.required' => 'Please select the vehicle model.',
            'vehicleModelId.exists' => 'Please select a valid vehicle model.',
            'vehicleLink.required' => 'Please provide the car listing link.',
            'vehicleLink.url' => 'Please provide a valid URL.',
            'sourceCountry.required' => 'Please select the country where the vehicle is located.',
        ]);

        try {
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
                'request_type' => 'with_link',
                'dealer_contact_info' => null,
                'estimated_price' => $this->estimatedPrice,
                'price_currency' => $this->priceCurrency,
                'special_requirements' => $this->specialRequirements,
                'customer_notes' => $this->customerNotes,
                'documents' => [],
                'vehicle_images' => [],
                'status' => 'pending',
            ]);

            $this->requestNumber = $request->request_number;

            try {
                Mail::to($this->customerEmail)->send(new AgizaImportRequestReceived($request));
            } catch (\Exception $e) {
                \Log::error('Failed to send confirmation email: '.$e->getMessage());
            }

            $this->successMessage = 'Your import request has been submitted successfully! Our team will review it and get back to you shortly.';
            $this->showSuccessModal = true;
            $this->resetForm();

        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while submitting your request. Please try again.';
            $this->showErrorModal = true;
            \Log::error('Agiza import submission error: '.$e->getMessage());
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
            'listingParseHint',
            'listingParseHintTone',
            'estimatedPrice',
            'specialRequirements',
            'customerNotes',
        ]);

        $this->priceCurrency = 'USD';
        $this->vehicleCondition = 'used';
        $this->vehicleModels = [];
    }

    public function render()
    {
        return view('livewire.customer.agiza-import');
    }
}
