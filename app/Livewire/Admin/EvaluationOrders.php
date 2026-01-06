<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.admin')]
class EvaluationOrders extends Component
{
    use WithPagination;

    public $filter = 'all';
    public $search = '';
    public $showIssueModal = false;
    public $selectedOrder = null;
    public $valuationAmount = '';
    public $reportNotes = '';

    protected $queryString = ['filter', 'search'];

    public function mount($filter = 'all')
    {
        $this->filter = $filter;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function openIssueModal($orderId)
    {
        $this->selectedOrder = Order::with(['vehicle.make', 'vehicle.model', 'user'])
            ->where('order_type', OrderType::VALUATION_REPORT->value)
            ->findOrFail($orderId);
        
        $this->showIssueModal = true;
        $this->valuationAmount = '';
        $this->reportNotes = '';
    }

    public function closeIssueModal()
    {
        $this->showIssueModal = false;
        $this->selectedOrder = null;
        $this->valuationAmount = '';
        $this->reportNotes = '';
    }

    public function issueReport()
    {
        $this->validate([
            'valuationAmount' => 'required|numeric|min:0',
            'reportNotes' => 'nullable|string|max:1000',
        ]);

        if (!$this->selectedOrder) {
            return;
        }

        // Generate certificate number
        $certificateNumber = 'EVAL-' . date('Y') . '-' . strtoupper(substr(uniqid(), -8));
        
        // Valid until (2 weeks from now)
        $validUntil = now()->addWeeks(2);

        // Update order with completion data
        $this->selectedOrder->update([
            'status' => OrderStatus::COMPLETED->value,
            'completed_at' => now(),
            'processed_by' => Auth::id(),
            'processed_at' => now(),
            'completion_data' => [
                'valuation_amount' => $this->valuationAmount,
                'report_notes' => $this->reportNotes,
                'certificate_number' => $certificateNumber,
                'valid_until' => $validUntil->toDateTimeString(),
                'issued_by' => Auth::user()->name,
                'issued_at' => now()->toDateTimeString(),
            ],
        ]);

        session()->flash('success', 'Evaluation report issued successfully! Certificate #' . $certificateNumber);
        
        $this->closeIssueModal();
        $this->dispatch('reportIssued');
    }

    public function downloadCertificate($orderId)
    {
        $order = Order::with(['vehicle.make', 'vehicle.model', 'user'])
            ->where('order_type', OrderType::VALUATION_REPORT->value)
            ->findOrFail($orderId);

        if (!$order->isCompleted() || !$order->completion_data) {
            session()->flash('error', 'Certificate not available for this order.');
            return;
        }

        $pdf = Pdf::loadView('admin.pdfs.evaluation-certificate', [
            'order' => $order,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'evaluation-certificate-' . $order->completion_data['certificate_number'] . '.pdf');
    }

    public function render()
    {
        $query = Order::with(['vehicle.make', 'vehicle.model', 'user'])
            ->where('order_type', OrderType::VALUATION_REPORT->value)
            ->latest();

        // Apply filters
        if ($this->filter === 'pending-payment') {
            $query->where('payment_required', true)
                  ->where('payment_completed', false);
        } elseif ($this->filter === 'paid') {
            $query->where('payment_completed', true)
                  ->whereIn('status', [OrderStatus::PENDING->value, OrderStatus::PROCESSING->value]);
        } elseif ($this->filter === 'completed') {
            $query->where('status', OrderStatus::COMPLETED->value);
        }

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('vehicle', function ($q) {
                      $q->whereHas('make', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      })->orWhereHas('model', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
                  });
            });
        }

        $orders = $query->paginate(15);

        // Get counts for filters
        $counts = [
            'all' => Order::where('order_type', OrderType::VALUATION_REPORT->value)->count(),
            'pending-payment' => Order::where('order_type', OrderType::VALUATION_REPORT->value)
                ->where('payment_required', true)
                ->where('payment_completed', false)
                ->count(),
            'paid' => Order::where('order_type', OrderType::VALUATION_REPORT->value)
                ->where('payment_completed', true)
                ->whereIn('status', [OrderStatus::PENDING->value, OrderStatus::PROCESSING->value])
                ->count(),
            'completed' => Order::where('order_type', OrderType::VALUATION_REPORT->value)
                ->where('status', OrderStatus::COMPLETED->value)
                ->count(),
        ];

        return view('livewire.admin.evaluation-orders', [
            'orders' => $orders,
            'counts' => $counts,
        ]);
    }
}

