<?php

namespace App\Livewire\Admin;

use App\Jobs\SendVisitationScheduledEmail;
use App\Models\CarVisitationRequest;
use Livewire\Component;

class VisitationDetail extends Component
{
    public CarVisitationRequest $visitation;

    public $scheduledDate = '';
    public $scheduledTime = '';
    public $location = '';
    public $adminNotes = '';
    public $showScheduleForm = false;
    public $showCompleteModal = false;
    public $showCancelModal = false;

    public function mount(int $id): void
    {
        $this->visitation = CarVisitationRequest::with(['vehicle.make', 'vehicle.model'])
            ->findOrFail($id);

        if ($this->visitation->scheduled_at) {
            $this->scheduledDate = $this->visitation->scheduled_at->format('Y-m-d');
            $this->scheduledTime = $this->visitation->scheduled_at->format('H:i');
        }
        $this->location = $this->visitation->location ?? '';
        $this->adminNotes = $this->visitation->admin_notes ?? '';
        $this->showScheduleForm = $this->visitation->status === 'pending';
    }

    public function schedule(): void
    {
        $this->validate([
            'scheduledDate' => 'required|date',
            'scheduledTime' => 'required|string',
            'location' => 'nullable|string|max:500',
            'adminNotes' => 'nullable|string|max:2000',
        ]);

        $scheduledAt = $this->scheduledDate . ' ' . $this->scheduledTime . ':00';

        $this->visitation->update([
            'status' => 'scheduled',
            'scheduled_at' => $scheduledAt,
            'location' => $this->location ?: null,
            'admin_notes' => $this->adminNotes ?: null,
        ]);

        SendVisitationScheduledEmail::dispatch($this->visitation->id);

        $this->showScheduleForm = false;
        $this->visitation->refresh();
        session()->flash('visitation_message', 'Visitation scheduled. Customer has been emailed with details and location.');
    }

    public function reschedule(): void
    {
        $this->showScheduleForm = true;
    }

    public function updateSchedule(): void
    {
        $this->validate([
            'scheduledDate' => 'required|date',
            'scheduledTime' => 'required|string',
            'location' => 'nullable|string|max:500',
            'adminNotes' => 'nullable|string|max:2000',
        ]);

        $scheduledAt = $this->scheduledDate . ' ' . $this->scheduledTime . ':00';

        $this->visitation->update([
            'scheduled_at' => $scheduledAt,
            'location' => $this->location ?: null,
            'admin_notes' => $this->adminNotes ?: null,
        ]);

        SendVisitationScheduledEmail::dispatch($this->visitation->id);

        $this->showScheduleForm = false;
        $this->visitation->refresh();
        session()->flash('visitation_message', 'Visitation rescheduled. Customer has been emailed with updated details.');
    }

    public function openCompleteModal(): void
    {
        $this->showCompleteModal = true;
    }

    public function closeCompleteModal(): void
    {
        $this->showCompleteModal = false;
    }

    public function openCancelModal(): void
    {
        $this->showCancelModal = true;
    }

    public function closeCancelModal(): void
    {
        $this->showCancelModal = false;
    }

    public function cancel(): void
    {
        $this->visitation->update(['status' => 'cancelled']);
        $this->visitation->refresh();
        $this->showCancelModal = false;
        session()->flash('visitation_message', 'Visitation cancelled.');
    }

    public function markCompleted(): void
    {
        $this->visitation->update(['status' => 'completed']);
        $this->visitation->refresh();
        $this->showCompleteModal = false;
        session()->flash('visitation_message', 'Visitation marked as completed.');
    }

    public function render()
    {
        return view('livewire.admin.visitation-detail', [
            'visitation' => $this->visitation,
        ])->layout('layouts.admin');
    }
}
