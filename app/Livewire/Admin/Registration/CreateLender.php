<?php

namespace App\Livewire\Admin\Registration;

use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Jobs\SendEntityUserCredentials;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateLender extends Component
{
    // Entity fields
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $zip_code = '';
    public $country = 'Kenya';
    public $registration_number = '';
    public $tax_id = '';
    public $website = '';
    public $description = '';
    
    // Primary user fields
    public $user_name = '';
    public $user_email = '';
    
    // Error modal
    public $showErrorModal = false;
    public $errorTitle = 'Error';
    public $errorMessage = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (Entity::where('email', $value)->exists()) {
                        $fail('This email is already registered in the entities table.');
                    }
                    if (User::where('email', $value)->exists()) {
                        $fail('This email is already registered in the users table.');
                    }
                },
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'registration_number' => 'nullable|string|unique:entities,registration_number',
            'tax_id' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'user_name' => 'required|string|max:255',
            'user_email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (User::where('email', $value)->exists()) {
                        $fail('This email is already registered in the users table.');
                    }
                    if (Entity::where('email', $value)->exists()) {
                        $fail('This email is already registered in the entities table.');
                    }
                },
            ],
            'pricing_plan_id' => 'nullable|exists:advertising_pricing,id',
        ];
    }

    protected $messages = [
        'name.required' => 'Lender name is required',
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'user_name.required' => 'Primary user name is required',
        'user_email.required' => 'Primary user email is required',
        'user_email.email' => 'Please enter a valid email address',
    ];

    public function mount()
    {
        $this->country = 'Kenya';
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'email' || $propertyName === 'user_email') {
            $this->validateOnly($propertyName);
        }
    }

    public function save()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Collect all validation errors
            $errors = $e->validator->errors()->all();
            $this->showError('Validation Error', implode("\n", $errors));
            return;
        }

        try {
            // Create entity with pending status (no user created yet)
            $entity = Entity::create([
                'name' => $this->name,
                'type' => EntityType::LENDER,
                'status' => EntityStatus::PENDING, // Always pending initially
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zip_code,
                'country' => $this->country,
                'registration_number' => $this->registration_number,
                'tax_id' => $this->tax_id,
                'website' => $this->website,
                'description' => $this->description,
                'metadata' => [
                    'primary_user_name' => $this->user_name,
                    'primary_user_email' => $this->user_email,
                ],
            ]);

            session()->flash('message', 'Lender registered successfully and pending approval!');
            
            return redirect()->route('admin.registration.lenders');
        } catch (\Exception $e) {
            $this->showError('Registration Failed', 'Failed to register lender: ' . $e->getMessage());
        }
    }

    public function showError($title, $message)
    {
        $this->errorTitle = $title;
        $this->errorMessage = $message;
        $this->showErrorModal = true;
        $this->dispatch('error-modal-shown');
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorTitle = 'Error';
        $this->errorMessage = '';
    }

    public function cancel()
    {
        return redirect()->route('admin.registration.lenders');
    }

    public function render()
    {
        return view('livewire.admin.registration.create-lender');
    }
}
