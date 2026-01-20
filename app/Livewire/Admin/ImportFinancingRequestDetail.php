<?php

namespace App\Livewire\Admin;

use App\Models\Entity;
use App\Models\ImportFinancingRequest;
use Livewire\Component;

class ImportFinancingRequestDetail extends Component
{
    public ImportFinancingRequest $request;
    public string $adminNotes = '';
    public bool $showSendToLendersModal = false;
    public array $selectedLenders = [];

    public function mount($id)
    {
        $this->request = ImportFinancingRequest::with(['user', 'reviewer', 'offers.entity', 'offers.user'])
            ->findOrFail($id);
        $this->adminNotes = $this->request->admin_notes ?? '';
    }

    public function approve()
    {
        $this->request->update([
            'status' => 'approved',
            'admin_notes' => $this->adminNotes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->request->refresh();
        session()->flash('success', 'Request has been approved. You can now send it to lenders.');
    }

    public function reject()
    {
        $this->request->update([
            'status' => 'rejected',
            'admin_notes' => $this->adminNotes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->request->refresh();
        session()->flash('success', 'Request has been rejected.');
    }

    public function openSendToLendersModal()
    {
        $this->showSendToLendersModal = true;
    }

    public function closeSendToLendersModal()
    {
        $this->showSendToLendersModal = false;
        $this->selectedLenders = [];
    }

    public function sendToLenders()
    {
        $this->request->update([
            'status' => 'with_lenders',
            'admin_notes' => $this->adminNotes,
        ]);

        // TODO: Send notifications to selected lenders
        // In a real implementation, you would notify the lenders here

        $this->closeSendToLendersModal();
        $this->request->refresh();
        session()->flash('success', 'Request has been sent to lenders.');
    }

    public function saveNotes()
    {
        $this->request->update([
            'admin_notes' => $this->adminNotes,
        ]);

        session()->flash('success', 'Notes saved successfully.');
    }

    public function render()
    {
        $lenders = Entity::where('type', 'lender')
            ->where('status', 'active')
            ->get();

        return view('livewire.admin.import-financing-request-detail', [
            'lenders' => $lenders,
        ])->layout('layouts.admin');
    }
}

