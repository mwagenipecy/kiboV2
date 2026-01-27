<?php

namespace App\Livewire\Customer;

use App\Models\ImportFinancingRequest;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.customer', ['vehicleType' => 'import-financing'])]
class ImportFinancing extends Component
{
    use WithFileUploads;

    // Request type
    #[Validate('required|in:buy_car,tax_transport')]
    public string $requestType = 'buy_car';

    // Customer info
    #[Validate('required|string|max:255')]
    public string $customerName = '';
    
    #[Validate('required|email|max:255')]
    public string $customerEmail = '';
    
    #[Validate('nullable|string|max:20')]
    public ?string $customerPhone = '';

    // For 'buy_car' type
    #[Validate('nullable|url|max:500')]
    public ?string $carLink = '';
    
    public bool $isExtractingInfo = false;
    public ?array $extractedCarInfo = null;
    public ?string $extractionError = null;

    // Vehicle details (manual or extracted)
    #[Validate('nullable|string|max:100')]
    public ?string $vehicleMake = '';
    
    #[Validate('nullable|string|max:100')]
    public ?string $vehicleModel = '';
    
    #[Validate('nullable|integer|min:1900|max:2030')]
    public ?int $vehicleYear = null;
    
    #[Validate('nullable|numeric|min:0')]
    public ?float $vehiclePrice = null;
    
    #[Validate('required|in:USD,EUR,GBP,JPY,TZS')]
    public string $vehicleCurrency = 'USD';
    
    #[Validate('nullable|in:new,used')]
    public ?string $vehicleCondition = 'used';
    
    #[Validate('nullable|string|max:255')]
    public ?string $vehicleLocation = '';

    // For 'tax_transport' type
    #[Validate('nullable|numeric|min:0')]
    public ?float $taxAmount = null;
    
    #[Validate('nullable|numeric|min:0')]
    public ?float $transportCost = null;
    
    #[Validate('nullable|numeric|min:0')]
    public ?float $totalClearingCost = null;

    // Dropdown data - loaded from database
    public $vehicleMakes = [];
    public $vehicleModels = [];

    public ?string $selectedMake = '';

    // Financing details
    #[Validate('nullable|numeric|min:0')]
    public ?float $financingAmountRequested = null;
    
    #[Validate('nullable|integer|min:6|max:84')]
    public ?int $loanTermMonths = 36;
    
    #[Validate('nullable|numeric|min:0')]
    public ?float $downPayment = null;

    // Documents
    public $documents = [];

    // Notes
    #[Validate('nullable|string|max:2000')]
    public ?string $customerNotes = '';

    // UI state
    public int $currentStep = 1;
    public bool $showSuccess = false;
    public ?string $referenceNumber = null;
    public bool $showLoginModal = false;
    public bool $showSideModal = false;
    public string $sideModalType = 'login'; // 'login' or 'register'
    
    // Error modal
    public bool $showErrorModal = false;
    public array $errorMessages = [];

    // Login/Register form fields
    public string $loginEmail = '';
    public string $loginPassword = '';
    public string $registerName = '';
    public string $registerEmail = '';
    public string $registerPassword = '';
    public string $registerPasswordConfirmation = '';

    public function mount()
    {
        $this->loadVehicleData();

        if (auth()->check()) {
            $user = auth()->user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
            $this->customerPhone = $user->phone ?? '';
        } else {
            $this->showLoginModal = true;
        }
    }

    protected function loadVehicleData()
    {
        // Load all active vehicle makes with their models
        $this->vehicleMakes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($make) {
                return [$make->id => $make->name];
            })
            ->toArray();

