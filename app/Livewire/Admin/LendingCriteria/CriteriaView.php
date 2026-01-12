<?php

namespace App\Livewire\Admin\LendingCriteria;

use App\Models\LendingCriteria;
use Livewire\Component;

class CriteriaView extends Component
{
    public $criteria;

    public function mount($criteriaId)
    {
        $this->criteria = LendingCriteria::with('entity')->findOrFail($criteriaId);
    }

    public function render()
    {
        return view('livewire.admin.lending-criteria.criteria-view');
    }
}
