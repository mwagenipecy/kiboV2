<?php

namespace App\Services;

use App\Models\WhatsAppMessageLog;
use Illuminate\Support\Facades\Log;

class WhatsAppMessageLogger
{
    /**
     * Log an incoming WhatsApp message
     *
     * @param string $phoneNumber
     * @param string $messageBody
     * @param array $metadata Additional metadata (button payload, message SID, etc.)
     * @return WhatsAppMessageLog
     */
    public function logIncoming(string $phoneNumber, string $messageBody, array $metadata = []): WhatsAppMessageLog
    {
        try {
            $log = WhatsAppMessageLog::create([
                'phone_number' => $phoneNumber,
                'direction' => 'incoming',
                'message_body' => $messageBody,
                'message_sid' => $metadata['message_sid'] ?? null,
                'button_payload' => $metadata['button_payload'] ?? null,
                'button_text' => $metadata['button_text'] ?? null,
                'from_number' => $metadata['from'] ?? null,
                'to_number' => $metadata['to'] ?? null,
                'status' => $metadata['status'] ?? 'received',
                'metadata' => $metadata,
                'sent_at' => now(),
            ]);

            Log::info('WhatsApp incoming message logged', [
                'log_id' => $log->id,
                'phone_number' => $phoneNumber,
                'message_length' => strlen($messageBody),
            ]);

            return $log;
        } catch (\Exception $e) {
            Log::error('Failed to log incoming WhatsApp message', [
                'phone_number' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Log an outgoing WhatsApp message
     *
     * @param string $phoneNumber
     * @param string $messageBody
     * @param array $metadata Additional metadata (message SID, status, buttons, etc.)
     * @return WhatsAppMessageLog
     */
    public function logOutgoing(string $phoneNumber, string $messageBody, array $metadata = []): WhatsAppMessageLog
    {
        try {
            $log = WhatsAppMessageLog::create([
                'phone_number' => $phoneNumber,
                'direction' => 'outgoing',
                'message_body' => $messageBody,
                'message_sid' => $metadata['message_sid'] ?? null,
                'from_number' => $metadata['from'] ?? null,
                'to_number' => $metadata['to'] ?? null,
                'status' => $metadata['status'] ?? 'sent',
                'used_buttons' => $metadata['used_buttons'] ?? false,
                'used_template' => $metadata['used_template'] ?? false,
                'template_sid' => $metadata['template_sid'] ?? null,
                'metadata' => $metadata,
                'sent_at' => now(),
            ]);

            Log::info('WhatsApp outgoing message logged', [
                'log_id' => $log->id,
                'phone_number' => $phoneNumber,
                'message_length' => strlen($messageBody),
                'message_sid' => $metadata['message_sid'] ?? null,
            ]);

            return $log;
        } catch (\Exception $e) {
            Log::error('Failed to log outgoing WhatsApp message', [
                'phone_number' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update message log status (e.g., when delivery status changes)
     *
     * @param string $messageSid
     * @param string $status
     * @param array $additionalData
     * @return bool
     */
    public function updateStatus(string $messageSid, string $status, array $additionalData = []): bool
    {
        try {
            $log = WhatsAppMessageLog::where('message_sid', $messageSid)->first();
            
            if ($log) {
                $log->status = $status;
                if (!empty($additionalData)) {
                    $metadata = $log->metadata ?? [];
                    $log->metadata = array_merge($metadata, $additionalData);
                }
                $log->save();

                Log::info('WhatsApp message log status updated', [
                    'message_sid' => $messageSid,
                    'status' => $status,
                    'log_id' => $log->id,
                ]);

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Failed to update WhatsApp message log status', [
                'message_sid' => $messageSid,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get message logs for a phone number
     *
     * @param string $phoneNumber
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLogsForPhoneNumber(string $phoneNumber, int $limit = 50)
    {
        return WhatsAppMessageLog::where('phone_number', $phoneNumber)
            ->orderBy('sent_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent message logs
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentLogs(int $limit = 100)
    {
        return WhatsAppMessageLog::orderBy('sent_at', 'desc')
            ->limit($limit)
            ->get();
    }
}

