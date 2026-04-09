<?php

namespace App\Livewire\Customer;

use App\Mail\AgizaImportRequestReceived;
use App\Models\AgizaImportRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer', ['vehicleType' => 'agiza-import'])]
class AgizaImport extends Component
{
    public string $customerName = '';

    public string $customerEmail = '';

    public string $customerPhone = '';

    public ?string $vehicleLink = '';

    public bool $showSuccessModal = false;

    public bool $showErrorModal = false;

    public string $successMessage = '';

    public string $errorMessage = '';

    public string $requestNumber = '';

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
            $this->customerPhone = $user->phone ?? '';
        }
    }

    public function submit()
    {
        if (! Auth::check()) {
            $this->errorMessage = 'Please login to submit a request.';
            $this->showErrorModal = true;

            return;
        }

        $this->validate([
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => 'required|string|max:20',
            'vehicleLink' => 'required|url|max:500',
        ], [
            'vehicleLink.required' => 'Please provide the car listing link.',
            'vehicleLink.url' => 'Please provide a valid URL.',
        ]);

        try {
            $request = AgizaImportRequest::create([
                'request_number' => AgizaImportRequest::generateRequestNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'vehicle_make' => null,
                'vehicle_model' => null,
                'vehicle_year' => null,
                'vehicle_condition' => null,
                'vehicle_link' => $this->vehicleLink,
                'source_country' => null,
                'request_type' => 'with_link',
                'dealer_contact_info' => null,
                'estimated_price' => null,
                'price_currency' => 'USD',
                'special_requirements' => null,
                'customer_notes' => null,
                'documents' => [],
                'vehicle_images' => [],
                'status' => 'pending',
            ]);

            $this->requestNumber = $request->request_number;

            try {
                Mail::to($this->customerEmail)->send(new AgizaImportRequestReceived($request));
            } catch (\Exception $e) {
                \Log::error('Failed to send confirmation email: '.$e->getMessage());
            }

            $this->successMessage = 'Your import request has been submitted successfully! Our team will review it and get back to you shortly.';
            $this->showSuccessModal = true;
            $this->resetForm();

        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred while submitting your request. Please try again.';
            $this->showErrorModal = true;
            \Log::error('Agiza import submission error: '.$e->getMessage());
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->successMessage = '';
        $this->requestNumber = '';
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function resetForm()
    {
        $this->reset('vehicleLink');
    }

    public function render()
    {
        return view('livewire.customer.agiza-import');
    }
}
