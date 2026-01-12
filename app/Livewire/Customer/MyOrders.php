<?php

namespace App\Livewire\Customer;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer')]
class MyOrders extends Component
{
    use WithPagination;

    public $filterStatus = 'all';
    public $filterType = 'all';
    
    // Modal states
    public $showViewModal = false;
    public $showPaymentModal = false;
    public $showTerminationModal = false;
    public $selectedOrder = null;
    public $terminationReason = '';
    public $terminationDate = '';

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function viewOrder($orderId)
    {
        $this->selectedOrder = Order::with(['vehicle.make', 'vehicle.model', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);
        $this->showViewModal = true;
        // Close other modals if open
        $this->showPaymentModal = false;
        $this->showTerminationModal = false;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedOrder = null;
    }

    public function payOrder($orderId)
    {
        $this->selectedOrder = Order::with(['vehicle.make', 'vehicle.model'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->selectedOrder = null;
    }

    public function processPayment($paymentMethod)
    {
        if (!$this->selectedOrder) {
            return;
        }

        // For leasing applications, handle payment differently
        if ($this->selectedOrder->order_type === OrderType::LEASING_APPLICATION) {
            $orderData = $this->selectedOrder->order_data ?? [];
            $quotationAmount = $orderData['quotation_amount'] ?? $orderData['total_upfront_cost'] ?? 0;
            
            if (!$quotationAmount || $quotationAmount <= 0) {
                session()->flash('error', 'No quotation amount found. Please contact support.');
                return;
            }
            
            // Here you would integrate with payment gateway (Stripe, PayPal, etc.)
            // For now, we'll mark it as a simulation
            DB::transaction(function () use ($paymentMethod, $orderData, $quotationAmount) {
                $payments = $orderData['payments'] ?? [];
                $payments[] = [
                    'amount' => $quotationAmount,
                    'date' => now()->format('Y-m-d'),
                    'method' => $paymentMethod,
                    'reference' => 'PAY-' . strtoupper(uniqid()),
                    'recorded_at' => now()->toDateTimeString(),
                ];
                
                $totalPaid = array_sum(array_column($payments, 'amount'));
                
                $orderData['payments'] = $payments;
                $orderData['total_paid'] = $totalPaid;
                $orderData['payment_received'] = $totalPaid >= $quotationAmount;
                
                if ($orderData['payment_received']) {
                    $orderData['payment_completed_at'] = now()->toDateTimeString();
                }
                
                $this->selectedOrder->update([
                    'order_data' => $orderData,
                    'payment_completed' => $orderData['payment_received'],
                    'paid_at' => $orderData['payment_received'] ? now() : null,
                    'payment_method' => $paymentMethod,
                ]);
            });
            
            session()->flash('success', 'Payment recorded successfully! The dealer will review and issue your contract.');
        } else {
            // Mark order as paid for other order types
            $this->selectedOrder->markAsPaid($paymentMethod, 'PAY-' . strtoupper(uniqid()));
            session()->flash('success', 'Payment successful! Your order has been confirmed.');
        }
        
        $this->closePaymentModal();
        $this->viewOrder($this->selectedOrder->id); // Refresh the order view
    }

    public function downloadQuotation()
    {
        if (!$this->selectedOrder || $this->selectedOrder->order_type !== OrderType::LEASING_APPLICATION) {
            session()->flash('error', 'Quotation not available for this order.');
            return;
        }
        
        $orderData = $this->selectedOrder->order_data ?? [];
        $isApproved = in_array($this->selectedOrder->status, [OrderStatus::APPROVED, OrderStatus::PROCESSING, OrderStatus::COMPLETED]);
        $hasQuotationAmount = isset($orderData['quotation_amount']) && $orderData['quotation_amount'] > 0;
        $hasUpfrontCost = isset($orderData['total_upfront_cost']) && $orderData['total_upfront_cost'] > 0;
        // Allow download if quotation sent, order approved, or has quotation/total upfront cost
        $hasQuotation = ($orderData['quotation_sent'] ?? false) || $isApproved || $hasQuotationAmount || $hasUpfrontCost;
        
        if (!$hasQuotation) {
            session()->flash('error', 'Quotation has not been generated yet. Please wait for the dealer to send it.');
            return;
        }
        
        // Generate quotation PDF (simplified - you'd use a PDF library like DomPDF or Snappy)
        // For now, return a response that indicates the quotation
        return response()->streamDownload(function () use ($orderData) {
            $quotationDate = isset($orderData['quotation_sent_at']) ? \Carbon\Carbon::parse($orderData['quotation_sent_at'])->format('M d, Y') : ($this->selectedOrder->processed_at ? $this->selectedOrder->processed_at->format('M d, Y') : now()->format('M d, Y'));
            $vehicleTitle = $orderData['vehicle_title'] ?? 'N/A';
            $vehicleMake = $orderData['vehicle_make'] ?? '';
            $vehicleModel = $orderData['vehicle_model'] ?? '';
            $vehicleYear = $orderData['vehicle_year'] ?? '';
            $leaseTerm = $orderData['lease_term_months'] ?? 0;
            $monthlyPayment = $orderData['monthly_payment'] ?? 0;
            $downPayment = $orderData['down_payment'] ?? 0;
            $securityDeposit = $orderData['security_deposit'] ?? 0;
            $acquisitionFee = $orderData['acquisition_fee'] ?? 0;
            $totalUpfront = $orderData['total_upfront_cost'] ?? ($downPayment + $securityDeposit + $acquisitionFee);
            $quotationAmount = $orderData['quotation_amount'] ?? $totalUpfront;
            $leaseId = $orderData['lease_id'] ?? 'N/A';
            
            echo "LEASING QUOTATION / INVOICE\n";
            echo "===========================\n\n";
            echo "Invoice Number: {$this->selectedOrder->order_number}\n";
            echo "Date: {$quotationDate}\n";
            echo "Lease ID: {$leaseId}\n\n";
            echo "CUSTOMER INFORMATION\n";
            echo "-------------------\n";
            echo "Name: " . ($orderData['full_name'] ?? $this->selectedOrder->user->name) . "\n";
            echo "Email: " . ($orderData['email'] ?? $this->selectedOrder->user->email) . "\n";
            echo "Phone: " . ($orderData['phone'] ?? 'N/A') . "\n\n";
            echo "VEHICLE INFORMATION\n";
            echo "------------------\n";
            echo "Vehicle: {$vehicleTitle}\n";
            if ($vehicleYear) echo "Year: {$vehicleYear}\n";
            if ($vehicleMake) echo "Make: {$vehicleMake}\n";
            if ($vehicleModel) echo "Model: {$vehicleModel}\n\n";
            echo "LEASE TERMS\n";
            echo "-----------\n";
            echo "Lease Term: {$leaseTerm} months\n";
            echo "Monthly Payment: $" . number_format($monthlyPayment, 2) . "\n\n";
            echo "BREAKDOWN OF COSTS\n";
            echo "------------------\n";
            echo "Down Payment:           $" . str_pad(number_format($downPayment, 2), 15, ' ', STR_PAD_LEFT) . "\n";
            echo "Security Deposit:       $" . str_pad(number_format($securityDeposit, 2), 15, ' ', STR_PAD_LEFT) . "\n";
            if ($acquisitionFee > 0) {
                echo "Acquisition Fee:        $" . str_pad(number_format($acquisitionFee, 2), 15, ' ', STR_PAD_LEFT) . "\n";
            }
            echo "                      " . str_repeat('-', 21) . "\n";
            echo "TOTAL AMOUNT DUE:       $" . str_pad(number_format($quotationAmount, 2), 15, ' ', STR_PAD_LEFT) . "\n\n";
            echo "==========================================\n";
            echo "This document serves as both a quotation and invoice.\n";
            echo "Please make payment to proceed with your lease.\n";
        }, 'invoice-' . $this->selectedOrder->order_number . '.txt', [
            'Content-Type' => 'text/plain',
        ]);
    }

    public function downloadContract()
    {
        if (!$this->selectedOrder || $this->selectedOrder->order_type !== OrderType::LEASING_APPLICATION) {
            session()->flash('error', 'Contract not available for this order.');
            return;
        }
        
        $orderData = $this->selectedOrder->order_data ?? [];
        
        if (!($orderData['contract_issued'] ?? false)) {
            session()->flash('error', 'Contract has not been issued yet. Please wait for the dealer to issue it.');
            return;
        }
        
        // Check if contract file exists in documents
        if (isset($orderData['documents']['contract'])) {
            $contractPath = $orderData['documents']['contract'];
            if (Storage::disk('public')->exists($contractPath)) {
                return Storage::disk('public')->download($contractPath);
            }
        }
        
        // Generate contract PDF (simplified - you'd use a PDF library)
        return response()->streamDownload(function () use ($orderData) {
            $contractDate = $orderData['contract_issued_at'] ?? now()->format('Y-m-d');
            $fullName = $orderData['full_name'] ?? 'N/A';
            $email = $orderData['email'] ?? 'N/A';
            $phone = $orderData['phone'] ?? 'N/A';
            $vehicleTitle = $orderData['vehicle_title'] ?? 'N/A';
            $leaseTerm = $orderData['lease_term_months'] ?? 0;
            $leaseStart = $orderData['lease_started_at'] ?? 'N/A';
            $leaseEnd = $orderData['lease_end_date'] ?? 'N/A';
            $monthlyPayment = $orderData['monthly_payment'] ?? 0;
            $mileageLimit = $orderData['mileage_limit_per_year'] ?? 0;
            $excessCharge = $orderData['excess_mileage_charge'] ?? 0;
            
            echo "LEASE CONTRACT\n";
            echo "==============\n\n";
            echo "Contract Number: CNT-" . $this->selectedOrder->order_number . "\n";
            echo "Issue Date: {$contractDate}\n\n";
            echo "Lessee: {$fullName}\n";
            echo "Email: {$email}\n";
            echo "Phone: {$phone}\n\n";
            echo "Vehicle: {$vehicleTitle}\n";
            echo "Lease Term: {$leaseTerm} months\n";
            echo "Lease Start Date: {$leaseStart}\n";
            echo "Lease End Date: {$leaseEnd}\n\n";
            echo "Monthly Payment: $" . number_format($monthlyPayment, 2) . "\n";
            echo "Mileage Limit per Year: " . number_format($mileageLimit, 0) . " km\n";
            echo "Excess Mileage Charge: $" . number_format($excessCharge, 2) . " per km\n\n";
            echo "TERMS AND CONDITIONS:\n";
            echo "1. Lessee agrees to make monthly payments on time.\n";
            echo "2. Vehicle must be maintained in good condition.\n";
            echo "3. Excess mileage charges apply if limit is exceeded.\n";
            echo "4. Early termination may incur fees.\n";
        }, 'contract-' . $this->selectedOrder->order_number . '.txt', [
            'Content-Type' => 'text/plain',
        ]);
    }

    public function openTerminationModal()
    {
        if (!$this->selectedOrder || $this->selectedOrder->order_type !== OrderType::LEASING_APPLICATION) {
            session()->flash('error', 'Termination is only available for leasing applications.');
            return;
        }
        
        $orderData = $this->selectedOrder->order_data ?? [];
        
        if (!($orderData['lease_started'] ?? false)) {
            session()->flash('error', 'Lease must be started before requesting termination.');
            return;
        }
        
        if ($orderData['lease_terminated'] ?? false) {
            session()->flash('error', 'Lease has already been terminated.');
            return;
        }
        
        $this->terminationDate = now()->format('Y-m-d');
        $this->terminationReason = '';
        $this->showTerminationModal = true;
    }

    public function closeTerminationModal()
    {
        $this->showTerminationModal = false;
        $this->terminationReason = '';
        $this->terminationDate = '';
    }

    public function requestTermination()
    {
        if (!$this->selectedOrder) {
            return;
        }

        $this->validate([
            'terminationDate' => 'required|date|after_or_equal:today',
            'terminationReason' => 'required|string|min:10|max:1000',
        ], [
            'terminationDate.required' => 'Please select a termination date.',
            'terminationDate.after_or_equal' => 'Termination date must be today or in the future.',
            'terminationReason.required' => 'Please provide a reason for termination.',
            'terminationReason.min' => 'Reason must be at least 10 characters.',
        ]);

        DB::transaction(function () {
            $orderData = $this->selectedOrder->order_data ?? [];
            
            $orderData['return_requested'] = true;
            $orderData['return_requested_at'] = now()->toDateTimeString();
            $orderData['return_date'] = $this->terminationDate;
            $orderData['return_reason'] = $this->terminationReason;
            
            $this->selectedOrder->update([
                'order_data' => $orderData,
                'status' => OrderStatus::PROCESSING, // Changed to processing for admin review
            ]);
        });

        session()->flash('success', 'Termination request submitted successfully. The dealer will review your request and contact you soon.');
        $this->closeTerminationModal();
        $this->viewOrder($this->selectedOrder->id); // Refresh the order view
    }

    public function render()
    {
        $query = Order::with(['vehicle.make', 'vehicle.model'])
            ->where('user_id', Auth::id())
            ->latest();

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterType !== 'all') {
            $query->where('order_type', $this->filterType);
        }

        $orders = $query->paginate(10);

        // Get counts for filters
        $statusCounts = [
            'all' => Order::where('user_id', Auth::id())->count(),
            'pending' => Order::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'processing' => Order::where('user_id', Auth::id())->where('status', 'processing')->count(),
            'approved' => Order::where('user_id', Auth::id())->where('status', 'approved')->count(),
            'completed' => Order::where('user_id', Auth::id())->where('status', 'completed')->count(),
        ];

        return view('livewire.customer.my-orders', [
            'orders' => $orders,
            'statusCounts' => $statusCounts,
        ]);
    }
}

