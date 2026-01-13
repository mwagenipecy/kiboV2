<?php

namespace App\Livewire\Customer;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer', ['vehicleType' => 'cars'])]
class MyAdverts extends Component
{
    use WithPagination;

    public $filterStatus = 'all';

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('cars.index');
        }
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function delete($vehicleId)
    {
        $vehicle = Vehicle::where('registered_by', Auth::id())
            ->findOrFail($vehicleId);
        
        // Delete images
        if ($vehicle->image_front) {
            Storage::disk('public')->delete($vehicle->image_front);
        }
        if ($vehicle->other_images) {
            foreach ($vehicle->other_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $vehicle->delete();
        
        session()->flash('success', 'Vehicle listing deleted successfully!');
    }

    public function render()
    {
        $query = Vehicle::where('registered_by', Auth::id())
            ->with(['make', 'model']);

        if ($this->filterStatus !== 'all') {
            if ($this->filterStatus === 'active') {
                $query->where('status', VehicleStatus::APPROVED);
            } else {
                $query->where('status', $this->filterStatus);
            }
        }

        $vehicles = $query->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('livewire.customer.my-adverts', [
            'vehicles' => $vehicles,
        ]);
    }
}
