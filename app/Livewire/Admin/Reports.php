<?php

namespace App\Livewire\Admin;

use App\Models\Report;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Reports extends Component
{
    use WithPagination;

    public $selectedSection = 'all';
    public $selectedStatus = 'all';
    public $search = '';
    public $showReportModal = false;
    public $selectedReport = null;
    public $adminNotes = '';

    protected $queryString = [
        'selectedSection' => ['except' => 'all'],
        'selectedStatus' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedSection()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus()
    {
        $this->resetPage();
    }

    public function viewReport($reportId)
    {
        $this->selectedReport = Report::with(['reporter', 'reviewer', 'reportable'])->findOrFail($reportId);
        $this->adminNotes = $this->selectedReport->admin_notes ?? '';
        $this->showReportModal = true;
    }

    public function closeModal()
    {
        $this->showReportModal = false;
        $this->selectedReport = null;
        $this->adminNotes = '';
    }

    public function updateStatus($reportId, $status)
    {
        $report = Report::findOrFail($reportId);
        $report->update([
            'status' => $status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $this->adminNotes,
        ]);

        // Refresh the selected report
        $this->selectedReport = Report::with(['reporter', 'reviewer', 'reportable'])->findOrFail($reportId);

        session()->flash('message', 'Report status updated successfully.');
    }

    public function updateAdminNotes()
    {
        if ($this->selectedReport) {
            $this->selectedReport->update([
                'admin_notes' => $this->adminNotes,
            ]);
            $this->selectedReport->refresh();
            session()->flash('message', 'Admin notes updated successfully.');
        }
    }

    public function render()
    {
        $query = Report::with(['reporter', 'reviewer'])
            ->orderBy('created_at', 'desc');

        if ($this->selectedSection !== 'all') {
            $query->where('section', $this->selectedSection);
        }

        if ($this->selectedStatus !== 'all') {
            $query->where('status', $this->selectedStatus);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('reason', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('reporter_name', 'like', '%' . $this->search . '%')
                    ->orWhere('reporter_email', 'like', '%' . $this->search . '%');
            });
        }

        $reports = $query->paginate(15);

        $sections = Report::distinct()->pluck('section')->toArray();
        $pendingCount = Report::where('status', 'pending')->count();

        return view('livewire.admin.reports', [
            'reports' => $reports,
            'sections' => $sections,
            'pendingCount' => $pendingCount,
        ]);
    }
}
