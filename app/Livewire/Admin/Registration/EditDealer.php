<?php

namespace App\Livewire\Admin\Registration;

use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Models\Entity;
use App\Models\PricingPlan;
use Livewire\Component;

class EditDealer extends Component
{
    public $entityId;
    
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

    /** @var int|null Subscription bundle (pricing plan) – max active car listings */
    public $pricing_plan_id = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:entities,email,' . $this->entityId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'registration_number' => 'nullable|string|unique:entities,registration_number,' . $this->entityId,
            'tax_id' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email',
            'pricing_plan_id' => 'nullable|exists:advertising_pricing,id',
        ];
    }

    protected $messages = [
        'name.required' => 'Dealer name is required',
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'email.unique' => 'This email is already registered',
        'user_name.required' => 'Primary user name is required',
        'user_email.required' => 'Primary user email is required',
        'user_email.email' => 'Please enter a valid email address',
    ];

    public function mount($id)
    {
        $this->entityId = $id;
        $entity = Entity::findOrFail($id);
        
        // Load entity data
        $this->name = $entity->name;
        $this->email = $entity->email;
        $this->phone = $entity->phone;
        $this->address = $entity->address;
        $this->city = $entity->city;
        $this->state = $entity->state;
        $this->zip_code = $entity->zip_code;
        $this->country = $entity->country;
        $this->registration_number = $entity->registration_number;
        $this->tax_id = $entity->tax_id;
        $this->website = $entity->website;
        $this->description = $entity->description;
        
        // Load primary user info from metadata
        $this->user_name = $entity->metadata['primary_user_name'] ?? '';
        $this->user_email = $entity->metadata['primary_user_email'] ?? '';

        $this->pricing_plan_id = $entity->pricing_plan_id;
    }

    public function update()
    {
        $this->validate();

        try {
            $entity = Entity::findOrFail($this->entityId);
            
            $entity->update([
                'name' => $this->name,
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
                'pricing_plan_id' => $this->pricing_plan_id ?: null,
                'metadata' => [
                    'primary_user_name' => $this->user_name,
                    'primary_user_email' => $this->user_email,
                ],
            ]);

            session()->flash('message', 'Dealer updated successfully!');
            
            return redirect()->route('admin.registration.dealers');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update dealer: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.registration.dealers');
    }

    public function render()
    {
        $subscriptionBundles = PricingPlan::active()
            ->byCategory('cars')
            ->whereNotNull('max_listings')
            ->ordered()
            ->get();

        return view('livewire.admin.registration.edit-dealer', [
            'subscriptionBundles' => $subscriptionBundles,
        ]);
    }
}
