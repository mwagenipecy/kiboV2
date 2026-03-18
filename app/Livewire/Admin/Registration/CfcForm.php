<?php

namespace App\Livewire\Admin\Registration;

use App\Models\Cfc;
use Livewire\Component;

class CfcForm extends Component
{
    public $cfcId = null;
    public $name = '';
    public $email = '';
    public $phoneNumber = '';
    public $registrationNumber = '';
    public $tinNumber = '';
    public $address = '';
    public $contactPerson = '';
    public $status = 'active';

    public function updated($propertyName)
    {
        if ($propertyName === 'email') {
            $this->validateOnly($propertyName);
        }
    }

    public function mount($id = null)
    {
        if ($id) {
            $cfc = Cfc::findOrFail($id);
            $this->cfcId = $cfc->id;
            $this->name = $cfc->name;
            $this->email = $cfc->email;
            $this->phoneNumber = $cfc->phone_number;
            $this->registrationNumber = $cfc->registration_number;
            $this->tinNumber = $cfc->tin_number ?? '';
            $this->address = $cfc->address ?? '';
            $this->contactPerson = $cfc->contact_person ?? '';
            $this->status = $cfc->status;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($this->cfcId) {
                        $existingCfc = Cfc::where('email', $value)->where('id', '!=', $this->cfcId)->exists();
                        if ($existingCfc) {
                            $fail('This email is already registered in the CFCs table.');
                        }
                    } else {
                        if (Cfc::where('email', $value)->exists()) {
                            $fail('This email is already registered in the CFCs table.');
                        }
                    }
                    if (\App\Models\User::where('email', $value)->exists()) {
                        $fail('This email is already registered in the users table.');
                    }
                    if (\App\Models\Entity::where('email', $value)->exists()) {
                        $fail('This email is already registered in the entities table.');
                    }
                },
            ],
            'phoneNumber' => 'required|string|max:20',
            'registrationNumber' => [
                'required',
                'string',
                'max:50',
                $this->cfcId 
                    ? 'unique:cfcs,registration_number,' . $this->cfcId 
                    : 'unique:cfcs,registration_number'
            ],
            'tinNumber' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'contactPerson' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ], [
            'registrationNumber.required' => 'Registration number is required.',
            'registrationNumber.unique' => 'This registration number is already in use.',
        ]);

        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phoneNumber,
                'registration_number' => $this->registrationNumber,
                'tin_number' => $this->tinNumber,
                'address' => $this->address,
                'contact_person' => $this->contactPerson,
                'status' => $this->status,
            ];

            if ($this->cfcId) {
                $cfc = Cfc::findOrFail($this->cfcId);
                $cfc->update($data);
                session()->flash('success', 'CFC updated successfully!');
            } else {
                Cfc::create($data);
                session()->flash('success', 'CFC created successfully!');
            }

            return redirect()->route('admin.registration.cfcs');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.registration.cfcs');
    }

    public function render()
    {
        return view('livewire.admin.registration.cfc-form');
    }
}
