<?php

namespace App\Livewire\Admin;

use App\Models\PricingPlan;
use Livewire\Component;
use Livewire\WithPagination;

class PricingManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingId = null;
    public $name = '';
    public $category = 'cars';
    public $description = '';
    public $price = '';
    public $currency = 'GBP';
    public $durationDays = '';
    public $features = [];
    public $newFeature = '';
    public $isFeatured = false;
    public $isPopular = false;
    public $isActive = true;
    public $sortOrder = 0;
    public $maxListings = '';
    public $maxTrucks = '';
    public $maxLeases = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'category' => 'required|in:cars,trucks,garage',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'currency' => 'required|string|size:3',
        'durationDays' => 'nullable|integer|min:1',
        'maxListings' => 'nullable|integer|min:0',
        'maxTrucks' => 'nullable|integer|min:0',
        'maxLeases' => 'nullable|integer|min:0',
        'isFeatured' => 'boolean',
        'isPopular' => 'boolean',
        'isActive' => 'boolean',
        'sortOrder' => 'integer|min:0',
    ];

    public function mount()
    {
        //
    }

    public function openModal($id = null)
    {
        $this->resetForm();
        if ($id) {
            $plan = PricingPlan::findOrFail($id);
            $this->editingId = $plan->id;
            $this->name = $plan->name;
            $this->category = $plan->category;
            $this->description = $plan->description ?? '';
            $this->price = $plan->price;
            $this->currency = $plan->currency;
            $this->durationDays = $plan->duration_days;
            $this->maxListings = $plan->max_listings !== null ? (string) $plan->max_listings : '';
            $this->maxTrucks = $plan->max_trucks !== null ? (string) $plan->max_trucks : '';
            $this->maxLeases = $plan->max_leases !== null ? (string) $plan->max_leases : '';
            $this->features = $plan->features ?? [];
            $this->isFeatured = $plan->is_featured;
            $this->isPopular = $plan->is_popular;
            $this->isActive = $plan->is_active;
            $this->sortOrder = $plan->sort_order;
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->category = 'cars';
        $this->description = '';
        $this->price = '';
        $this->currency = 'GBP';
        $this->durationDays = '';
        $this->maxListings = '';
        $this->maxTrucks = '';
        $this->maxLeases = '';
        $this->features = [];
        $this->newFeature = '';
        $this->isFeatured = false;
        $this->isPopular = false;
        $this->isActive = true;
        $this->sortOrder = 0;
    }

    public function addFeature()
    {
        if (!empty($this->newFeature)) {
            $this->features[] = $this->newFeature;
            $this->newFeature = '';
        }
    }

    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'price' => $this->price,
            'currency' => $this->currency,
            'duration_days' => $this->durationDays ?: null,
            'max_listings' => $this->maxListings !== '' && $this->maxListings !== null ? (int) $this->maxListings : null,
            'max_trucks' => $this->maxTrucks !== '' && $this->maxTrucks !== null ? (int) $this->maxTrucks : null,
            'max_leases' => $this->maxLeases !== '' && $this->maxLeases !== null ? (int) $this->maxLeases : null,
            'features' => $this->features,
            'is_featured' => $this->isFeatured,
            'is_popular' => $this->isPopular,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
        ];

        if ($this->editingId) {
            PricingPlan::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Pricing plan updated successfully!');
        } else {
            PricingPlan::create($data);
            session()->flash('message', 'Pricing plan created successfully!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        PricingPlan::findOrFail($id)->delete();
        session()->flash('message', 'Pricing plan deleted successfully!');
    }

    public function toggleActive($id)
    {
        $plan = PricingPlan::findOrFail($id);
        $plan->update(['is_active' => !$plan->is_active]);
        session()->flash('message', 'Pricing plan status updated!');
    }

    public function render()
    {
        $plans = PricingPlan::orderBy('category')
            ->orderBy('sort_order')
            ->orderBy('price')
            ->paginate(20);

        return view('livewire.admin.pricing-management', [
            'plans' => $plans,
        ]);
    }
}
