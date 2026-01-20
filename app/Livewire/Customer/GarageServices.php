<?php

namespace App\Livewire\Customer;

use App\Models\Agent;
use Illuminate\Support\Collection;
use Livewire\Component;

class GarageServices extends Component
{
    public array $serviceGroups = [];
    public bool $showModal = false;
    public ?string $modalService = null;
    public array $modalGarages = [];

    public function mount(): void
    {
        $this->loadServiceGroups();
    }

    public function openModal(string $service): void
    {
        if (!isset($this->serviceGroups[$service])) {
            $this->showModal = false;
            $this->modalService = null;
            $this->modalGarages = [];
            return;
        }

        $this->modalService = $this->formatService($service);
        $this->modalGarages = $this->serviceGroups[$service]['garages'] ?? [];
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->modalService = null;
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
}

