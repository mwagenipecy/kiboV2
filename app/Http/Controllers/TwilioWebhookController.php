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
        $buttonPayload = $request->input('ButtonPayload'); // For interactive button clicks
        $buttonText = $request->input('ButtonText'); // For interactive button clicks

        // Use button payload if available (user clicked a button), otherwise use body text
        $messageText = $buttonPayload ?? $buttonText ?? $body;

        if (empty($messageText)) {
            // Return empty 200 response (standard for webhooks)
            return response('', 200);
        }

        // Remove whatsapp: prefix to get the phone number
        $phoneNumber = str_replace('whatsapp:', '', $from);
        
        Log::info('Processing WhatsApp message', [
            'phone_number' => $phoneNumber,
            'body' => $body,
            'button_payload' => $buttonPayload,
            'button_text' => $buttonText,
            'message_text' => $messageText,
        ]);

        try {
            // Process message through chatbot and get response
            $response = $this->chatbotService->processMessage($phoneNumber, $messageText);
            $responseMessage = $response['message'] ?? null;
            $useButtons = $response['use_buttons'] ?? false;
            $buttons = $response['buttons'] ?? [];
            $useTemplate = $response['use_template'] ?? false;
            $templateSid = $response['template_sid'] ?? null;
            
            Log::info('Chatbot processed message', [
                'phone_number' => $phoneNumber,
                'has_response' => !empty($responseMessage),
                'response_length' => $responseMessage ? strlen($responseMessage) : 0,
                'response_preview' => $responseMessage ? substr($responseMessage, 0, 50) . '...' : null,
                'use_buttons' => $useButtons,
                'buttons_count' => count($buttons),
                'use_template' => $useTemplate,
                'template_sid' => $templateSid,
            ]);
            
            // Send response directly (synchronously) if we have a message
            if ($responseMessage) {
                try {
                    $twilioService = app(\App\Services\TwilioService::class);
                    
                    Log::info('Attempting to send WhatsApp response', [
                        'phone_number' => $phoneNumber,
                        'message_length' => strlen($responseMessage),
                        'use_buttons' => $useButtons,
                        'use_template' => $useTemplate,
                    ]);
                    
                    // Use buttons if configured, otherwise use template or plain text
                    if ($useButtons && !empty($buttons)) {
                        $result = $twilioService->sendWhatsAppMessageWithButtons($phoneNumber, $responseMessage, $buttons);
                    } elseif ($useTemplate && $templateSid) {
                        $result = $twilioService->sendWhatsAppTemplate($phoneNumber, $templateSid, [], $responseMessage);
                    } else {
                        $result = $twilioService->sendWhatsAppMessage($phoneNumber, $responseMessage);
                    }
                    
                    Log::info('WhatsApp response sent successfully', [
                        'phone_number' => $phoneNumber,
                        'message_sid' => $result['sid'] ?? null,
                        'status' => $result['status'] ?? null,
                        'to' => $result['to'] ?? null,
                        'from' => $result['from'] ?? null,
                        'used_buttons' => $useButtons,
                        'used_template' => $useTemplate,
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
                    'body' => $messageText,
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

