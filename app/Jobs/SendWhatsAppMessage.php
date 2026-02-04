<?php

namespace App\Jobs;

use App\Services\TwilioService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\TwilioException;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $to,
        public string $body,
        public ?string $contentSid = null,
        public array $contentVariables = []
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(TwilioService $twilioService): void
    {
        try {
            if ($this->contentSid) {
                // Send template message
                $result = $twilioService->sendWhatsAppTemplate(
                    $this->to,
                    $this->contentSid,
                    $this->contentVariables,
                    $this->body
                );
            } else {
                // Send plain text message
                $result = $twilioService->sendWhatsAppMessage($this->to, $this->body);
            }

            Log::info('WhatsApp message sent successfully', [
                'to' => $this->to,
                'sid' => $result['sid'],
                'status' => $result['status'],
            ]);
        } catch (TwilioException $e) {
            Log::error('Failed to send WhatsApp message', [
                'to' => $this->to,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('WhatsApp message job failed after all retries', [
            'to' => $this->to,
            'error' => $exception->getMessage(),
        ]);
    }
}

