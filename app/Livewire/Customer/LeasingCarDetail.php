<?php

namespace App\Livewire\Customer;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\VehicleLease;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeasingCarDetail extends Component
{
    use WithFileUploads;

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

    // Modal state
    public $showModal = false;
    
    // Form fields
    public $full_name = '';
    public $email = '';
    public $phone = '';
    public $date_of_birth = '';
    public $address = '';
    public $city = '';
    public $postal_code = '';
    public $monthly_income = '';
    public $employment_status = '';
    public $employer_name = '';
    public $employment_months = '';
    public $current_lease = false;
    public $id_document;
    public $proof_of_income;
    public $proof_of_address;
    public $driving_license;
    public $additional_documents = [];
    public $notes = '';
    public $agreeToTerms = false;
    public $agreeToCreditCheck = false;

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

    // Modal methods
    public function openModal()
    {
        \Log::info('openModal called', ['leaseId' => $this->leaseId, 'auth' => Auth::check()]);
        
        if (!Auth::check()) {
            session()->flash('error', 'Please login to apply for leasing.');
            return;
        }
        
        // Pre-fill user information
        $user = Auth::user();
        if ($user->customer) {
            $this->full_name = $user->customer->first_name . ' ' . $user->customer->last_name;
            $this->email = $user->email;
            $this->phone = $user->customer->phone ?? '';
            $this->address = $user->customer->address ?? '';
        } else {
            $this->full_name = $user->name;
            $this->email = $user->email;
        }
        
        $this->showModal = true;
        \Log::info('showModal set to true', ['showModal' => $this->showModal]);
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->full_name = '';
        $this->email = '';
        $this->phone = '';
        $this->date_of_birth = '';
        $this->address = '';
        $this->city = '';
        $this->postal_code = '';
        $this->monthly_income = '';
        $this->employment_status = '';
        $this->employer_name = '';
        $this->employment_months = '';
        $this->current_lease = false;
        $this->id_document = null;
        $this->proof_of_income = null;
        $this->proof_of_address = null;
        $this->driving_license = null;
        $this->additional_documents = [];
        $this->notes = '';
        $this->agreeToTerms = false;
        $this->agreeToCreditCheck = false;
    }

    public function submitApplication()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to apply for leasing.');
            return redirect()->route('login');
        }

        $this->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today|before:-18 years',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'monthly_income' => 'required|numeric|min:' . ($this->lease->min_monthly_income ?? 0),
            'employment_status' => 'required|in:employed,self_employed,unemployed,retired,student',
            'employer_name' => 'required_if:employment_status,employed,self_employed|string|max:255',
            'employment_months' => 'nullable|integer|min:0',
            'current_lease' => 'boolean',
            'id_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'proof_of_income' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'proof_of_address' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'driving_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'additional_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'agreeToTerms' => 'accepted',
            'agreeToCreditCheck' => 'accepted',
        ], [
            'date_of_birth.before' => 'You must be at least 18 years old to apply for leasing.',
            'monthly_income.min' => 'Minimum monthly income required is $' . number_format($this->lease->min_monthly_income ?? 0),
        ]);

        try {
            // Upload documents
            $documents = [];
            
            if ($this->id_document) {
                $documents['id_document'] = $this->id_document->store('leasing-documents/' . Auth::id(), 'public');
            }
            
            if ($this->proof_of_income) {
                $documents['proof_of_income'] = $this->proof_of_income->store('leasing-documents/' . Auth::id(), 'public');
            }
            
            if ($this->proof_of_address) {
                $documents['proof_of_address'] = $this->proof_of_address->store('leasing-documents/' . Auth::id(), 'public');
            }
            
            if ($this->driving_license) {
                $documents['driving_license'] = $this->driving_license->store('leasing-documents/' . Auth::id(), 'public');
            }
            
            if (!empty($this->additional_documents)) {
                $additionalDocs = [];
                foreach ($this->additional_documents as $doc) {
                    if (is_object($doc)) {
                        $additionalDocs[] = $doc->store('leasing-documents/' . Auth::id(), 'public');
                    }
                }
                $documents['additional_documents'] = $additionalDocs;
            }

            // Calculate total upfront cost
            $totalUpfront = $this->lease->down_payment + $this->lease->security_deposit + ($this->lease->acquisition_fee ?? 0);
            $totalLeaseCost = ($this->lease->monthly_payment * $this->lease->lease_term_months) + $totalUpfront;

            // Create the leasing application order
            Order::create([
                'user_id' => Auth::id(),
                'vehicle_id' => null,
                'order_type' => OrderType::LEASING_APPLICATION,
                'status' => OrderStatus::PENDING,
                'customer_notes' => $this->notes,
                'order_data' => [
                    'lease_id' => $this->lease->id,
                    'lease_title' => $this->lease->lease_title,
                    'vehicle_title' => $this->lease->vehicle_title,
                    'vehicle_make' => $this->lease->vehicle_make,
                    'vehicle_model' => $this->lease->vehicle_model,
                    'vehicle_year' => $this->lease->vehicle_year,
                    'entity_id' => $this->lease->entity_id,
                    'entity_name' => $this->lease->entity->name ?? null,
                    'monthly_payment' => $this->lease->monthly_payment,
                    'lease_term_months' => $this->lease->lease_term_months,
                    'down_payment' => $this->lease->down_payment,
                    'security_deposit' => $this->lease->security_deposit,
                    'acquisition_fee' => $this->lease->acquisition_fee ?? 0,
                    'total_upfront_cost' => $totalUpfront,
                    'total_lease_cost' => $totalLeaseCost,
                    'mileage_limit_per_year' => $this->lease->mileage_limit_per_year,
                    'excess_mileage_charge' => $this->lease->excess_mileage_charge,
                    'full_name' => $this->full_name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'date_of_birth' => $this->date_of_birth,
                    'address' => $this->address,
                    'city' => $this->city,
                    'postal_code' => $this->postal_code,
                    'monthly_income' => $this->monthly_income,
                    'employment_status' => $this->employment_status,
                    'employer_name' => $this->employer_name,
                    'employment_months' => $this->employment_months,
                    'current_lease' => $this->current_lease,
                    'documents' => $documents,
                    'approval_status' => 'pending',
                    'quotation_sent' => false,
                    'payment_received' => false,
                    'contract_issued' => false,
                    'lease_started' => false,
                    'return_requested' => false,
                    'lease_terminated' => false,
                ],
            ]);

            session()->flash('success', 'Your leasing application has been submitted successfully! We will review your application and contact you soon.');
            
            $this->closeModal();
            $this->checkExistingApplication();

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit application. Please try again. ' . $e->getMessage());
            \Log::error('Leasing application error: ' . $e->getMessage());
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
