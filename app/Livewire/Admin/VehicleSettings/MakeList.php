<?php

namespace App\Livewire\Admin\VehicleSettings;

use App\Models\VehicleMake;
use Livewire\Component;
use Livewire\WithPagination;

class MakeList extends Component
{
    use WithPagination;

    protected $listeners = ['makeUpdated' => '$refresh'];

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($makeId)
    {
        $this->dispatch('editMake', makeId: $makeId);
    }

    public function delete($makeId)
    {
        try {
            $make = VehicleMake::findOrFail($makeId);
            
            // Delete icon if exists
            if ($make->icon) {
                \Storage::disk('public')->delete($make->icon);
            }
            
            $make->delete();
            session()->flash('success', 'Make deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete this make: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $makes = VehicleMake::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount('vehicleModels')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.vehicle-settings.make-list', [
            'makes' => $makes,
        ]);
    }
}
