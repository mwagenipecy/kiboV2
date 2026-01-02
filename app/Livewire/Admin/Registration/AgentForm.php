<?php

namespace App\Livewire\Admin\Registration;

use App\Models\Agent;
use Livewire\Component;

class AgentForm extends Component
{
    public $agentId = null;
    public $name = '';
    public $email = '';
    public $phoneNumber = '';
    public $agentType = '';
    public $licenseNumber = '';
    public $address = '';
    public $companyName = '';
    public $status = 'active';

    // Agent types
    public $agentTypes = [
        'cars' => 'Cars',
        'trucks' => 'Trucks',
        'bikes' => 'Bikes',
        'vans' => 'Vans',
        'motorhomes' => 'Motorhomes',
        'caravans' => 'Caravans',
        'farm' => 'Farm Equipment',
        'plant' => 'Plant Machinery',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $agent = Agent::findOrFail($id);
            $this->agentId = $agent->id;
            $this->name = $agent->name;
            $this->email = $agent->email;
            $this->phoneNumber = $agent->phone_number;
            $this->agentType = $agent->agent_type;
            $this->licenseNumber = $agent->license_number ?? '';
            $this->address = $agent->address ?? '';
            $this->companyName = $agent->company_name ?? '';
            $this->status = $agent->status;
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
                $this->agentId 
                    ? 'unique:agents,email,' . $this->agentId 
                    : 'unique:agents,email'
            ],
            'phoneNumber' => 'required|string|max:20',
            'agentType' => 'required|string',
            'licenseNumber' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'companyName' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ], [
            'agentType.required' => 'Agent type is required.',
        ]);

        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phoneNumber,
                'agent_type' => $this->agentType,
                'license_number' => $this->licenseNumber,
                'address' => $this->address,
                'company_name' => $this->companyName,
                'status' => $this->status,
            ];

            if ($this->agentId) {
                $agent = Agent::findOrFail($this->agentId);
                $agent->update($data);
                session()->flash('success', 'Agent updated successfully!');
            } else {
                Agent::create($data);
                session()->flash('success', 'Agent created successfully!');
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
