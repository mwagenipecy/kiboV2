<?php

namespace App\View\Components\Customer;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use Illuminate\View\Component;

class CarsListPreview extends Component
{
    /** Optional: 'used' or 'new' to filter listings. */
    public ?string $condition;

    /** Section title (e.g. "Browse used cars for sale"). */
    public string $title;

    /**
     * Create a new component instance.
     */
    public function __construct(?string $condition = null, ?string $title = null)
    {
        $this->condition = $condition;
        if ($title !== null) {
            $this->title = $title;
        } elseif ($condition === 'used') {
            $this->title = 'Browse used cars for sale';
        } elseif ($condition === 'new') {
            $this->title = 'Browse new cars for sale';
        } else {
            $this->title = 'Browse cars for sale';
        }
    }

    /**
     * Get the view / contents of the component.
     */
    public function render()
    {
        $query = Vehicle::with(['make', 'model', 'entity'])
            ->where('status', VehicleStatus::APPROVED);

        if (in_array($this->condition, ['used', 'new'], true)) {
            $query->where('condition', $this->condition);
        }

        $vehicles = $query->latest()->limit(8)->get();

        return view('components.customer.cars-list-preview', [
            'vehicles' => $vehicles,
            'title' => $this->title,
            'condition' => $this->condition,
        ]);
    }
}
