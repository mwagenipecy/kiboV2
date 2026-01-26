<?php

namespace App\Livewire\Customer;

use App\Models\Agent;
use App\Models\GarageServiceOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GarageServices extends Component
{
    public array $serviceGroups = [];
    public bool $showModal = false;
    public ?string $modalService = null;
    public ?string $modalServiceKey = null;
    public array $modalGarages = [];
    public bool $showTracking = false;
    public $userOrders = [];

    public function mount(): void
    {
        $this->loadServiceGroups();
        $this->loadUserOrders();
    }

    protected $listeners = ['booking-created' => 'loadUserOrders'];

    public function openModal(string $service): void
    {
        if (!isset($this->serviceGroups[$service])) {
            $this->showModal = false;
            $this->modalService = null;
            $this->modalServiceKey = null;
            $this->modalGarages = [];
            return;
        }

        $this->modalServiceKey = $service;
        $this->modalService = $this->formatService($service);
        $this->modalGarages = $this->serviceGroups[$service]['garages'] ?? [];
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->modalService = null;
        $this->modalServiceKey = null;
        $this->modalGarages = [];
    }

    public function render()
    {
        return view('livewire.customer.garage-services');
    }

    private function loadServiceGroups(): void
    {
        $garages = Agent::where('agent_type', 'garage_owner')
            ->where('status', 'active')
            ->get();

        $groups = $garages
            ->flatMap(function ($garage) {
                return collect($garage->services ?? [])->map(function ($service) use ($garage) {
                    return [
                        'service' => $service,
                        'garage' => $garage,
                    ];
                });
            })
            ->groupBy('service')
            ->map(function (Collection $items) {
                $garages = $items->pluck('garage')->unique('id')->map(function ($garage) {
                    return [
                        'id' => $garage->id,
                        'name' => $garage->company_name ?? $garage->name ?? 'Garage',
                        'phone' => $garage->phone_number ?? null,
                        'email' => $garage->email ?? null,
                        'address' => $garage->address ?? null,
                    ];
                })->values()->toArray();

                return [
                    'count' => count($garages),
                    'garages' => $garages,
                ];
            })
            ->sortKeys()
            ->toArray();

        $this->serviceGroups = $groups;
    }

    public function formatService(string $service): string
    {
        return ucwords(str_replace('_', ' ', $service));
    }

    public function loadUserOrders(): void
    {
        if (Auth::check()) {
            $this->userOrders = GarageServiceOrder::where('user_id', Auth::id())
                ->with('agent')
                ->latest()
                ->get()
                ->toArray();
        }
    }

    public function toggleTracking(): void
    {
        $this->showTracking = !$this->showTracking;
        if ($this->showTracking) {
            $this->loadUserOrders();
        }
    }
}

