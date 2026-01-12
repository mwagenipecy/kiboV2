<?php

namespace App\Livewire\Admin\Registration;

use App\Models\Agent;
use App\Models\User;
use App\Jobs\SendRegistrationCredentials;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AgentList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterAgentType = '';
    public $agentToDelete = null;
    public $showDeleteModal = false;
    public $agentToApprove = null;
    public $showApproveModal = false;

    // Agent types
    public $agentTypes = [
        'garage_owner' => 'Garage Owner',
        'lubricant_shop' => 'Lubricant Shop',
        'spare_part' => 'Spare Part',
    ];

    protected $queryString = ['search', 'filterAgentType'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterAgentType()
    {
        $this->resetPage();
    }

    public function confirmApprove($agentId)
    {
        $this->agentToApprove = $agentId;
        $this->showApproveModal = true;
    }

    public function approve()
    {
        try {
            $agent = Agent::findOrFail($this->agentToApprove);
            
            if ($agent->approval_status === 'approved') {
                session()->flash('error', 'Agent is already approved!');
                $this->showApproveModal = false;
                $this->agentToApprove = null;
                return;
            }

            // Check if user already exists (created when agent was created)
            if ($agent->user_id) {
                // User already exists, just update approval status
                $agent->update([
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);

                session()->flash('success', 'Agent approved successfully!');
            } else {
                // User doesn't exist (old agent created before user creation on save)
                // Generate random password
                $password = Str::random(12);

                // Create user account
                $user = User::create([
                    'name' => $agent->name,
                    'email' => $agent->email,
                    'password' => Hash::make($password),
                    'role' => 'agent',
                ]);

                // Update agent with approval info
                $agent->update([
                    'approval_status' => 'approved',
                    'user_id' => $user->id,
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);

                // Dispatch queued job to send credentials email
                SendRegistrationCredentials::dispatch(
                    $agent->email,
                    $agent->name,
                    $password,
                    'agent'
                );

                session()->flash('success', 'Agent approved successfully! Credentials have been sent via email.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }

        $this->showApproveModal = false;
        $this->agentToApprove = null;
    }

    public function reject($agentId)
    {
        try {
            $agent = Agent::findOrFail($agentId);
            $agent->update([
                'approval_status' => 'rejected',
                'approved_by' => auth()->id(),
            ]);
            session()->flash('success', 'Agent registration rejected.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function confirmDelete($agentId)
    {
        $this->agentToDelete = $agentId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $agent = Agent::findOrFail($this->agentToDelete);
            
            // Delete associated user if exists
            if ($agent->user_id) {
                User::find($agent->user_id)?->delete();
            }
            
            $agent->delete();
            session()->flash('success', 'Agent deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete this agent: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->agentToDelete = null;
    }

    public function render()
    {
        $agents = Agent::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('company_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterAgentType, function ($query) {
                $query->where('agent_type', $this->filterAgentType);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.registration.agent-list', [
            'agents' => $agents,
        ]);
    }
}
