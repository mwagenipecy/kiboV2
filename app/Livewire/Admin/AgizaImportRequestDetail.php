<?php

namespace App\Livewire\Admin;

use App\Mail\AgizaImportQuotationSent;
use App\Models\AgizaImportNotification;
use App\Models\AgizaImportRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AgizaImportRequestDetail extends Component
{
    public AgizaImportRequest $request;
    public string $adminNotes = '';
    public string $status = '';
    public ?int $assignedTo = null;
    public ?float $quotedImportCost = null;
    public ?float $quotedTotalCost = null;
    public string $quoteCurrency = 'USD';

    public bool $showSuccessMessage = false;
    public string $successMessage = '';

    public function mount($id)
    {
        $this->request = AgizaImportRequest::with(['user', 'assignedAgent'])->findOrFail($id);
        $this->adminNotes = $this->request->admin_notes ?? '';
        $this->status = $this->request->status;
        $this->assignedTo = $this->request->assigned_to;
        $this->quotedImportCost = $this->request->quoted_import_cost;
        $this->quotedTotalCost = $this->request->quoted_total_cost;
        $this->quoteCurrency = $this->request->quote_currency ?? 'USD';
    }

    public function saveNotes()
    {
        $this->request->update([
            'admin_notes' => $this->adminNotes,
        ]);

        $this->successMessage = 'Notes saved successfully!';
        $this->showSuccessMessage = true;
        $this->dispatch('notes-saved');
    }

    public function updateStatus()
    {
        $this->validate([
            'status' => 'required|in:pending,under_review,quote_provided,accepted,in_progress,completed,cancelled',
        ]);

        $this->request->update([
            'status' => $this->status,
        ]);

        $this->successMessage = 'Status updated successfully!';
        $this->showSuccessMessage = true;
        $this->dispatch('status-updated');
    }

    public function assignAgent()
    {
        $this->request->update([
            'assigned_to' => $this->assignedTo,
        ]);

        $this->successMessage = 'Agent assigned successfully!';
        $this->showSuccessMessage = true;
        $this->dispatch('agent-assigned');
    }

    public function provideQuote()
    {
        $this->validate([
            'quotedImportCost' => 'required|numeric|min:0',
            'quotedTotalCost' => 'required|numeric|min:0',
            'quoteCurrency' => 'required|string|max:10',
        ]);

        $this->request->update([
            'quoted_import_cost' => $this->quotedImportCost,
            'quoted_total_cost' => $this->quotedTotalCost,
            'quote_currency' => $this->quoteCurrency,
            'quoted_at' => now(),
            'status' => 'quote_provided',
        ]);

        AgizaImportNotification::create([
            'agiza_import_request_id' => $this->request->id,
            'user_id' => $this->request->user_id,
            'type' => 'quotation_sent',
            'title' => 'Quotation Received',
            'message' => 'We have prepared a quotation for your import request #' . $this->request->request_number,
            'data' => [
                'import_cost' => $this->quotedImportCost,
                'total_cost' => $this->quotedTotalCost,
                'currency' => $this->quoteCurrency,
            ],
            'is_read' => false,
        ]);

        try {
            Mail::to($this->request->customer_email)->queue(new AgizaImportQuotationSent($this->request));
        } catch (\Exception $e) {
            \Log::error('Failed to send quotation email: ' . $e->getMessage());
        }

        $this->status = 'quote_provided';
        $this->successMessage = 'Quote provided successfully! Email sent and notification created in customer portal.';
        $this->showSuccessMessage = true;
        $this->dispatch('quote-provided');
    }

    public function closeSuccessMessage()
    {
        $this->showSuccessMessage = false;
    }

    public function render()
    {
        $agents = User::where('role', 'admin')->get();

        return view('livewire.admin.agiza-import-request-detail', [
            'agents' => $agents,
        ]);
    }
}
