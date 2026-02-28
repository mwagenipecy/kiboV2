<?php

namespace App\Livewire\Customer;

use App\Models\DealerSubscription;
use App\Models\Entity;
use App\Models\PricingPlan;
use Livewire\Component;

class SubscriptionCheckout extends Component
{
    /** @var int */
    public $planId;

    /** @var PricingPlan|null */
    public $plan;

    /** @var Entity|null */
    public $entity;

    /** @var bool */
    public $paymentStep = false;

    /** @var DealerSubscription|null */
    public $subscription;

    public function mount($plan)
    {
        $this->planId = (int) $plan;
        $this->plan = PricingPlan::active()->byCategory('cars')->find($this->planId);

        if (!$this->plan) {
            session()->flash('error', 'Plan not found or no longer available.');
            return $this->redirect(route('pricing.show', ['category' => 'cars']), navigate: true);
        }

        $user = auth()->user();
        if (!$user || !$user->entity_id) {
            session()->flash('error', 'You must be signed in as a dealer to subscribe. Register or sign in with your dealer account.');
            return $this->redirect(route('pricing.show', ['category' => 'cars']), navigate: true);
        }

        $this->entity = Entity::with('pricingPlan')->find($user->entity_id);
        if (!$this->entity || $this->entity->type->value !== 'dealer') {
            session()->flash('error', 'Only dealer accounts can subscribe to listing plans.');
            return $this->redirect(route('pricing.show', ['category' => 'cars']), navigate: true);
        }
    }

    public function proceedToPayment()
    {
        $subscription = DealerSubscription::create([
            'entity_id' => $this->entity->id,
            'pricing_plan_id' => $this->plan->id,
            'status' => 'pending_payment',
            'amount' => $this->plan->price,
            'currency' => $this->plan->currency,
        ]);

        $this->subscription = $subscription;
        $this->paymentStep = true;
    }

    public function backToPricing()
    {
        return $this->redirect(route('pricing.show', ['category' => 'cars']), navigate: true);
    }

    public function render()
    {
        return view('livewire.customer.subscription-checkout')
            ->layout('layouts.customer', ['vehicleType' => 'cars']);
    }
}
