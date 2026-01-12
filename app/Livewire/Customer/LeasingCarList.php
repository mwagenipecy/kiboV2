<?php

namespace App\Livewire\Customer;

use App\Models\VehicleLease;
use Livewire\Component;
use Livewire\WithPagination;

class LeasingCarList extends Component
{
    use WithPagination;

    // Filter parameters
    public $search = '';
    public $make = '';
    public $model = '';
    public $minYear = '';
    public $maxYear = '';
    public $minPayment = '';
    public $maxPayment = '';
    public $leaseTerm = [];
    public $bodyType = [];
    
    // Sort
    public $sortBy = 'relevance';
    
    // UI states
    public $showFilters = false;
    public $savedLeases = [];
    public $expandedSections = [
        'sort' => true,
        'makeModel' => false,
        'year' => false,
        'payment' => false,
        'leaseTerm' => false,
        'bodyType' => false,
    ];
    
    protected $queryString = [
        'search',
        'make',
        'model',
        'minYear',
        'maxYear',
        'minPayment',
        'maxPayment',
        'sortBy',
    ];

    public function mount()
    {
        // Load saved leases from session
        $this->savedLeases = session()->get('saved_leases', []);
    }

    public function updatingMake()
    {
        $this->model = ''; // Reset model when make changes
        $this->resetPage();
    }

    public function updatedAnyFilter()
    {
        $this->resetPage();
    }

    public function toggleSave($leaseId)
    {
        if (in_array($leaseId, $this->savedLeases)) {
            $this->savedLeases = array_diff($this->savedLeases, [$leaseId]);
        } else {
            $this->savedLeases[] = $leaseId;
        }
        session()->put('saved_leases', $this->savedLeases);
    }

    public function toggleSection($section)
    {
        $this->expandedSections[$section] = !$this->expandedSections[$section];
    }

    public function clearFilters()
    {
        $this->reset([
            'search', 'make', 'model', 'minYear', 'maxYear',
            'minPayment', 'maxPayment', 'leaseTerm', 'bodyType', 'sortBy'
        ]);
        $this->showFilters = false;
        $this->resetPage();
    }

    public function render()
    {
        $query = VehicleLease::with(['entity'])
            ->active();

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('lease_title', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicle_title', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicle_make', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicle_model', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by make
        if ($this->make) {
            $query->where('vehicle_make', 'like', '%' . $this->make . '%');
        }

        // Filter by model
        if ($this->model) {
            $query->where('vehicle_model', 'like', '%' . $this->model . '%');
        }

        // Filter by year
        if ($this->minYear) {
            $query->where('vehicle_year', '>=', $this->minYear);
        }
        if ($this->maxYear) {
            $query->where('vehicle_year', '<=', $this->maxYear);
        }

        // Filter by payment range
        if ($this->minPayment) {
            $query->where('monthly_payment', '>=', $this->minPayment);
        }
        if ($this->maxPayment) {
            $query->where('monthly_payment', '<=', $this->maxPayment);
        }

        // Filter by lease term
        if (!empty($this->leaseTerm)) {
            $query->whereIn('lease_term_months', $this->leaseTerm);
        }

        // Filter by body type
        if (!empty($this->bodyType)) {
            $query->whereIn('body_type', $this->bodyType);
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'payment_low':
                $query->orderBy('monthly_payment', 'asc');
                break;
            case 'payment_high':
                $query->orderBy('monthly_payment', 'desc');
                break;
            case 'year_new':
                $query->orderBy('vehicle_year', 'desc');
                break;
            case 'year_old':
                $query->orderBy('vehicle_year', 'asc');
                break;
            case 'term_short':
                $query->orderBy('lease_term_months', 'asc');
                break;
            case 'term_long':
                $query->orderBy('lease_term_months', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $leases = $query->paginate(16);
        $totalCount = $leases->total();

        // Get available filters data
        $availableMakes = VehicleLease::active()
            ->whereNotNull('vehicle_make')
            ->distinct()
            ->pluck('vehicle_make')
            ->filter()
            ->sort()
            ->values();

        $availableModels = collect();
        if ($this->make) {
            $availableModels = VehicleLease::active()
                ->where('vehicle_make', 'like', '%' . $this->make . '%')
                ->whereNotNull('vehicle_model')
                ->distinct()
                ->pluck('vehicle_model')
                ->filter()
                ->sort()
                ->values();
        }

        $availableTerms = VehicleLease::active()
            ->whereNotNull('lease_term_months')
            ->distinct()
            ->pluck('lease_term_months')
            ->sort()
            ->values();

        return view('livewire.customer.leasing-car-list', [
            'leases' => $leases,
            'totalCount' => $totalCount,
            'availableMakes' => $availableMakes,
            'availableModels' => $availableModels,
            'availableTerms' => $availableTerms,
        ])->layout('layouts.customer');
    }
}
