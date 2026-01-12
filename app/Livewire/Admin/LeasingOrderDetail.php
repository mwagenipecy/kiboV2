<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\VehicleLease;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class LeasingOrderDetail extends Component
{
    public Order $order;
    public $lease = null;
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $showQuotationModal = false;
    public $showPaymentModal = false;
    public $showContractModal = false;
    public $showStartLeaseModal = false;
    public $showReturnModal = false;
    public $rejectionReason = '';
    public $adminNotes = '';
    public $quotationAmount = '';
    public $paymentAmount = '';
    public $paymentDate = '';
    public $paymentMethod = 'cash';
    public $paymentReference = '';
    public $contractIssuedAt = '';
    public $leaseStartDate = '';
    public $returnReason = '';
    public $returnDate = '';
    public $currentMileage = '';

    public function mount($id)
    {
        $this->order = Order::with(['user.customer', 'processedBy'])
            ->where('order_type', OrderType::LEASING_APPLICATION->value)
            ->findOrFail($id);
        
        $orderData = $this->order->order_data ?? [];
        $leaseId = $orderData['lease_id'] ?? null;
        
        if ($leaseId) {
            $this->lease = VehicleLease::find($leaseId);
        }
        
        $this->adminNotes = $this->order->admin_notes ?? '';
    }

    public function openApproveModal()
    {
        $this->showApproveModal = true;
    }

    public function closeApproveModal()
    {
        $this->showApproveModal = false;
    }

    public function openRejectModal()
    {
        $this->showRejectModal = true;
        $this->rejectionReason = '';
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->rejectionReason = '';
    }

    public function openQuotationModal()
    {
        $orderData = $this->order->order_data ?? [];
        $this->quotationAmount = $orderData['total_upfront_cost'] ?? ($orderData['down_payment'] ?? 0 + $orderData['security_deposit'] ?? 0);
        $this->showQuotationModal = true;
    }

    public function closeQuotationModal()
    {
        $this->showQuotationModal = false;
        $this->quotationAmount = '';
    }

    public function openPaymentModal()
    {
        $orderData = $this->order->order_data ?? [];
        $this->paymentAmount = $orderData['quotation_amount'] ?? $orderData['total_upfront_cost'] ?? 0;
        $this->paymentDate = now()->format('Y-m-d');
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->paymentAmount = '';
        $this->paymentDate = '';
        $this->paymentMethod = 'cash';
        $this->paymentReference = '';
    }

    public function openContractModal()
    {
        $this->contractIssuedAt = now()->format('Y-m-d');
        $this->showContractModal = true;
    }

    public function closeContractModal()
    {
        $this->showContractModal = false;
        $this->contractIssuedAt = '';
    }

    public function openStartLeaseModal()
    {
        $this->leaseStartDate = now()->format('Y-m-d');
        $this->showStartLeaseModal = true;
    }

    public function closeStartLeaseModal()
    {
        $this->showStartLeaseModal = false;
        $this->leaseStartDate = '';
    }

    public function openReturnModal()
    {
        $this->returnDate = now()->format('Y-m-d');
        $this->returnReason = '';
        $this->showReturnModal = true;
    }

    public function closeReturnModal()
    {
        $this->showReturnModal = false;
        $this->returnDate = '';
        $this->returnReason = '';
    }

    public function approveOrder()
    {
        if (!$this->order->isPending()) {
            session()->flash('error', 'Only pending applications can be approved.');
            return;
        }

        $this->validate([
            'adminNotes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () {
            $orderData = $this->order->order_data ?? [];
            
            $orderData['approval_status'] = 'approved';
            $orderData['approved_at'] = now()->toDateTimeString();
            $orderData['approved_by'] = Auth::id();
            $orderData['approved_by_name'] = Auth::user()->name;
            
            $this->order->update([
                'status' => OrderStatus::APPROVED->value,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'admin_notes' => $this->adminNotes,
                'order_data' => $orderData,
            ]);
            
            // Mark lease car as reserved when approved
            if ($this->lease) {
                $this->lease->update([
                    'status' => 'reserved',
                ]);
            }
        });

        session()->flash('success', 'Leasing application approved. Lease vehicle marked as reserved. You can now send quotation.');
        $this->closeApproveModal();
        $this->mount($this->order->id);
    }

    public function rejectOrder()
    {
        if (!$this->order->isPending()) {
            session()->flash('error', 'Only pending applications can be rejected.');
            return;
        }

        $this->validate([
            'rejectionReason' => 'required|string|min:10',
        ], [
            'rejectionReason.required' => 'Please provide a reason for rejection.',
            'rejectionReason.min' => 'Rejection reason must be at least 10 characters.',
        ]);

        DB::transaction(function () {
            $orderData = $this->order->order_data ?? [];
            
            $orderData['approval_status'] = 'rejected';
            $orderData['rejected_at'] = now()->toDateTimeString();
            $orderData['rejected_by'] = Auth::id();
            $orderData['rejected_by_name'] = Auth::user()->name;
            
            $this->order->update([
                'status' => OrderStatus::REJECTED->value,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'admin_notes' => $this->adminNotes,
                'rejection_reason' => $this->rejectionReason,
                'order_data' => $orderData,
            ]);
            
            // Lease car status remains the same (no change)
        });

        session()->flash('success', 'Leasing application rejected.');
        $this->closeRejectModal();
        $this->mount($this->order->id);
    }

    public function sendQuotation()
    {
        $orderData = $this->order->order_data ?? [];
        
        if (($orderData['approval_status'] ?? '') !== 'approved') {
            session()->flash('error', 'Application must be approved before sending quotation.');
            return;
        }

        $this->validate([
            'quotationAmount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            $orderData = $this->order->order_data ?? [];
            
            $orderData['quotation_sent'] = true;
            $orderData['quotation_amount'] = $this->quotationAmount;
            $orderData['quotation_sent_at'] = now()->toDateTimeString();
            $orderData['quotation_sent_by'] = Auth::id();
            
            $this->order->update([
                'order_data' => $orderData,
                'admin_notes' => $this->adminNotes,
            ]);
        });

        session()->flash('success', 'Quotation sent successfully. Waiting for payment.');
        $this->closeQuotationModal();
        $this->mount($this->order->id);
    }

    public function recordPayment()
    {
        $orderData = $this->order->order_data ?? [];
        
        if (!($orderData['quotation_sent'] ?? false)) {
            session()->flash('error', 'Quotation must be sent before recording payment.');
            return;
        }

        $this->validate([
            'paymentAmount' => 'required|numeric|min:0',
            'paymentDate' => 'required|date',
            'paymentMethod' => 'required|in:cash,bank_transfer,check,card,other',
            'paymentReference' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () {
            $orderData = $this->order->order_data ?? [];
            
            $payments = $orderData['payments'] ?? [];
            $payments[] = [
                'amount' => $this->paymentAmount,
                'date' => $this->paymentDate,
                'method' => $this->paymentMethod,
                'reference' => $this->paymentReference,
                'recorded_by' => Auth::id(),
                'recorded_at' => now()->toDateTimeString(),
            ];
            
            $totalPaid = array_sum(array_column($payments, 'amount'));
            $quotationAmount = $orderData['quotation_amount'] ?? $orderData['total_upfront_cost'] ?? 0;
            
            $orderData['payments'] = $payments;
            $orderData['total_paid'] = $totalPaid;
            $orderData['payment_received'] = $totalPaid >= $quotationAmount;
            
            if ($orderData['payment_received']) {
                $orderData['payment_completed_at'] = now()->toDateTimeString();
            }
            
            $this->order->update([
                'order_data' => $orderData,
                'payment_completed' => $orderData['payment_received'],
                'paid_at' => $orderData['payment_received'] ? now() : null,
                'admin_notes' => $this->adminNotes,
            ]);
        });

        session()->flash('success', 'Payment recorded successfully.');
        $this->closePaymentModal();
        $this->mount($this->order->id);
    }

    public function issueContract()
    {
        $orderData = $this->order->order_data ?? [];
        
        if (!($orderData['payment_received'] ?? false)) {
            session()->flash('error', 'Payment must be received before issuing contract.');
            return;
        }

        $this->validate([
            'contractIssuedAt' => 'required|date',
        ]);

        DB::transaction(function () {
            $orderData = $this->order->order_data ?? [];
            
            $orderData['contract_issued'] = true;
            $orderData['contract_issued_at'] = $this->contractIssuedAt;
            $orderData['contract_issued_by'] = Auth::id();
            
            $this->order->update([
                'order_data' => $orderData,
                'admin_notes' => $this->adminNotes,
            ]);
        });

        session()->flash('success', 'Contract issued successfully. You can now start the lease.');
        $this->closeContractModal();
        $this->mount($this->order->id);
    }

    public function startLease()
    {
        $orderData = $this->order->order_data ?? [];
        
        if (!($orderData['contract_issued'] ?? false)) {
            session()->flash('error', 'Contract must be issued before starting the lease.');
            return;
        }

        $this->validate([
            'leaseStartDate' => 'required|date',
        ]);

        DB::transaction(function () {
            $orderData = $this->order->order_data ?? [];
            
            $orderData['lease_started'] = true;
            $orderData['lease_started_at'] = $this->leaseStartDate;
            $orderData['lease_started_by'] = Auth::id();
            
            // Calculate lease end date
            $leaseTermMonths = $orderData['lease_term_months'] ?? 36;
            $orderData['lease_end_date'] = date('Y-m-d', strtotime($this->leaseStartDate . " +{$leaseTermMonths} months"));
            
            // Initialize payment tracking
            $orderData['payments_made'] = [];
            $orderData['total_payments_made'] = 0;
            $orderData['current_mileage'] = 0;
            
            $this->order->update([
                'order_data' => $orderData,
                'admin_notes' => $this->adminNotes,
            ]);
            
            // Mark lease car as active (or keep reserved, depends on business logic)
            if (isset($this->lease)) {
                // Optionally update status - you may want to keep it as 'reserved' or change to 'active'
                // $this->lease->update(['status' => 'active']);
            }
        });

        session()->flash('success', 'Lease started successfully. Monitoring will now track payments and mileage.');
        $this->closeStartLeaseModal();
        $this->mount($this->order->id);
    }

    public function processReturn()
    {
        $orderData = $this->order->order_data ?? [];
        
        if (!($orderData['lease_started'] ?? false)) {
            session()->flash('error', 'Lease must be started before processing return.');
            return;
        }

        $this->validate([
            'returnDate' => 'required|date',
            'returnReason' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () {
            $orderData = $this->order->order_data ?? [];
            
            $orderData['return_requested'] = true;
            $orderData['return_requested_at'] = now()->toDateTimeString();
            $orderData['return_date'] = $this->returnDate;
            $orderData['return_reason'] = $this->returnReason;
            $orderData['lease_terminated'] = true;
            $orderData['lease_terminated_at'] = $this->returnDate;
            $orderData['terminated_by'] = Auth::id();
            
            // Calculate final amounts (excess mileage, damages, etc.)
            $currentMileage = $orderData['current_mileage'] ?? 0;
            $mileageLimitPerYear = $orderData['mileage_limit_per_year'] ?? 15000;
            $leaseTermMonths = $orderData['lease_term_months'] ?? 36;
            $allowedMileage = ($mileageLimitPerYear * $leaseTermMonths) / 12;
            $excessMileage = max(0, $currentMileage - $allowedMileage);
            $excessMileageCharge = $orderData['excess_mileage_charge'] ?? 0.25;
            $orderData['excess_mileage'] = $excessMileage;
            $orderData['excess_mileage_fee'] = $excessMileage * $excessMileageCharge;
            
            $this->order->update([
                'status' => OrderStatus::COMPLETED->value,
                'completed_at' => now(),
                'order_data' => $orderData,
                'admin_notes' => $this->adminNotes,
            ]);
            
            // Release lease car (make available again)
            if ($this->lease) {
                $this->lease->update([
                    'status' => 'active',
                ]);
            }
        });

        session()->flash('success', 'Lease return processed successfully. Contract terminated and vehicle released.');
        $this->closeReturnModal();
        $this->mount($this->order->id);
    }

    public function recordMonthlyPayment()
    {
        $orderData = $this->order->order_data ?? [];
        
        if (!($orderData['lease_started'] ?? false)) {
            session()->flash('error', 'Lease must be started before recording monthly payments.');
            return;
        }

        $this->validate([
            'paymentAmount' => 'required|numeric|min:0',
            'paymentDate' => 'required|date',
            'paymentMethod' => 'required|in:cash,bank_transfer,check,card,other',
            'paymentReference' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () {
            $orderData = $this->order->order_data ?? [];
            
            $monthlyPayments = $orderData['payments_made'] ?? [];
            $monthlyPayments[] = [
                'amount' => $this->paymentAmount,
                'date' => $this->paymentDate,
                'method' => $this->paymentMethod,
                'reference' => $this->paymentReference,
                'recorded_by' => Auth::id(),
                'recorded_at' => now()->toDateTimeString(),
            ];
            
            $totalPayments = array_sum(array_column($monthlyPayments, 'amount'));
            $monthlyPayment = $orderData['monthly_payment'] ?? 0;
            $leaseTermMonths = $orderData['lease_term_months'] ?? 36;
            $expectedTotal = $monthlyPayment * $leaseTermMonths;
            
            $orderData['payments_made'] = $monthlyPayments;
            $orderData['total_payments_made'] = $totalPayments;
            $orderData['payments_remaining'] = max(0, $expectedTotal - $totalPayments);
            
            $this->order->update([
                'order_data' => $orderData,
                'admin_notes' => $this->adminNotes,
            ]);
        });

        session()->flash('success', 'Monthly payment recorded successfully.');
        $this->closePaymentModal();
        $this->mount($this->order->id);
    }

    public function updateMileage()
    {
        $orderData = $this->order->order_data ?? [];
        
        if (!($orderData['lease_started'] ?? false)) {
            session()->flash('error', 'Lease must be started before updating mileage.');
            return;
        }

        $this->validate([
            'currentMileage' => 'required|integer|min:0',
        ]);

        $orderData['current_mileage'] = $this->currentMileage;
        
        // Check for excess mileage
        $mileageLimitPerYear = $orderData['mileage_limit_per_year'] ?? 15000;
        $leaseTermMonths = $orderData['lease_term_months'] ?? 36;
        $allowedMileage = ($mileageLimitPerYear * $leaseTermMonths) / 12;
        
        if ($this->currentMileage > $allowedMileage) {
            $excessMileage = $this->currentMileage - $allowedMileage;
            $excessMileageCharge = $orderData['excess_mileage_charge'] ?? 0.25;
            $orderData['excess_mileage'] = $excessMileage;
            $orderData['excess_mileage_fee'] = $excessMileage * $excessMileageCharge;
        } else {
            // Reset excess mileage if within limit
            $orderData['excess_mileage'] = 0;
            $orderData['excess_mileage_fee'] = 0;
        }
        
        $orderData['mileage_updated_at'] = now()->toDateTimeString();
        $orderData['mileage_updated_by'] = Auth::id();
        
        $this->order->update([
            'order_data' => $orderData,
            'admin_notes' => $this->adminNotes,
        ]);

        session()->flash('success', 'Mileage updated successfully.');
        $this->currentMileage = ''; // Reset the input field
        $this->mount($this->order->id);
    }

    public function saveNotes()
    {
        $this->order->update([
            'admin_notes' => $this->adminNotes,
        ]);

        session()->flash('success', 'Notes saved successfully.');
    }

    public function downloadDocument($documentType)
    {
        $orderData = $this->order->order_data ?? [];
        $documents = $orderData['documents'] ?? [];
        
        if (!isset($documents[$documentType])) {
            session()->flash('error', 'Document not found.');
            return;
        }
        
        $filePath = $documents[$documentType];
        
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath);
        }
        
        session()->flash('error', 'Document file not found.');
    }

    public function render()
    {
        return view('livewire.admin.leasing-order-detail');
    }
}

