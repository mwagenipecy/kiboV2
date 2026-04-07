<?php

namespace App\Livewire\Customer;

use App\Models\PricingPlan;
use Livewire\Component;

class PricingPage extends Component
{
    public $category = 'cars';

    public bool $showPricingAccessModal = false;

    /** @var array<string, mixed>|null */
    public ?array $pricingAccessModal = null;

    public function mount($category = 'cars')
    {
        $this->category = $category;

        if ($category === 'cars') {
            $payload = session()->pull('pricing_cars_modal');
            if (is_array($payload) && $payload !== []) {
                $this->pricingAccessModal = $payload;
                $this->showPricingAccessModal = true;
            }
        }
    }

    public function dismissPricingModal(): void
    {
        $this->showPricingAccessModal = false;
        $this->pricingAccessModal = null;
    }

    public function openSignInFromPricingModal(): void
    {
        $this->showPricingAccessModal = false;
        $this->pricingAccessModal = null;
        $this->js('setTimeout(function(){ document.getElementById("openAuthModal")?.click(); }, 50);');
    }

    public function render()
    {
        // Cars: show Free tier on the page (informational); paid checkout still excludes it via SubscriptionCheckout.
        $plansQuery = PricingPlan::active()
            ->byCategory($this->category)
            ->ordered();

        if ($this->category !== 'cars') {
            $plansQuery->billable();
        }

        $plans = $plansQuery->get();

        $categoryName = match ($this->category) {
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
