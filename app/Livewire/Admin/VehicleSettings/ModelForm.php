<?php

namespace App\Livewire\Admin\VehicleSettings;

use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Livewire\Component;

class ModelForm extends Component
{
    public $name = '';
    public $vehicle_make_id = '';
    public $status = 'active';
    public $editingId = null;

    protected $listeners = ['editModel', 'resetForm'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'vehicle_make_id' => 'required|exists:vehicle_makes,id',
        'status' => 'required|in:active,inactive',
    ];

    public function editModel($modelId)
    {
        $model = VehicleModel::findOrFail($modelId);
        $this->editingId = $model->id;
        $this->name = $model->name;
        $this->vehicle_make_id = $model->vehicle_make_id;
        $this->status = $model->status;
    }

    public function resetForm()
    {
        $this->reset(['name', 'vehicle_make_id', 'status', 'editingId']);
        $this->status = 'active';
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'vehicle_make_id' => $this->vehicle_make_id,
                'status' => $this->status,
            ];

            if ($this->editingId) {
                $model = VehicleModel::findOrFail($this->editingId);
                $model->update($data);
                session()->flash('success', 'Model updated successfully!');
            } else {
                VehicleModel::create($data);
                session()->flash('success', 'Model created successfully!');
            }

            $this->resetForm();
            $this->dispatch('modelUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $makes = VehicleMake::where('status', 'active')->orderBy('name')->get();
        
        return view('livewire.admin.vehicle-settings.model-form', [
            'makes' => $makes,
        ]);
    }
}
