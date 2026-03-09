<?php

namespace App\Livewire\Customer;

use App\Jobs\SendComplaintReceivedEmail;
use App\Models\Complaint;
use Livewire\Component;

class ComplaintsSection extends Component
{
    public $activeTab = 'submit'; // submit | track

    // Submit form
    public $name = '';
    public $email = '';
    public $phone = '';
    public $subject = '';
    public $message = '';
    public $category = 'general';

    // Track form – by tracking number only
    public $tracking_number = '';
    public $trackResults = null;
    public $trackError = null;

    public $submittedNumber = null;

    public function submitComplaint()
    {
        $this->activeTab = 'submit';
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'category' => ['required', 'string', 'in:general,service,product,payment,other'],
        ]);

        $complaintNumber = Complaint::generateComplaintNumber();
        $complaint = Complaint::create([
            'complaint_number' => $complaintNumber,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'subject' => $this->subject,
            'message' => $this->message,
            'category' => $this->category,
            'status' => Complaint::STATUS_PENDING,
            'user_id' => auth()->id(),
        ]);

        SendComplaintReceivedEmail::dispatch($complaint->id);

        $this->submittedNumber = $complaintNumber;
        $this->reset(['name', 'email', 'phone', 'subject', 'message', 'category']);
        $this->trackResults = null;
        $this->trackError = null;
    }

    public function trackComplaints()
    {
        $this->activeTab = 'track';
        $this->validate([
            'tracking_number' => ['required', 'string', 'max:32'],
        ]);

        $this->trackError = null;
        $this->trackResults = null;

        $number = strtoupper(trim($this->tracking_number));
        $complaint = Complaint::where('complaint_number', $number)->first();

        if ($complaint) {
            $this->trackResults = collect([$complaint]);
        } else {
            $this->trackError = __('No complaint found for this tracking number. Please check and try again.');
        }
    }

    public function switchToSubmit()
    {
        $this->activeTab = 'submit';
        $this->submittedNumber = null;
        $this->trackError = null;
        $this->trackResults = null;
    }

    public function switchToTrack()
    {
        $this->activeTab = 'track';
        $this->submittedNumber = null;
    }

    public function render()
    {
        return view('livewire.customer.complaints-section');
    }
}
