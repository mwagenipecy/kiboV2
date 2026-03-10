<?php

namespace App\Jobs;

use App\Mail\VisitationScheduledMail;
use App\Models\CarVisitationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendVisitationScheduledEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public int $visitationId
    ) {}

    public function handle(): void
    {
        $visitation = CarVisitationRequest::find($this->visitationId);
        if ($visitation && $visitation->email) {
            Mail::to($visitation->email)->send(new VisitationScheduledMail($visitation));
        }
    }
}
