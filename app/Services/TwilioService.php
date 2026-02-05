<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected Client $twilio;
    protected string $fromNumber;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->fromNumber = config('services.twilio.whatsapp_from');

        if (!$sid || !$token || !$this->fromNumber) {
            \Log::error('Twilio credentials missing', [
                'sid_set' => !empty($sid),
                'token_set' => !empty($token),
                'from_set' => !empty($this->fromNumber),
            ]);
            throw new \Exception('Twilio credentials are not configured. Please check your .env file. Required: TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN, TWILIO_WHATSAPP_FROM');
        }

        $this->twilio = new Client($sid, $token);
    }

    /**
     * Send a plain text WhatsApp message
     *
     * @param string $to The recipient's WhatsApp number (e.g., +255767582837)
     * @param string $body The message body
     * @return array Returns message data including sid, status, etc.
     * @throws TwilioException
     */
    public function sendWhatsAppMessage(string $to, string $body): array
    {
        try {
            // Ensure the number is in WhatsApp format
            $to = $this->formatWhatsAppNumber($to);
            $from = $this->formatWhatsAppNumber($this->fromNumber);

            $message = $this->twilio->messages->create(
                $to,
                [
                    'from' => $from,
                    'body' => $body,
                ]
            );

            return [
                'success' => true,
                'sid' => $message->sid,
                'status' => $message->status,
                'to' => $message->to,
                'from' => $message->from,
                'body' => $message->body,
                'date_created' => $message->dateCreated?->format('Y-m-d H:i:s'),
            ];
        } catch (TwilioException $e) {
            Log::error('Twilio WhatsApp message failed', [
                'to' => $to,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            throw $e;
        }
    }

    /**
     * Send a WhatsApp message using a content template
     *
     * @param string $to The recipient's WhatsApp number (e.g., +255767582837)
     * @param string $contentSid The Content SID from Twilio
     * @param array $contentVariables Variables to replace in the template (e.g., ['1' => '12/1', '2' => '3pm'])
     * @param string|null $body Fallback body text (optional)
     * @return array Returns message data including sid, status, etc.
     * @throws TwilioException
     */
    public function sendWhatsAppTemplate(
        string $to,
        string $contentSid,
        array $contentVariables = [],
        ?string $body = null
    ): array {
        try {
            // Ensure the number is in WhatsApp format
            $to = $this->formatWhatsAppNumber($to);
            $from = $this->formatWhatsAppNumber($this->fromNumber);

            $params = [
                'from' => $from,
                'contentSid' => $contentSid,
            ];

            // Add content variables if provided
            if (!empty($contentVariables)) {
                $params['contentVariables'] = json_encode($contentVariables);
            }

            // Add fallback body if provided
            if ($body !== null) {
                $params['body'] = $body;
            }

            $message = $this->twilio->messages->create($to, $params);

            return [
                'success' => true,
                'sid' => $message->sid,
                'status' => $message->status,
                'to' => $message->to,
                'from' => $message->from,
                'body' => $message->body,
                'date_created' => $message->dateCreated?->format('Y-m-d H:i:s'),
            ];
        } catch (TwilioException $e) {
            Log::error('Twilio WhatsApp template message failed', [
                'to' => $to,
                'content_sid' => $contentSid,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            throw $e;
        }
    }

    /**
     * Format phone number to WhatsApp format
     *
     * @param string $number Phone number
     * @return string Formatted WhatsApp number
     */
    protected function formatWhatsAppNumber(string $number): string
    {
        // Remove any existing whatsapp: prefix
        $number = str_replace('whatsapp:', '', $number);

        // Add whatsapp: prefix if not present
        if (!str_starts_with($number, 'whatsapp:')) {
            $number = 'whatsapp:' . $number;
        }

        return $number;
    }

    /**
     * Get message status by SID
     *
     * @param string $messageSid The message SID
     * @return array Message details
     * @throws TwilioException
     */
    public function getMessageStatus(string $messageSid): array
    {
        try {
            $message = $this->twilio->messages($messageSid)->fetch();

            return [
                'sid' => $message->sid,
                'status' => $message->status,
                'to' => $message->to,
                'from' => $message->from,
                'body' => $message->body,
                'error_code' => $message->errorCode,
                'error_message' => $message->errorMessage,
                'date_created' => $message->dateCreated?->format('Y-m-d H:i:s'),
                'date_sent' => $message->dateSent?->format('Y-m-d H:i:s'),
                'date_updated' => $message->dateUpdated?->format('Y-m-d H:i:s'),
            ];
        } catch (TwilioException $e) {
            Log::error('Failed to fetch Twilio message status', [
                'message_sid' => $messageSid,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}

