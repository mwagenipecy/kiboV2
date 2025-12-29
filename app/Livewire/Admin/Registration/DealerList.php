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
use Livewire\WithPagination;

class DealerList extends Component
{
    use WithPagination;
    
    // Filters
    public $search = '';
    public $statusFilter = '';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function approveEntity($entityId)
    {
        try {
            DB::beginTransaction();

            $entity = Entity::findOrFail($entityId);
            
            // Check if entity is pending
            if ($entity->status->value !== 'pending') {
                session()->flash('error', 'Only pending entities can be approved.');
                return;
            }

            // Get primary user info from metadata
            $primaryUserName = $entity->metadata['primary_user_name'] ?? null;
            $primaryUserEmail = $entity->metadata['primary_user_email'] ?? null;

            if (!$primaryUserName || !$primaryUserEmail) {
                session()->flash('error', 'Primary user information is missing.');
                return;
            }

            // Generate random password
            $password = Str::random(12);

            // Create primary user
            $user = User::create([
                'name' => $primaryUserName,
                'email' => $primaryUserEmail,
                'password' => Hash::make($password),
                'role' => 'dealer',
                'entity_id' => $entity->id,
            ]);

            // Update entity status to active
            $entity->update(['status' => EntityStatus::ACTIVE]);

            // Dispatch job to send credentials
            SendEntityUserCredentials::dispatch($user, $entity, $password);

            DB::commit();

            session()->flash('message', 'Dealer approved successfully! Credentials have been sent to their email.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to approve dealer: ' . $e->getMessage());
        }
    }

    public function updateStatus($entityId, $newStatus)
    {
        $entity = Entity::findOrFail($entityId);
        $entity->update(['status' => $newStatus]);
        
        session()->flash('message', 'Status updated successfully!');
    }

    public function deleteEntity($entityId)
    {
        try {
            $entity = Entity::findOrFail($entityId);
            $entity->users()->delete();
            $entity->delete();
            
            session()->flash('message', 'Dealer deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete dealer: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $entities = Entity::ofType(EntityType::DEALER)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('registration_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->withStatus(EntityStatus::from($this->statusFilter));
            })
            ->withCount('users')
            ->latest()
            ->paginate(10);

        $statusCounts = [
            'total' => Entity::ofType(EntityType::DEALER)->count(),
            'pending' => Entity::ofType(EntityType::DEALER)->withStatus(EntityStatus::PENDING)->count(),
            'active' => Entity::ofType(EntityType::DEALER)->withStatus(EntityStatus::ACTIVE)->count(),
            'suspended' => Entity::ofType(EntityType::DEALER)->withStatus(EntityStatus::SUSPENDED)->count(),
        ];

        return view('livewire.admin.registration.dealer-list', [
            'entities' => $entities,
            'statusCounts' => $statusCounts,
            'statuses' => EntityStatus::forSelect(),
        ]);
    }
}
