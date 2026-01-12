<?php

namespace App\Livewire\Admin\Registration;

use App\Models\Agent;
use App\Models\User;
use App\Models\VehicleMake;
use App\Jobs\SendRegistrationCredentials;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AgentForm extends Component
{
    public $agentId = null;
    public $name = '';
    public $email = '';
    public $phoneNumber = '';
    public $agentType = '';
    public $vehicleMakes = [];
    public $services = [];
    public $sparePartDetails = '';
    public $licenseNumber = '';
    public $address = '';
    public $latitude = '';
    public $longitude = '';
    public $companyName = '';
    public $status = 'active';

    // Agent types
    public $agentTypes = [
        'garage_owner' => 'Garage Owner',
        'lubricant_shop' => 'Lubricant Shop',
        'spare_part' => 'Spare Part',
    ];

    // Available services for garage owners
    public $availableServices = [
        'washing' => 'Washing',
        'oiling' => 'Oiling',
        'repair' => 'Repair',
        'maintenance' => 'Maintenance',
        'diagnostics' => 'Diagnostics',
        'tire_service' => 'Tire Service',
        'battery_service' => 'Battery Service',
        'air_conditioning' => 'Air Conditioning',
    ];

    // Vehicle makes for dropdown
    public $vehicleMakesList = [];

    public function mount($id = null)
    {
        // Load vehicle makes for dropdown
        $this->vehicleMakesList = VehicleMake::where('status', 'active')->orderBy('name')->get();

        if ($id) {
            $agent = Agent::findOrFail($id);
            $this->agentId = $agent->id;
            $this->name = $agent->name;
            $this->email = $agent->email;
            $this->phoneNumber = $agent->phone_number;
            $this->agentType = $agent->agent_type;
            // Convert vehicle makes IDs to strings for checkbox compatibility
            $vehicleMakes = $agent->vehicle_makes ?? [];
            $this->vehicleMakes = array_map('strval', $vehicleMakes);
            $this->services = $agent->services ?? [];
            $this->sparePartDetails = $agent->spare_part_details ?? '';
            $this->licenseNumber = $agent->license_number ?? '';
            $this->address = $agent->address ?? '';
            $this->latitude = $agent->latitude ?? '';
            $this->longitude = $agent->longitude ?? '';
            $this->companyName = $agent->company_name ?? '';
            $this->status = $agent->status;
        }
    }

    public function updatedAgentType()
    {
        // Reset conditional fields when agent type changes
        $this->vehicleMakes = [];
        $this->services = [];
        $this->sparePartDetails = '';
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                $this->agentId 
                    ? 'unique:agents,email,' . $this->agentId 
                    : 'unique:agents,email',
            ],
            'phoneNumber' => 'required|string|max:20',
            'agentType' => 'required|string|in:garage_owner,lubricant_shop,spare_part',
            'licenseNumber' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'companyName' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ];

        // Validation rules based on agent type
        if (in_array($this->agentType, ['garage_owner', 'spare_part'])) {
            $rules['vehicleMakes'] = 'required|array|min:1';
            $rules['vehicleMakes.*'] = 'exists:vehicle_makes,id';
        }

        if ($this->agentType === 'garage_owner') {
            $rules['services'] = 'required|array|min:1';
            $rules['services.*'] = 'in:' . implode(',', array_keys($this->availableServices));
        }

        if ($this->agentType === 'spare_part') {
            $rules['sparePartDetails'] = 'nullable|string';
        }

        $this->validate($rules, [
            'agentType.required' => 'Agent type is required.',
            'vehicleMakes.required' => 'Please select at least one vehicle make.',
            'services.required' => 'Please select at least one service.',
        ]);

        // Check if email exists in users table when creating new agent
        if (!$this->agentId && User::where('email', $this->email)->exists()) {
            $this->addError('email', 'This email is already registered as a user.');
            return;
        }

        // Check if email exists in users table when editing (except for the agent's own user)
        if ($this->agentId) {
            $existingAgent = Agent::find($this->agentId);
            $existingUserId = $existingAgent?->user_id;
            $userExists = User::where('email', $this->email)
                ->when($existingUserId, function($query) use ($existingUserId) {
                    return $query->where('id', '!=', $existingUserId);
                })
                ->exists();
            if ($userExists) {
                $this->addError('email', 'This email is already registered as a user.');
                return;
            }
        }

        try {
            $vehicleMakes = in_array($this->agentType, ['garage_owner', 'spare_part']) && !empty($this->vehicleMakes) 
                ? array_map('intval', $this->vehicleMakes) 
                : null;

            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phoneNumber,
                'agent_type' => $this->agentType,
                'vehicle_makes' => $vehicleMakes,
                'services' => $this->agentType === 'garage_owner' ? $this->services : null,
                'spare_part_details' => $this->agentType === 'spare_part' ? $this->sparePartDetails : null,
                'license_number' => $this->licenseNumber,
                'address' => $this->address,
                'latitude' => $this->latitude ? (float) $this->latitude : null,
                'longitude' => $this->longitude ? (float) $this->longitude : null,
                'company_name' => $this->companyName,
                'status' => $this->status,
            ];

            if ($this->agentId) {
                $agent = Agent::findOrFail($this->agentId);
                $agent->update($data);
                session()->flash('success', 'Agent updated successfully!');
            } else {
                // Generate random password
                $password = Str::random(12);

                // Create user account
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($password),
                    'role' => 'agent',
                ]);

                // Create agent with user_id and auto-approve (since created by admin)
                $data['user_id'] = $user->id;
                $data['approval_status'] = 'approved';
                $data['approved_at'] = now();
                $data['approved_by'] = auth()->id();
                
                $agent = Agent::create($data);

                // Dispatch queued job to send credentials email
                SendRegistrationCredentials::dispatch(
                    $this->email,
                    $this->name,
                    $password,
                    'agent'
                );

                session()->flash('success', 'Agent created successfully! User account has been created and credentials have been sent via email.');
            }

            return redirect()->route('admin.registration.agents');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.registration.agents');
    }

    public function render()
    {
        return view('livewire.admin.registration.agent-form');
    }
}
