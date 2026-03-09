<?php

namespace App\Jobs;

use App\Mail\ComplaintResolvedMail;
use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendComplaintResolvedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public int $complaintId
    ) {}

    public function handle(): void
    {
        $complaint = Complaint::find($this->complaintId);
        if ($complaint && $complaint->email) {
            Mail::to($complaint->email)->send(new ComplaintResolvedMail($complaint));
        }
    }
}
