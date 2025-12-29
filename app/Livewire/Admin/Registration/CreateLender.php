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

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:entities,email',
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
        'user_email' => 'required|email|unique:users,email',
    ];

    protected $messages = [
        'name.required' => 'Lender name is required',
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'email.unique' => 'This email is already registered',
        'user_name.required' => 'Primary user name is required',
        'user_email.required' => 'Primary user email is required',
        'user_email.email' => 'Please enter a valid email address',
        'user_email.unique' => 'This email is already in use',
    ];

    public function mount()
    {
        $this->country = 'Kenya';
    }

    public function save()
    {
        $this->validate();

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
            session()->flash('error', 'Failed to register lender: ' . $e->getMessage());
        }
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
