<?php

namespace App\Livewire\Customer;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\VehicleLease;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeasingApplicationModal extends Component
{
    use WithFileUploads;

    public $show = false;
    public $leaseId;
    public $lease;
    
    // Personal Information
    public $full_name = '';
    public $email = '';
    public $phone = '';
    public $date_of_birth = '';
    public $address = '';
    public $city = '';
    public $postal_code = '';
    
    // Financial Information
    public $monthly_income = '';
    public $employment_status = '';
    public $employer_name = '';
    public $employment_months = '';
    public $credit_score = '';
    public $current_lease = false;
    
    // Documents
    public $id_document;
    public $proof_of_income;
    public $proof_of_address;
    public $driving_license;
    public $additional_documents = [];
    
    // Temporary previews
    public $tempIdDocument;
    public $tempProofOfIncome;
    public $tempProofOfAddress;
    public $tempDrivingLicense;
    public $tempAdditionalDocuments = [];
    
    public $notes = '';
    public $agreeToTerms = false;
    public $agreeToCreditCheck = false;

    #[On('open-leasing-modal')]
    public function open(...$params)
    {
        // Extract leaseId from event parameters
        // Livewire 3 passes event data as parameters
        $leaseId = null;
        
        if (!empty($params)) {
            $firstParam = $params[0];
            if (is_numeric($firstParam)) {
                $leaseId = (int) $firstParam;
            } elseif (is_array($firstParam)) {
                $leaseId = $firstParam['leaseId'] ?? $firstParam[0] ?? null;
                if ($leaseId) {
                    $leaseId = (int) $leaseId;
                }
            } elseif (is_object($firstParam)) {
                $leaseId = $firstParam->leaseId ?? null;
                if ($leaseId) {
                    $leaseId = (int) $leaseId;
                }
            }
        }
        
        if (!$leaseId || $leaseId <= 0) {
            \Log::error('Leasing modal: No leaseId provided', ['params' => $params, 'first_param' => $params[0] ?? null]);
            session()->flash('error', 'Invalid lease ID.');
            return;
        }
        
        \Log::info('Leasing modal opening', ['leaseId' => $leaseId, 'params' => $params]);

        if (!Auth::check()) {
            session()->flash('error', 'Please login to apply for leasing.');
            return redirect()->route('login');
        }

        try {
            $this->leaseId = $leaseId;
            $this->lease = VehicleLease::with('entity')->findOrFail($this->leaseId);
        } catch (\Exception $e) {
            session()->flash('error', 'Lease not found.');
            \Log::error('Leasing modal error: ' . $e->getMessage());
            return;
        }
        
        // Pre-fill user information if available
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
        
        // Always show modal - validation will happen on submit
        $this->show = true;
        $this->resetDocuments();
        
        // Debug: Log that we're setting show to true
        \Log::info('Modal show set to true', ['show' => $this->show, 'leaseId' => $this->leaseId, 'lease' => $this->lease ? $this->lease->id : null]);
        
        // Force a re-render
        $this->js('window.dispatchEvent(new CustomEvent("leasing-modal-opened"))');
    }
    
    public function close()
    {
        $this->show = false;
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
        $this->credit_score = '';
        $this->current_lease = false;
        $this->notes = '';
        $this->agreeToTerms = false;
        $this->agreeToCreditCheck = false;
        $this->resetDocuments();
    }
    
    public function resetDocuments()
    {
        $this->id_document = null;
        $this->proof_of_income = null;
        $this->proof_of_address = null;
        $this->driving_license = null;
        $this->additional_documents = [];
        $this->tempIdDocument = null;
        $this->tempProofOfIncome = null;
        $this->tempProofOfAddress = null;
        $this->tempDrivingLicense = null;
        $this->tempAdditionalDocuments = [];
    }

    public function submit()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to apply for leasing.');
            return redirect()->route('login');
        }

        $this->validate([
            // Personal Information
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today|before:-18 years',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            
            // Financial Information
            'monthly_income' => 'required|numeric|min:' . ($this->lease->min_monthly_income ?? 0),
            'employment_status' => 'required|in:employed,self_employed,unemployed,retired,student',
            'employer_name' => 'required_if:employment_status,employed,self_employed|string|max:255',
            'employment_months' => 'nullable|integer|min:0',
            'credit_score' => 'nullable|integer|min:300|max:850',
            'current_lease' => 'boolean',
            
            // Documents
            'id_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'proof_of_income' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'proof_of_address' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'driving_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'additional_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            
            // Agreements
            'agreeToTerms' => 'accepted',
            'agreeToCreditCheck' => 'accepted',
        ], [
            'date_of_birth.before' => 'You must be at least 18 years old to apply for leasing.',
            'monthly_income.min' => 'Minimum monthly income required is $' . number_format($this->lease->min_monthly_income ?? 0),
            'id_document.required' => 'ID document is required for application.',
            'proof_of_income.required' => 'Proof of income is required for application.',
            'proof_of_address.required' => 'Proof of address is required for application.',
            'driving_license.required' => 'Driving license is required for application.',
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
                'vehicle_id' => null, // Lease is independent
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
                    
                    // Lease Terms
                    'monthly_payment' => $this->lease->monthly_payment,
                    'lease_term_months' => $this->lease->lease_term_months,
                    'down_payment' => $this->lease->down_payment,
                    'security_deposit' => $this->lease->security_deposit,
                    'acquisition_fee' => $this->lease->acquisition_fee ?? 0,
                    'total_upfront_cost' => $totalUpfront,
                    'total_lease_cost' => $totalLeaseCost,
                    'mileage_limit_per_year' => $this->lease->mileage_limit_per_year,
                    'excess_mileage_charge' => $this->lease->excess_mileage_charge,
                    
                    // Applicant Information
                    'full_name' => $this->full_name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'date_of_birth' => $this->date_of_birth,
                    'address' => $this->address,
                    'city' => $this->city,
                    'postal_code' => $this->postal_code,
                    
                    // Financial Information
                    'monthly_income' => $this->monthly_income,
                    'employment_status' => $this->employment_status,
                    'employer_name' => $this->employer_name,
                    'employment_months' => $this->employment_months,
                    'credit_score' => $this->credit_score,
                    'current_lease' => $this->current_lease,
                    
                    // Documents
                    'documents' => $documents,
                    
                    // Workflow Status
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
            
            $this->dispatch('order-created');
            $this->close();

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit application. Please try again. ' . $e->getMessage());
            \Log::error('Leasing application error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        if ($this->show && $this->leaseId && !$this->lease) {
            $this->lease = VehicleLease::with('entity')->find($this->leaseId);
        }

        return view('livewire.customer.leasing-application-modal');
    }
}

