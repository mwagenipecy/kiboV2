<?php

namespace App\Livewire\Admin;

use App\Models\PaymentLink;
use App\Models\PaymentLinkGenerationLog;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentLinksList extends Component
{
    use WithPagination;

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $statusFilter = 'all';

    public string $section = 'overview';

    public string $search = '';

    protected $queryString = ['dateFrom', 'dateTo', 'statusFilter', 'search'];

    public function mount(string $section = 'overview'): void
    {
        $this->section = $section;
        if ($this->dateFrom === '') {
            $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        }
        if ($this->dateTo === '') {
            $this->dateTo = now()->format('Y-m-d');
        }
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    protected function baseQuery()
    {
        $query = PaymentLink::with('items')
            ->whereBetween('created_at', [
                $this->dateFrom . ' 00:00:00',
                $this->dateTo . ' 23:59:59',
            ])
            ->latest();

        if ($this->statusFilter === 'unpaid') {
            $query->unpaidItems();
        } elseif ($this->statusFilter === 'partial') {
            $query->withPartialItems();
        } elseif ($this->statusFilter === 'paid') {
            $query->fullyPaid();
        }

        if ($this->search !== '') {
            $term = '%' . $this->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('customer_reference', 'like', $term)
                    ->orWhere('customer_name', 'like', $term)
                    ->orWhere('customer_email', 'like', $term)
                    ->orWhere('customer_phone', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('short_code', 'like', $term)
                    ->orWhere('link_id', 'like', $term);
            });
        }

        return $query;
    }

    public function getFilteredLinksProperty()
    {
        return $this->baseQuery()->paginate(15);
    }

    public function getStatsProperty(): array
    {
        $links = $this->baseQuery()->get();
        $total = $links->sum('total_amount');
        $paid = $links->sum(fn ($l) => $l->total_paid_amount);
        $unpaid = $total - $paid;
        $paidCount = $links->filter(fn ($l) => $l->overall_payment_status === 'paid')->count();
        $unpaidCount = $links->filter(fn ($l) => $l->overall_payment_status === 'unpaid')->count();
        $partialCount = $links->filter(fn ($l) => $l->overall_payment_status === 'partial')->count();

        return [
            'total' => $total,
            'paid' => $paid,
            'unpaid' => $unpaid,
            'links' => $links->count(),
            'paid_count' => $paidCount,
            'unpaid_count' => $unpaidCount,
            'partial_count' => $partialCount,
        ];
    }

    public function getByDateProperty(): array
    {
        $links = $this->baseQuery()->get();
        $byDate = [];
        foreach ($links as $link) {
            $date = $link->created_at->format('Y-m-d');
            if (!isset($byDate[$date])) {
                $byDate[$date] = ['paid' => 0, 'unpaid' => 0];
            }
            $paid = $link->total_paid_amount;
            $total = $link->total_amount;
            $byDate[$date]['paid'] += $paid;
            $byDate[$date]['unpaid'] += max(0, $total - $paid);
        }
        ksort($byDate);
        return $byDate;
    }

    public function getGenerationLogsProperty()
    {
        return PaymentLinkGenerationLog::with('paymentLink')
            ->latest()
            ->take(50)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.payment-links-list', [
            'filteredLinks' => $this->filteredLinks,
            'stats' => $this->stats,
            'byDate' => $this->byDate,
            'generationLogs' => $this->generationLogs,
        ]);
    }
}
