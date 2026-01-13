<?php

namespace App\Livewire\Customer;

use App\Models\PricingPlan;
use Livewire\Component;

class PricingPage extends Component
{
    public $category = 'cars';

    public function mount($category = 'cars')
    {
        $this->category = $category;
    }

    public function render()
    {
        $plans = PricingPlan::active()
            ->byCategory($this->category)
            ->ordered()
            ->get();

        $categoryName = match($this->category) {
            'cars' => 'Cars',
            'trucks' => 'Trucks',
            'garage' => 'Garage Services',
            default => 'Advertising',
        };

        return view('livewire.customer.pricing-page', [
            'plans' => $plans,
            'categoryName' => $categoryName,
        ]);
    }
}
