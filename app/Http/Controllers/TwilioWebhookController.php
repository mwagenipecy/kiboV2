<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwilioWebhookController extends Controller
{
    protected WhatsAppChatbotService $chatbotService;

    public function __construct(WhatsAppChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Handle incoming WhatsApp messages from Twilio
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleIncomingMessage(Request $request)
    {
        // Log the incoming message for debugging
        Log::info('Incoming WhatsApp message from Twilio', [
            'from' => $request->input('From'),
            'to' => $request->input('To'),
            'body' => $request->input('Body'),
            'message_sid' => $request->input('MessageSid'),
        ]);

        // Extract message details
        $from = $request->input('From'); // whatsapp:+255767582837
        $body = $request->input('Body');

        if (empty($body)) {
            // Return empty 200 response (standard for webhooks)
            return response('', 200);
        }

        // Remove whatsapp: prefix to get the phone number
        $phoneNumber = str_replace('whatsapp:', '', $from);

        try {
            // Process message through chatbot and get response
            $responseMessage = $this->chatbotService->processMessage($phoneNumber, $body);
            
            Log::info('Chatbot processed message', [
                'phone_number' => $phoneNumber,
                'has_response' => !empty($responseMessage),
                'response_length' => $responseMessage ? strlen($responseMessage) : 0,
                'response_preview' => $responseMessage ? substr($responseMessage, 0, 50) . '...' : null,
            ]);
            
            // Send response directly (synchronously) if we have a message
            if ($responseMessage) {
                try {
                    $twilioService = app(\App\Services\TwilioService::class);
                    
                    Log::info('Attempting to send WhatsApp response', [
                        'phone_number' => $phoneNumber,
                        'message_length' => strlen($responseMessage),
                    ]);
                    
                    $result = $twilioService->sendWhatsAppMessage($phoneNumber, $responseMessage);
                    
                    Log::info('WhatsApp response sent successfully', [
                        'phone_number' => $phoneNumber,
                        'message_sid' => $result['sid'] ?? null,
                        'status' => $result['status'] ?? null,
                        'to' => $result['to'] ?? null,
                        'from' => $result['from'] ?? null,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send WhatsApp response', [
                        'phone_number' => $phoneNumber,
                        'error' => $e->getMessage(),
                        'error_code' => $e->getCode(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            } else {
                Log::warning('No response message generated', [
                    'phone_number' => $phoneNumber,
                    'body' => $body,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error processing WhatsApp message', [
                'phone_number' => $phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        // Return empty 200 response (standard for webhooks)
        // Twilio expects a 200 OK response to acknowledge receipt
        return response('', 200);
    }

    /**
     * Handle message status updates from Twilio
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleStatusCallback(Request $request)
    {
        Log::info('WhatsApp message status update from Twilio', [
            'message_sid' => $request->input('MessageSid'),
            'status' => $request->input('MessageStatus'),
            'error_code' => $request->input('ErrorCode'),
            'error_message' => $request->input('ErrorMessage'),
        ]);

        // Update message status in your database if needed
        // $messageSid = $request->input('MessageSid');
        // $status = $request->input('MessageStatus');

        return response('', 200);
    }
}

