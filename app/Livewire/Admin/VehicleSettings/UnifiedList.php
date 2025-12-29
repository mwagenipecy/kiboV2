<?php

namespace App\Livewire\Admin\VehicleSettings;

use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class UnifiedList extends Component
{
    use WithPagination, WithFileUploads;

    public $activeTab = 'makes'; // 'makes' or 'models'
    public $search = '';
    public $filterMake = '';
    
    // Make fields
    public $makeId = null;
    public $makeName = '';
    public $makeIcon;
    public $makeStatus = 'active';
    public $makeToDelete = null;
    
    // Model fields
    public $modelId = null;
    public $modelName = '';
    public $modelMakeId = '';
    public $modelStatus = 'active';
    public $modelToDelete = null;
    
    // Modal states
    public $showMakeModal = false;
    public $showMakeDeleteModal = false;
    public $showModelModal = false;
    public $showModelDeleteModal = false;

    protected $queryString = ['activeTab', 'search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterMake()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->search = '';
        $this->filterMake = '';
        $this->resetPage();
    }

    // ====== MAKE METHODS ======
    
    public function openMakeModal()
    {
        $this->resetMakeForm();
        $this->showMakeModal = true;
    }

    public function editMake($makeId)
    {
        $make = VehicleMake::findOrFail($makeId);
        $this->makeId = $make->id;
        $this->makeName = $make->name;
        $this->makeStatus = $make->status;
        $this->makeIcon = null;
        $this->showMakeModal = true;
    }

    public function saveMake()
    {
        $this->validate([
            'makeName' => [
                'required',
                'string',
                'max:255',
                $this->makeId 
                    ? 'unique:vehicle_makes,name,' . $this->makeId 
                    : 'unique:vehicle_makes,name'
            ],
            'makeIcon' => 'nullable|image|max:1024',
            'makeStatus' => 'required|in:active,inactive',
        ]);

        try {
            $data = [
                'name' => $this->makeName,
                'status' => $this->makeStatus,
            ];

            if ($this->makeIcon) {
                $iconPath = $this->makeIcon->store('vehicle-icons', 'public');
                $data['icon'] = $iconPath;
            }

            if ($this->makeId) {
                $make = VehicleMake::findOrFail($this->makeId);
                
                if ($this->makeIcon && $make->icon) {
                    \Storage::disk('public')->delete($make->icon);
                }
                
                $make->update($data);
                session()->flash('success', 'Make updated successfully!');
            } else {
                VehicleMake::create($data);
                session()->flash('success', 'Make created successfully!');
            }

            $this->resetMakeForm();
            $this->showMakeModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function confirmDeleteMake($makeId)
    {
        $this->makeToDelete = $makeId;
        $this->showMakeDeleteModal = true;
    }

    public function deleteMake()
    {
        try {
            $make = VehicleMake::findOrFail($this->makeToDelete);
            
            if ($make->icon) {
                \Storage::disk('public')->delete($make->icon);
            }
            
            $make->delete();
            session()->flash('success', 'Make deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete this make: ' . $e->getMessage());
        }

        $this->showMakeDeleteModal = false;
        $this->makeToDelete = null;
    }

    public function resetMakeForm()
    {
        $this->makeId = null;
        $this->makeName = '';
        $this->makeIcon = null;
        $this->makeStatus = 'active';
    }

    // ====== MODEL METHODS ======
    
    public function openModelModal()
    {
        $this->resetModelForm();
        $this->showModelModal = true;
    }

    public function editModel($modelId)
    {
        $model = VehicleModel::findOrFail($modelId);
        $this->modelId = $model->id;
        $this->modelName = $model->name;
        $this->modelMakeId = $model->vehicle_make_id;
        $this->modelStatus = $model->status;
        $this->showModelModal = true;
    }

    public function saveModel()
    {
        $this->validate([
            'modelName' => [
                'required',
                'string',
                'max:255',
            ],
            'modelMakeId' => 'required|exists:vehicle_makes,id',
            'modelStatus' => 'required|in:active,inactive',
        ]);

        // Check for duplicate model name within the same make
        $existingModel = VehicleModel::where('name', $this->modelName)
            ->where('vehicle_make_id', $this->modelMakeId)
            ->when($this->modelId, function ($query) {
                $query->where('id', '!=', $this->modelId);
            })
            ->first();

        if ($existingModel) {
            $this->addError('modelName', 'This model already exists for the selected make.');
            return;
        }

        try {
            $data = [
                'name' => $this->modelName,
                'vehicle_make_id' => $this->modelMakeId,
                'status' => $this->modelStatus,
            ];

            if ($this->modelId) {
                $model = VehicleModel::findOrFail($this->modelId);
                $model->update($data);
                session()->flash('success', 'Model updated successfully!');
            } else {
                VehicleModel::create($data);
                session()->flash('success', 'Model created successfully!');
            }

            $this->resetModelForm();
            $this->showModelModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function confirmDeleteModel($modelId)
    {
        $this->modelToDelete = $modelId;
        $this->showModelDeleteModal = true;
    }

    public function deleteModel()
    {
        try {
            $model = VehicleModel::findOrFail($this->modelToDelete);
            $model->delete();
            session()->flash('success', 'Model deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete this model: ' . $e->getMessage());
        }

        $this->showModelDeleteModal = false;
        $this->modelToDelete = null;
    }

    public function resetModelForm()
    {
        $this->modelId = null;
        $this->modelName = '';
        $this->modelMakeId = '';
        $this->modelStatus = 'active';
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

        $allMakes = VehicleMake::where('status', 'active')->orderBy('name')->get();

        return view('livewire.admin.vehicle-settings.unified-list', [
            'makes' => $makes,
            'models' => $models,
            'allMakes' => $allMakes,
        ]);
    }
}
