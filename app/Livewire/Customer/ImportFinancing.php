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
            });

        // Load all active vehicle models grouped by make_id
        $this->vehicleModels = VehicleModel::where('status', 'active')
            ->with('vehicleMake')
            ->get()
            ->groupBy('vehicle_make_id')
            ->map(function ($models) {
                return $models->pluck('name')->toArray();
            });
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
        $this->validateCurrentStep();
        
        if ($this->currentStep < 4) {
            $this->currentStep++;
        }
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
                        'vehicleMake' => 'required|string|max:100',
                        'vehicleModel' => 'required|string|max:100',
                        'vehicleYear' => 'nullable|integer|min:1900|max:2030',
                        'vehiclePrice' => 'required|numeric|min:0',
                        'vehicleCurrency' => 'required|in:USD,EUR,GBP,JPY,TZS',
                    ]);
                } else {
                    $this->validate([
                        'vehicleMake' => 'nullable|string|max:100',
                        'vehicleModel' => 'nullable|string|max:100',
                        'taxAmount' => 'required|numeric|min:0',
                        'transportCost' => 'required|numeric|min:0',
                    ]);
                }
                break;
            case 4:
                $this->validate([
                    'financingAmountRequested' => 'required|numeric|min:1000',
                    'loanTermMonths' => 'required|integer|min:6|max:84',
                ]);
                break;
        }
    }

    public function submit()
    {
        $this->validateCurrentStep();

        // Store documents if uploaded
        $documentPaths = [];
        if (!empty($this->documents)) {
            foreach ($this->documents as $document) {
                $path = $document->store('import-financing-documents', 'public');
                $documentPaths[] = $path;
            }
        }

        // Create the request
        $request = ImportFinancingRequest::create([
            'user_id' => auth()->id(),
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'customer_phone' => $this->customerPhone,
            'request_type' => $this->requestType,
            'car_link' => $this->carLink,
            'extracted_car_info' => $this->extractedCarInfo,
            'vehicle_make' => $this->vehicleMake,
            'vehicle_model' => $this->vehicleModel,
            'vehicle_year' => $this->vehicleYear,
            'vehicle_price' => $this->vehiclePrice,
            'vehicle_currency' => $this->vehicleCurrency,
            'vehicle_condition' => $this->vehicleCondition,
            'vehicle_location' => $this->vehicleLocation,
            'tax_amount' => $this->taxAmount,
            'transport_cost' => $this->transportCost,
            'total_clearing_cost' => $this->totalClearingCost,
            'financing_amount_requested' => $this->financingAmountRequested,
            'loan_term_months' => $this->loanTermMonths,
            'down_payment' => $this->downPayment,
            'documents' => $documentPaths,
            'customer_notes' => $this->customerNotes,
            'status' => 'pending',
        ]);

        $this->referenceNumber = $request->reference_number;
        $this->showSuccess = true;

        // TODO: Send notification to admin
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

