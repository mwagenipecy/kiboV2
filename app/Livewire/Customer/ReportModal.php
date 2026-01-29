<?php

namespace App\Livewire\Customer;

use App\Models\Report;
use Livewire\Component;
use Livewire\Attributes\Validate;

class ReportModal extends Component
{
    public $show = false;
    public $section = 'vehicle';
    public $reportableId = null;
    public $reportableType = null;

    #[Validate('required|string|max:255')]
    public $reason = '';

    #[Validate('nullable|string|max:2000')]
    public $description = '';

    #[Validate('nullable|string|max:255')]
    public $reporterName = '';

    #[Validate('nullable|email|max:255')]
    public $reporterEmail = '';

    protected $listeners = ['openReportModal' => 'open'];

    public function mount($section = 'vehicle', $reportableId = null, $reportableType = null)
    {
        $this->section = $section;
        $this->reportableId = $reportableId;
        $this->reportableType = $reportableType;
        
        if (auth()->check()) {
            $user = auth()->user();
            $this->reporterName = $user->name;
            $this->reporterEmail = $user->email;
        }
    }

    public function open($data = [])
    {
        if (isset($data['section'])) {
            $this->section = $data['section'];
        }
        if (isset($data['reportableId'])) {
            $this->reportableId = $data['reportableId'];
        }
        if (isset($data['reportableType'])) {
            $this->reportableType = $data['reportableType'];
        }
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->reset(['reason', 'description']);
    }

    public function submit()
    {
        $this->validate();

        Report::create([
            'section' => $this->section,
            'reportable_id' => $this->reportableId,
            'reportable_type' => $this->reportableType,
            'reporter_id' => auth()->id(),
            'reporter_email' => $this->reporterEmail,
            'reporter_name' => $this->reporterName,
            'reason' => $this->reason,
            'description' => $this->description,
            'status' => 'pending',
        ]);

        session()->flash('report_success', 'Thank you for your report. We will review it shortly.');
        $this->close();
        
        $this->dispatch('report-submitted');
    }

    public function render()
    {
        return view('livewire.customer.report-modal');
    }
}
