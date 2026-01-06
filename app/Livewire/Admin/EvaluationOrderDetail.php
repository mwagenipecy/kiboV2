<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.admin')]
class EvaluationOrderDetail extends Component
{
    public $orderId;
    public $order;
    
    // Assessment fields
    public $valuationAmount = '';
    public $vehicleCondition = '';
    public $exteriorCondition = '';
    public $interiorCondition = '';
    public $mechanicalCondition = '';
    public $issuesFound = '';
    public $recommendedRepairs = '';
    public $reportNotes = '';
    public $showIssueForm = false;
    public $showConfirmModal = false;

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->loadOrder();
    }

    public function loadOrder()
    {
        $this->order = Order::with(['vehicle.make', 'vehicle.model', 'user'])
            ->where('order_type', OrderType::VALUATION_REPORT->value)
            ->findOrFail($this->orderId);
    }

    public function toggleIssueForm()
    {
        $this->showIssueForm = !$this->showIssueForm;
    }

    public function prepareIssueReport()
    {
        // Validate first
        $this->validate([
            'valuationAmount' => 'required|numeric|min:0',
            'vehicleCondition' => 'required|in:excellent,very_good,good,fair,poor',
            'exteriorCondition' => 'required|in:excellent,very_good,good,fair,poor',
            'interiorCondition' => 'required|in:excellent,very_good,good,fair,poor',
            'mechanicalCondition' => 'required|in:excellent,very_good,good,fair,poor',
            'issuesFound' => 'nullable|string|max:2000',
            'recommendedRepairs' => 'nullable|string|max:1000',
            'reportNotes' => 'nullable|string|max:1000',
        ]);

        // Show confirmation modal
        $this->showConfirmModal = true;
    }

    public function cancelConfirmation()
    {
        $this->showConfirmModal = false;
    }

    public function issueReport()
    {
        // Close confirmation modal
        $this->showConfirmModal = false;

        // Generate certificate number
        $certificateNumber = 'EVAL-' . date('Y') . '-' . strtoupper(substr(uniqid(), -8));
        
        // Valid until (2 weeks from now)
        $validUntil = now()->addWeeks(2);

        // Update order with completion data
        $this->order->update([
            'status' => OrderStatus::COMPLETED->value,
            'completed_at' => now(),
            'processed_by' => Auth::id(),
            'processed_at' => now(),
            'completion_data' => [
                'valuation_amount' => $this->valuationAmount,
                'vehicle_condition' => $this->vehicleCondition,
                'exterior_condition' => $this->exteriorCondition,
                'interior_condition' => $this->interiorCondition,
                'mechanical_condition' => $this->mechanicalCondition,
                'issues_found' => $this->issuesFound,
                'recommended_repairs' => $this->recommendedRepairs,
                'report_notes' => $this->reportNotes,
                'certificate_number' => $certificateNumber,
                'valid_until' => $validUntil->toDateTimeString(),
                'issued_by' => Auth::user()->name,
                'issued_at' => now()->toDateTimeString(),
            ],
        ]);

        session()->flash('success', 'Evaluation report issued successfully! Certificate #' . $certificateNumber);
        
        $this->showIssueForm = false;
        $this->loadOrder();
        $this->dispatch('reportIssued');
    }

    public function downloadCertificate()
    {
        if (!$this->order->isCompleted() || !$this->order->completion_data) {
            session()->flash('error', 'Certificate not available for this order.');
            return;
        }

        $pdf = Pdf::loadView('admin.pdfs.evaluation-certificate', [
            'order' => $this->order,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'evaluation-certificate-' . $this->order->completion_data['certificate_number'] . '.pdf');
    }

    public function render()
    {
        return view('livewire.admin.evaluation-order-detail');
    }
}

