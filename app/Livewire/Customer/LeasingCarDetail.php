<?php

namespace App\Livewire\Customer;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\VehicleLease;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LeasingCarDetail extends Component
{
    public $lease;
    public $leaseId;
    public $isSaved = false;
    public $expandedSections = [];
    public $showImageModal = false;
    public $currentImage = null;
    public $allImages = [];
    public $existingOrder = null;
    public $canApply = true;
    public $applicationStatus = null;

    public function mount($id)
    {
        $this->leaseId = $id;
        $this->lease = VehicleLease::with(['entity'])
            ->active()
            ->findOrFail($id);
        
        // Check if lease is saved
        $savedLeases = session()->get('saved_leases', []);
        $this->isSaved = in_array($id, $savedLeases);
        
        // Check for existing applications if user is logged in
        if (Auth::check()) {
            $this->checkExistingApplication();
        }
        
        // Prepare all images for gallery
        $this->allImages = [];
        if ($this->lease->image_front) {
            $this->allImages[] = $this->lease->image_front;
        }
        if ($this->lease->image_side) {
            $this->allImages[] = $this->lease->image_side;
        }
        if ($this->lease->image_back) {
            $this->allImages[] = $this->lease->image_back;
        }
        if ($this->lease->other_images && is_array($this->lease->other_images) && count($this->lease->other_images) > 0) {
            $this->allImages = array_merge($this->allImages, $this->lease->other_images);
        }
        
        // Initialize expanded sections
        $this->expandedSections = [
            'fullDescription' => false,
        ];
    }
    
    public function toggleSave()
    {
        $savedLeases = session()->get('saved_leases', []);
        
        if ($this->isSaved) {
            $savedLeases = array_diff($savedLeases, [$this->leaseId]);
            $this->isSaved = false;
        } else {
            $savedLeases[] = $this->leaseId;
            $this->isSaved = true;
        }
        
        session()->put('saved_leases', $savedLeases);
    }

    public function toggleSection($section)
    {
        $this->expandedSections[$section] = !$this->expandedSections[$section];
    }

    public function openImageModal($imageIndex)
    {
        $this->currentImage = $imageIndex;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->currentImage = null;
    }

    public function nextImage()
    {
        if ($this->currentImage !== null && count($this->allImages) > 0) {
            $this->currentImage = ($this->currentImage + 1) % count($this->allImages);
        }
    }

    public function previousImage()
    {
        if ($this->currentImage !== null && count($this->allImages) > 0) {
            $this->currentImage = ($this->currentImage - 1 + count($this->allImages)) % count($this->allImages);
        }
    }

    protected function checkExistingApplication()
    {
        if (!Auth::check()) {
            $this->canApply = true;
            return;
        }

        // Check for existing orders for this lease
        $existingOrders = Order::where('user_id', Auth::id())
            ->where('order_type', OrderType::LEASING_APPLICATION->value)
            ->where('order_data->lease_id', $this->leaseId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($existingOrders->isEmpty()) {
            $this->canApply = true;
            return;
        }

        // Get the most recent order
        $this->existingOrder = $existingOrders->first();
        
        // Determine if user can apply based on order status
        $status = $this->existingOrder->status;
        $orderData = $this->existingOrder->order_data ?? [];
        $approvalStatus = $orderData['approval_status'] ?? 'pending';
        $leaseStarted = $orderData['lease_started'] ?? false;
        $leaseTerminated = $orderData['lease_terminated'] ?? false;

        // User can only apply if:
        // 1. Order is rejected
        // 2. Order is completed AND lease is terminated
        // 3. No active/pending/approved orders without termination
        
        if ($status === OrderStatus::REJECTED) {
            $this->canApply = true;
            $this->applicationStatus = 'rejected';
        } elseif ($status === OrderStatus::COMPLETED && $leaseTerminated) {
            $this->canApply = true;
            $this->applicationStatus = 'completed';
        } elseif ($status === OrderStatus::PENDING) {
            $this->canApply = false;
            $this->applicationStatus = 'pending';
        } elseif ($status === OrderStatus::APPROVED && $approvalStatus === 'approved' && !$leaseStarted) {
            $this->canApply = false;
            $this->applicationStatus = 'approved';
        } elseif ($leaseStarted && !$leaseTerminated) {
            $this->canApply = false;
            $this->applicationStatus = 'active';
        } else {
            $this->canApply = false;
            $this->applicationStatus = 'processing';
        }
    }

    public function render()
    {
        return view('livewire.customer.leasing-car-detail')->layout('layouts.customer');
    }
}
