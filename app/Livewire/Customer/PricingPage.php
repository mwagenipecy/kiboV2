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

        // For cars: dealer subscription – current plan and upgrade flags
        $currentPlan = null;
        $isDealer = false;
        $currentPlanIndex = -1;

        if ($this->category === 'cars' && auth()->check() && auth()->user()->entity_id) {
            $entity = auth()->user()->entity;
            if ($entity && $entity->type->value === 'dealer') {
                $isDealer = true;
                $currentPlan = $entity->pricingPlan;
                if ($currentPlan) {
                    $idx = $plans->search(fn ($p) => $p->id === $currentPlan->id);
                    $currentPlanIndex = $idx !== false ? $idx : -1;
                }
            }
        }

        return view('livewire.customer.pricing-page', [
            'plans' => $plans,
            'categoryName' => $categoryName,
            'currentPlan' => $currentPlan,
            'isDealer' => $isDealer,
            'currentPlanIndex' => $currentPlanIndex,
        ]);
    }
}
