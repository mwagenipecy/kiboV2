<?php

namespace App\Livewire\Admin\VehicleSettings;

use App\Models\VehicleModel;
use Livewire\Component;
use Livewire\WithPagination;

class ModelList extends Component
{
    use WithPagination;

    protected $listeners = ['modelUpdated' => '$refresh'];

    public $search = '';
    public $filterMake = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterMake()
    {
        $this->resetPage();
    }

    public function edit($modelId)
    {
        $this->dispatch('editModel', modelId: $modelId);
    }

    public function delete($modelId)
    {
        try {
            $model = VehicleModel::findOrFail($modelId);
            $model->delete();
            session()->flash('success', 'Model deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete this model: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $models = VehicleModel::query()
            ->with('vehicleMake')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterMake, function ($query) {
                $query->where('vehicle_make_id', $this->filterMake);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $makes = \App\Models\VehicleMake::where('status', 'active')->orderBy('name')->get();

        return view('livewire.admin.vehicle-settings.model-list', [
            'models' => $models,
            'makes' => $makes,
        ]);
    }
}
