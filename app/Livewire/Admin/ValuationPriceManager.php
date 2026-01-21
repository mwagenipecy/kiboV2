<?php

namespace App\Livewire\Admin;

use App\Models\ValuationPrice;
use App\Models\VehicleMake;
use Livewire\Component;
use Livewire\WithPagination;

class ValuationPriceManager extends Component
{
    use WithPagination;

    // Filter properties
    public $filterType = '';
    public $filterUrgency = '';
    public $filterActive = '';

    // Form properties
    public $showModal = false;
    public $editingId = null;
    public $name = '';
    public $type = 'car';
    public $vehicle_make_id = '';
    public $urgency = 'standard';
    public $price = '';
    public $currency = 'TZS';
    public $description = '';
    public $is_active = true;
    public $sort_order = 0;

    // Confirmation modal
    public $showDeleteModal = false;
    public $deletingId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:car,truck,house',
        'vehicle_make_id' => 'nullable|exists:vehicle_makes,id',
        'urgency' => 'required|in:standard,urgent',
        'price' => 'required|numeric|min:0',
        'currency' => 'required|in:TZS,USD,GBP,EUR,KES',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
        'sort_order' => 'integer|min:0',
    ];

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterUrgency()
    {
        $this->resetPage();
    }

    public function updatingFilterActive()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $price = ValuationPrice::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $price->name;
        $this->type = $price->type;
        $this->vehicle_make_id = $price->vehicle_make_id ?? '';
        $this->urgency = $price->urgency;
        $this->price = $price->price;
        $this->currency = $price->currency;
        $this->description = $price->description ?? '';
        $this->is_active = $price->is_active;
        $this->sort_order = $price->sort_order;
        
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
        $this->type = 'car';
        $this->vehicle_make_id = '';
        $this->urgency = 'standard';
        $this->price = '';
        $this->currency = 'TZS';
        $this->description = '';
        $this->is_active = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'vehicle_make_id' => $this->vehicle_make_id ?: null,
            'urgency' => $this->urgency,
            'price' => $this->price,
            'currency' => $this->currency,
            'description' => $this->description ?: null,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingId) {
            $price = ValuationPrice::findOrFail($this->editingId);
            $price->update($data);
            session()->flash('success', 'Valuation price updated successfully!');
        } else {
            ValuationPrice::create($data);
            session()->flash('success', 'Valuation price created successfully!');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->deletingId = null;
        $this->showDeleteModal = false;
    }

    public function delete()
    {
        if ($this->deletingId) {
            ValuationPrice::destroy($this->deletingId);
            session()->flash('success', 'Valuation price deleted successfully!');
        }
        
        $this->cancelDelete();
    }

    public function toggleActive($id)
    {
        $price = ValuationPrice::findOrFail($id);
        $price->update(['is_active' => !$price->is_active]);
        
        session()->flash('success', 'Price status updated successfully!');
    }

    public function render()
    {
        $query = ValuationPrice::with('vehicleMake');

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterUrgency) {
            $query->where('urgency', $this->filterUrgency);
        }

        if ($this->filterActive !== '') {
            $query->where('is_active', $this->filterActive === '1');
        }

        $prices = $query->orderBy('type')
            ->orderBy('urgency')
            ->orderBy('sort_order')
            ->paginate(15);

        $vehicleMakes = VehicleMake::active()->orderBy('name')->get();

        return view('livewire.admin.valuation-price-manager', [
            'prices' => $prices,
            'vehicleMakes' => $vehicleMakes,
            'types' => ValuationPrice::TYPES,
            'urgencies' => ValuationPrice::URGENCIES,
            'currencies' => ValuationPrice::CURRENCIES,
        ])->layout('layouts.admin');
    }
}

