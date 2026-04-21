<?php

namespace App\Livewire\Admin;

use App\Jobs\SendComplaintResolvedEmail;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ComplaintDetail extends Component
{
    public Complaint $complaint;
    public $resolutionNotes = '';
    public $assignToUserId = '';
    public $successMessage = '';

    public function mount(int $id): void
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $this->complaint = Complaint::with(['assignedTo', 'user'])->findOrFail($id);
        $this->resolutionNotes = $this->complaint->resolution_notes ?? '';
    }

    public function getAssignableUsersProperty()
    {
        return User::where('role', 'admin')
            ->where('id', '!=', $this->complaint->assigned_to_user_id)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);
    }

    public function canResolve(): bool
    {
        $user = Auth::user();
        return $user->isAdmin() || $this->complaint->assigned_to_user_id === $user->id;
    }

    public function canAssign(): bool
    {
        return Auth::user()->isAdmin();
    }

    public function resolve(): void
    {
        if (!$this->canResolve()) {
            return;
        }
        $this->validate(['resolutionNotes' => ['nullable', 'string', 'max:2000']]);

        $this->complaint->update([
            'status' => Complaint::STATUS_CLOSED,
            'resolved_at' => now(),
            'resolution_notes' => $this->resolutionNotes ?: null,
            'assigned_to_user_id' => null,
        ]);
        $this->complaint->refresh();

        SendComplaintResolvedEmail::dispatchSync($this->complaint->id);

        $this->successMessage = 'Complaint marked as resolved. A notification email has been queued to the complainant.';
    }

    public function assign(): void
    {
        if (!$this->canAssign()) {
            return;
        }
        $this->validate(['assignToUserId' => ['required', 'exists:users,id']]);

        $this->complaint->update([
            'assigned_to_user_id' => (int) $this->assignToUserId,
            'status' => Complaint::STATUS_IN_PROGRESS,
        ]);
        $this->complaint->refresh();
        $this->assignToUserId = '';
        $this->successMessage = 'Complaint assigned successfully.';
    }

    public function render()
    {
        return view('livewire.admin.complaint-detail', [
            'assignableUsers' => $this->assignableUsers,
            'canResolve' => $this->canResolve(),
            'canAssign' => $this->canAssign(),
        ])->layout('layouts.admin');
    }
}
