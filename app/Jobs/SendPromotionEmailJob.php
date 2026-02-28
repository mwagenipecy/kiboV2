<?php

namespace App\Jobs;

use App\Mail\PromotionMail;
use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPromotionEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    public function __construct(
        public string $recipientEmail,
        public string $recipientName,
        public string $recipientType,
        public string $subject,
        public string $bodyHtml,
        public ?int $sentByUserId = null,
        public ?int $promotionCampaignId = null,
        public ?int $emailLogId = null
    ) {}

    public function handle(): void
    {
        $log = $this->emailLogId
            ? EmailLog::findOrFail($this->emailLogId)
            : EmailLog::create([
                'type' => 'promotion',
                'promotion_campaign_id' => $this->promotionCampaignId,
                'recipient_email' => $this->recipientEmail,
                'recipient_name' => $this->recipientName,
                'recipient_type' => $this->recipientType,
                'subject' => $this->subject,
                'sent_by_user_id' => $this->sentByUserId,
                'sent_at' => null,
                'status' => 'pending',
                'metadata' => [],
            ]);

        try {
            Mail::to($this->recipientEmail)->send(
                new PromotionMail($this->recipientName, $this->subject, $this->bodyHtml)
            );

            $log->update([
                'sent_at' => now(),
                'status' => 'sent',
            ]);
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'metadata' => array_merge($log->metadata ?? [], ['exception' => get_class($e)]),
            ]);
            throw $e;
        }
    }
}
