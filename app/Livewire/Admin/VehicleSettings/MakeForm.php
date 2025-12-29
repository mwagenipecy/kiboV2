<?php

namespace App\Livewire\Admin\VehicleSettings;

use App\Models\VehicleMake;
use Livewire\Component;
use Livewire\WithFileUploads;

class MakeForm extends Component
{
    use WithFileUploads;

    public $name = '';
    public $icon;
    public $status = 'active';
    public $editingId = null;

    protected $listeners = ['editMake', 'resetForm'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'icon' => 'nullable|image|max:1024',
        'status' => 'required|in:active,inactive',
    ];

    public function editMake($makeId)
    {
        $make = VehicleMake::findOrFail($makeId);
        $this->editingId = $make->id;
        $this->name = $make->name;
        $this->status = $make->status;
        $this->icon = null; // Reset icon field for new upload
    }

    public function resetForm()
    {
        $this->reset(['name', 'icon', 'status', 'editingId']);
        $this->status = 'active';
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'status' => $this->status,
            ];

            if ($this->icon) {
                $iconPath = $this->icon->store('vehicle-icons', 'public');
                $data['icon'] = $iconPath;
            }

            if ($this->editingId) {
                $make = VehicleMake::findOrFail($this->editingId);
                
                // Delete old icon if new one is uploaded
                if ($this->icon && $make->icon) {
                    \Storage::disk('public')->delete($make->icon);
                }
                
                $make->update($data);
                session()->flash('success', 'Make updated successfully!');
            } else {
                VehicleMake::create($data);
                session()->flash('success', 'Make created successfully!');
            }

            $this->resetForm();
            $this->dispatch('makeUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.vehicle-settings.make-form');
    }
}
