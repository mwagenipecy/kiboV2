<?php

namespace App\Livewire\Customer;

use App\Models\ImportFinancingRequest;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Services\ImageCompressionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.customer', ['vehicleType' => 'financing'])]
class ImportFinancing extends Component
{
    use WithFileUploads;

    #[Validate('required|in:tax_transport')]
    public string $requestType = 'tax_transport';

    #[Validate('required|string|max:255')]
    public string $customerName = '';

    #[Validate('required|email|max:255')]
    public string $customerEmail = '';

    #[Validate('nullable|string|max:20')]
    public ?string $customerPhone = '';

    #[Validate('nullable|string|max:100')]
    public ?string $vehicleMake = '';

    #[Validate('nullable|string|max:100')]
    public ?string $vehicleModel = '';

    #[Validate('nullable|integer|min:1900|max:2030')]
    public ?int $vehicleYear = null;

    #[Validate('nullable|in:new,used')]
    public ?string $vehicleCondition = 'used';

    public $vehicleMakes = [];

    public $vehicleModels = [];

    public ?string $selectedMake = '';

    #[Validate('nullable|numeric|min:0')]
    public ?float $financingAmountRequested = null;

    #[Validate('nullable|integer|min:6|max:84')]
    public ?int $loanTermMonths = 36;

    #[Validate('nullable|numeric|min:0')]
    public ?float $downPayment = null;

    public $documents = [];

    #[Validate('nullable|string|max:2000')]
    public ?string $customerNotes = '';

    public int $currentStep = 1;

    public bool $showSuccess = false;

    public ?string $referenceNumber = null;

    public bool $showLoginModal = false;

    public bool $showSideModal = false;

    public string $sideModalType = 'login';

    public bool $showErrorModal = false;

    public array $errorMessages = [];

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
        }
    }

    protected function loadVehicleData()
    {
        $this->vehicleMakes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($make) {
                return [$make->id => $make->name];
            })
            ->toArray();

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
        $this->showLoginModal = false;

        $this->resetFormFields();

        session()->forget(['message', 'error']);
    }

    public function closeSideModal()
    {
        $this->showSideModal = false;
        $this->resetFormFields();
    }

    public function closeLoginModal()
    {
        $this->showLoginModal = false;
    }

    public function backToInitialModal()
    {
        $this->showSideModal = false;
        $this->showLoginModal = true;
        $this->resetFormFields();
    }

    public function checkAuthAndShowModal()
    {
        if (! auth()->check()) {
            $this->showLoginModal = true;

            return false;
        }

        return true;
    }

    public function updatedCustomerName()
    {
        if (! auth()->check()) {
            $this->showLoginModal = true;
        }
    }

    public function updatedCustomerEmail()
    {
        if (! auth()->check()) {
            $this->showLoginModal = true;
        }
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

        $this->customerName = $user->name;
        $this->customerEmail = $user->email;
        $this->customerPhone = $user->phone ?? '';

        session()->flash('message', 'Account created successfully!');
    }

    public function handleRequestTypeClick($type)
    {
        if (! $this->checkAuthAndShowModal()) {
            return;
        }
        $this->requestType = $type;
    }

    public function updatedVehicleMake($value)
    {
        $this->vehicleMake = $value;

        if ($this->vehicleModel && (! isset($this->vehicleModels[$value]) || ! in_array($this->vehicleModel, $this->vehicleModels[$value]))) {
            $this->vehicleModel = null;
        }
    }

    public function updatedVehicleModel($value)
    {
        $this->vehicleModel = $value;
    }

    public function getVehicleModelOptionsProperty(): array
    {
        if (! empty($this->vehicleMake) && isset($this->vehicleModels[$this->vehicleMake])) {
            return $this->vehicleModels[$this->vehicleMake];
        }

        return [];
    }

    public function getVehicleMakeNameProperty(): string
    {
        if (! empty($this->vehicleMake) && isset($this->vehicleMakes[$this->vehicleMake])) {
            return $this->vehicleMakes[$this->vehicleMake];
        }

        return '';
    }

    public function nextStep()
    {
        if (! $this->checkAuthAndShowModal()) {
            return;
        }

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
                    'requestType' => 'required|in:tax_transport',
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
                // Vehicle details only; tax/transport/clearing costs are entered later by clearance & forwarding.
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
        if (! $this->checkAuthAndShowModal()) {
            return;
        }

        try {
            $this->validateCurrentStep();

            $compress = app(ImageCompressionService::class);

            $documentPaths = [];
            if (! empty($this->documents)) {
                foreach ($this->documents as $document) {
                    $documentPaths[] = $compress->storeCompressedIfImage($document, 'import-financing-documents', 1200);
                }
            }

            $data = [
                'user_id' => auth()->id(),
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'request_type' => 'tax_transport',
                'financing_amount_requested' => $this->financingAmountRequested,
                'loan_term_months' => $this->loanTermMonths,
                'down_payment' => $this->downPayment,
                'documents' => $documentPaths,
                'customer_notes' => $this->customerNotes,
                'status' => 'pending',
                'vehicle_make' => $this->vehicleMakeName ?: null,
                'vehicle_model' => $this->vehicleModel ?: null,
                'vehicle_year' => $this->vehicleYear,
                'vehicle_condition' => $this->vehicleCondition,
                'tax_amount' => null,
                'transport_cost' => null,
                'total_clearing_cost' => null,
                'vehicle_currency' => 'TZS',
            ];

            $request = ImportFinancingRequest::create($data);

            $this->referenceNumber = $request->reference_number;
            $this->showSuccess = true;

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorMessages = collect($e->errors())->flatten()->toArray();
            $this->showErrorModal = true;
        } catch (\Exception $e) {
            $this->errorMessages = ['An error occurred while submitting your application. Please try again.'];
            $this->showErrorModal = true;
            \Log::error('Import financing submission error: '.$e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'requestType', 'vehicleMake', 'vehicleModel', 'vehicleYear',
            'vehicleCondition', 'financingAmountRequested', 'loanTermMonths',
            'downPayment', 'documents', 'customerNotes', 'currentStep', 'showSuccess',
            'referenceNumber',
        ]);

        $this->requestType = 'tax_transport';
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
