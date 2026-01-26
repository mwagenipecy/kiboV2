<?php

namespace App\Livewire\Admin\LendingCriteria;

use App\Models\Entity;
use App\Models\LendingCriteria;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CriteriaList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterEntity = '';
    public $filterStatus = 'all';

    protected $queryString = ['search', 'filterEntity', 'filterStatus'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($criteriaId)
    {
        $criteria = LendingCriteria::findOrFail($criteriaId);
        $criteria->update(['is_active' => !$criteria->is_active]);
        
        session()->flash('success', 'Lending criteria status updated successfully!');
    }

    public function deleteCriteria($criteriaId)
    {
        $criteria = LendingCriteria::findOrFail($criteriaId);
        $criteria->delete();
        
        session()->flash('success', 'Lending criteria deleted successfully!');
    }

    public function render()
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;
        
        $query = LendingCriteria::with('entity')->latest();

        // Apply entity filter for non-admin users
        if ($userRole !== 'admin') {
            if ($user->entity_id) {
                $query->where('entity_id', $user->entity_id);
            } else {
                // If no entity_id, show no criteria
                $query->whereRaw('1 = 0');
            }
        }

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('entity', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter by entity (only for admin users)
        if ($userRole === 'admin' && $this->filterEntity) {
            $query->where('entity_id', $this->filterEntity);
        }

        // Filter by status
        if ($this->filterStatus === 'active') {
            $query->where('is_active', true);
        } elseif ($this->filterStatus === 'inactive') {
            $query->where('is_active', false);
        }

        $criteria = $query->paginate(15);
        $entities = Entity::where('type', 'lender')->get();

        return view('livewire.admin.lending-criteria.criteria-list', [
            'criteria' => $criteria,
            'entities' => $entities,
            'userIsAdmin' => $userRole === 'admin',
        ]);
    }
}