        // Load all active vehicle models grouped by make_id
        $this->vehicleModels = VehicleModel::where('status', 'active')
            ->with('vehicleMake')
            ->get()
            ->groupBy('vehicle_make_id')
            ->map(function ($models) {
                return $models->pluck('name')->toArray();
            })
            ->toArray();
    }

    public function openSideModal($type)
    {
        $this->sideModalType = $type;
        $this->showSideModal = true;

        // Only hide the initial login modal if we're opening from there
        if ($this->showLoginModal) {
            $this->showLoginModal = false;
        }

        $this->resetFormFields();

        // Clear any session messages when switching forms
        session()->forget(['message', 'error']);
    }

    public function closeSideModal()
    {
        $this->showSideModal = false;
        $this->showLoginModal = true;
        $this->resetFormFields();
    }

    public function backToInitialModal()
    {
        $this->showSideModal = false;
        $this->showLoginModal = true;
        $this->resetFormFields();
    }

    protected function resetFormFields()
    {
        $this->loginEmail = '';
        $this->loginPassword = '';
        $this->registerName = '';
        $this->registerEmail = '';
        $this->registerPassword = '';
        $this->registerPasswordConfirmation = '';
    }

    public function login()
    {
        $this->validate([
            'loginEmail' => 'required|email',
            'loginPassword' => 'required|min:8',
        ]);

        if (Auth::attempt(['email' => $this->loginEmail, 'password' => $this->loginPassword])) {
            $this->showSideModal = false;
            $this->showLoginModal = false;

            // Pre-fill user data
            $user = auth()->user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
            $this->customerPhone = $user->phone ?? '';

            session()->flash('message', 'Successfully logged in!');
        } else {
            session()->flash('error', 'Invalid credentials. Please try again.');
        }
    }

    public function register()
    {
        $this->validate([
            'registerName' => 'required|string|max:255',
            'registerEmail' => 'required|email|unique:users,email',
            'registerPassword' => 'required|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $this->registerName,
            'email' => $this->registerEmail,
            'password' => bcrypt($this->registerPassword),
        ]);

        Auth::login($user);

        $this->showSideModal = false;
        $this->showLoginModal = false;

        // Pre-fill user data
        $this->customerName = $user->name;
        $this->customerEmail = $user->email;
        $this->customerPhone = $user->phone ?? '';

        session()->flash('message', 'Account created successfully!');
    }

    public function updatedRequestType()
    {
        // Reset fields when switching types
        if ($this->requestType === 'buy_car') {
            $this->taxAmount = null;
            $this->transportCost = null;
            $this->totalClearingCost = null;
        } else {
            $this->carLink = '';
            $this->extractedCarInfo = null;
        }
    }

    public function updatedVehicleMake($value)
    {
        $this->vehicleMake = $value;

        // Clear model selection if it's not available for the new make
        if ($this->vehicleModel && (!isset($this->vehicleModels[$value]) || !in_array($this->vehicleModel, $this->vehicleModels[$value]))) {
                $this->vehicleModel = null;
        }
    }

    public function updatedVehicleModel($value)
    {
        $this->vehicleModel = $value;
    }

    public function getVehicleModelOptionsProperty(): array
    {
        if (!empty($this->vehicleMake) && isset($this->vehicleModels[$this->vehicleMake])) {
            return $this->vehicleModels[$this->vehicleMake];
        }

        return [];
    }

    public function getVehicleMakeNameProperty(): string
    {
        if (!empty($this->vehicleMake) && isset($this->vehicleMakes[$this->vehicleMake])) {
            return $this->vehicleMakes[$this->vehicleMake];
        }

        return '';
    }

    public function extractCarInfo()
    {
        if (empty($this->carLink)) {
            $this->extractionError = 'Please enter a valid car link.';
            return;
        }

        $this->isExtractingInfo = true;
        $this->extractionError = null;
        $this->extractedCarInfo = null;

        try {
            // In a real implementation, you would call an API or scraping service
            // For now, we'll simulate extraction with a placeholder
            // This could be integrated with a service that scrapes car listing websites
            
            $this->extractedCarInfo = $this->simulateCarInfoExtraction($this->carLink);
            
            if ($this->extractedCarInfo) {
                // Auto-fill the form with extracted data
                $this->vehicleMake = $this->extractedCarInfo['make'] ?? '';
                $this->vehicleModel = $this->extractedCarInfo['model'] ?? '';
                $this->vehicleYear = $this->extractedCarInfo['year'] ?? null;
                $this->vehiclePrice = $this->extractedCarInfo['price'] ?? null;
                $this->vehicleCurrency = $this->extractedCarInfo['currency'] ?? 'USD';
                $this->vehicleCondition = $this->extractedCarInfo['condition'] ?? 'used';
                $this->vehicleLocation = $this->extractedCarInfo['location'] ?? '';
            }
        } catch (\Exception $e) {
            $this->extractionError = 'Unable to extract car information. Please enter details manually.';
        }

        $this->isExtractingInfo = false;
    }

    protected function simulateCarInfoExtraction(string $url): ?array
    {
        // This is a placeholder - in production, implement actual scraping or API call
        // Check if it's a known car listing site
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'] ?? '';

        // For demo purposes, return placeholder data
        // Real implementation would scrape the actual site
        return [
            'make' => '',
            'model' => '',
            'year' => null,
            'price' => null,
            'currency' => 'USD',
            'condition' => 'used',
            'location' => '',
            'source_url' => $url,
            'extracted_at' => now()->toISOString(),
        ];
    }

    public function nextStep()
    {
        try {
            $this->validateCurrentStep();
            
            if ($this->currentStep < 4) {
                $this->currentStep++;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorMessages = collect($e->errors())->flatten()->toArray();
            $this->showErrorModal = true;
        }
    }
    
    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessages = [];
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep(int $step)
    {
        if ($step >= 1 && $step <= 4) {
            // Validate all previous steps
            for ($i = 1; $i < $step; $i++) {
                $this->currentStep = $i;
                $this->validateCurrentStep();
            }
            $this->currentStep = $step;
        }
    }

    protected function validateCurrentStep()
    {
        switch ($this->currentStep) {
            case 1:
                $this->validate([
                    'requestType' => 'required|in:buy_car,tax_transport',
                ]);
                break;
            case 2:
                $this->validate([
                    'customerName' => 'required|string|max:255',
                    'customerEmail' => 'required|email|max:255',
                    'customerPhone' => 'nullable|string|max:20',
                ]);
                break;
            case 3:
                if ($this->requestType === 'buy_car') {
                    $this->validate([
                        'vehicleMake' => 'required',
                        'vehicleModel' => 'required|string|max:100',
                        'vehicleYear' => 'nullable|integer|min:1900|max:2030',
                        'vehiclePrice' => 'required|numeric|min:0',
                        'vehicleCurrency' => 'required|in:USD,EUR,GBP,JPY,TZS',
                    ], [
                        'vehicleMake.required' => 'Please select a vehicle make.',
                        'vehicleModel.required' => 'Please select a vehicle model.',
                        'vehiclePrice.required' => 'Please enter the vehicle price.',
                    ]);
                } else {
                    $this->validate([
                        'taxAmount' => 'required|numeric|min:0',
                        'transportCost' => 'required|numeric|min:0',
                    ], [
                        'taxAmount.required' => 'Please enter the tax amount.',
                        'transportCost.required' => 'Please enter the transport cost.',
                    ]);
                }
                break;
            case 4:
                $this->validate([
                    'financingAmountRequested' => 'required|numeric|min:1000',
                    'loanTermMonths' => 'required|integer|min:6|max:84',
                ], [
                    'financingAmountRequested.required' => 'Please enter the financing amount you need.',
                    'financingAmountRequested.min' => 'The minimum financing amount is 1,000.',
                    'loanTermMonths.required' => 'Please select a loan term.',
                ]);
                break;
        }
    }

    public function submit()
    {
        try {
            $this->validateCurrentStep();

            // Check if user is authenticated
            if (!auth()->check()) {
                $this->errorMessages = ['You must be logged in to submit an application.'];
                $this->showErrorModal = true;
                return;
            }

            // Store documents if uploaded
            $documentPaths = [];
            if (!empty($this->documents)) {
                foreach ($this->documents as $document) {
                    $path = $document->store('import-financing-documents', 'public');
                    $documentPaths[] = $path;
                }
            }

            // Prepare data for both request types
            $data = [
                'user_id' => auth()->id(),
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'request_type' => $this->requestType,
                'financing_amount_requested' => $this->financingAmountRequested,
                'loan_term_months' => $this->loanTermMonths,
                'down_payment' => $this->downPayment,
                'documents' => $documentPaths,
                'customer_notes' => $this->customerNotes,
                'status' => 'pending',
            ];

            // Add request-type specific fields
            if ($this->requestType === 'buy_car') {
                $data['car_link'] = $this->carLink;
                $data['extracted_car_info'] = $this->extractedCarInfo;
                $data['vehicle_make'] = $this->vehicleMakeName;
                $data['vehicle_model'] = $this->vehicleModel;
                $data['vehicle_year'] = $this->vehicleYear;
                $data['vehicle_price'] = $this->vehiclePrice;
                $data['vehicle_currency'] = $this->vehicleCurrency;
                $data['vehicle_condition'] = $this->vehicleCondition;
                $data['vehicle_location'] = $this->vehicleLocation;
            } else {
                // tax_transport
                $data['vehicle_make'] = $this->vehicleMakeName ?: null;
                $data['vehicle_model'] = $this->vehicleModel ?: null;
                $data['vehicle_year'] = $this->vehicleYear;
                $data['vehicle_condition'] = $this->vehicleCondition;
                $data['tax_amount'] = $this->taxAmount;
                $data['transport_cost'] = $this->transportCost;
                $data['total_clearing_cost'] = $this->totalClearingCost;
                $data['vehicle_currency'] = 'TZS';
            }

            // Create the request
            $request = ImportFinancingRequest::create($data);

            $this->referenceNumber = $request->reference_number;
            $this->showSuccess = true;

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorMessages = collect($e->errors())->flatten()->toArray();
            $this->showErrorModal = true;
        } catch (\Exception $e) {
            $this->errorMessages = ['An error occurred while submitting your application. Please try again.'];
            $this->showErrorModal = true;
            \Log::error('Import financing submission error: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'requestType', 'carLink', 'extractedCarInfo', 'extractionError',
            'vehicleMake', 'vehicleModel', 'vehicleYear', 'vehiclePrice',
            'vehicleCondition', 'vehicleLocation', 'taxAmount', 'transportCost',
            'totalClearingCost', 'financingAmountRequested', 'loanTermMonths',
            'downPayment', 'documents', 'customerNotes', 'currentStep', 'showSuccess',
            'referenceNumber'
        ]);
        
        $this->requestType = 'buy_car';
        $this->vehicleCurrency = 'USD';
        $this->loanTermMonths = 36;
        $this->currentStep = 1;
        
        if (auth()->check()) {
            $user = auth()->user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
            $this->customerPhone = $user->phone ?? '';
        }
    }

    public function render()
    {
        return view('livewire.customer.import-financing');
    }
}

