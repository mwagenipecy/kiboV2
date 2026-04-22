<?php

namespace App\Livewire\Admin;

use App\Models\Agent;
use Livewire\Component;

class AgentProfileSettings extends Component
{
    public $name = '';
    public $companyName = '';
    public $address = '';
    public $latitude = '';
    public $longitude = '';
    public $email = '';
    public $phoneNumber = '';
    public $agentType = '';

    public function mount(): void
    {
        $agent = $this->resolveLubricantAgent();

        $this->name = $agent->name ?? '';
        $this->companyName = $agent->company_name ?? '';
        $this->address = $agent->address ?? '';
        $this->latitude = $agent->latitude ?? '';
        $this->longitude = $agent->longitude ?? '';
        $this->email = $agent->email ?? '';
        $this->phoneNumber = $agent->phone_number ?? '';
        $this->agentType = $agent->agent_type ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'companyName' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $agent = $this->resolveLubricantAgent();

        $agent->update([
            'name' => $validated['name'],
            'company_name' => $validated['companyName'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'] !== '' ? (float) $validated['latitude'] : null,
            'longitude' => $validated['longitude'] !== '' ? (float) $validated['longitude'] : null,
        ]);

        // Keep auth user display name in sync with profile name.
        auth()->user()?->update(['name' => $validated['name']]);

        session()->flash('success', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.agent-profile-settings');
    }

    protected function resolveLubricantAgent(): Agent
    {
        $agent = Agent::where('user_id', auth()->id())->firstOrFail();

        if ($agent->agent_type !== 'lubricant_shop') {
            abort(403);
        }

        return $agent;
    }
}
