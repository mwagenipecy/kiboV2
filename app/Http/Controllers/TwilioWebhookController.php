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
            // Process message through chatbot
            $this->chatbotService->processMessage($phoneNumber, $body);
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

