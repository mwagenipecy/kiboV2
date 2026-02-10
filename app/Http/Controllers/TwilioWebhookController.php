<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppChatbotService;
use App\Services\WhatsAppMessageLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwilioWebhookController extends Controller
{
    protected WhatsAppChatbotService $chatbotService;
    protected WhatsAppMessageLogger $messageLogger;

    public function __construct(WhatsAppChatbotService $chatbotService, WhatsAppMessageLogger $messageLogger)
    {
        $this->chatbotService = $chatbotService;
        $this->messageLogger = $messageLogger;
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
        $body = $request->input('Body', '');
        $buttonPayload = $request->input('ButtonPayload'); // For interactive button clicks
        $buttonText = $request->input('ButtonText'); // For interactive button clicks
        $numMedia = $request->input('NumMedia', '0');

        // Use button payload if available (user clicked a button), otherwise use body text
        // ButtonPayload contains the button ID (e.g., "1" or "2")
        $messageText = $buttonPayload ?? $buttonText ?? $body;

        // If it's a media message with no text, return early
        if (empty($messageText) && $numMedia === '0') {
            // Return empty 200 response (standard for webhooks)
            return response('', 200);
        }

        // Remove whatsapp: prefix to get the phone number
        $phoneNumber = str_replace('whatsapp:', '', $from);
        
        // Log incoming message
        try {
            $this->messageLogger->logIncoming($phoneNumber, $messageText, [
                'message_sid' => $request->input('MessageSid'),
                'button_payload' => $buttonPayload,
                'button_text' => $buttonText,
                'from' => $from,
                'to' => $request->input('To'),
                'status' => 'received',
                'all_inputs' => $request->all(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log incoming message', [
                'phone_number' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);
        }
        
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
                    
                    // Log outgoing message
                    try {
                        $this->messageLogger->logOutgoing($phoneNumber, $responseMessage, [
                            'message_sid' => $result['sid'] ?? null,
                            'from' => $result['from'] ?? null,
                            'to' => $result['to'] ?? null,
                            'status' => $result['status'] ?? 'sent',
                            'used_buttons' => $useButtons,
                            'used_template' => $useTemplate,
                            'template_sid' => $templateSid,
                            'buttons' => $buttons,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to log outgoing message', [
                            'phone_number' => $phoneNumber,
                            'error' => $e->getMessage(),
                        ]);
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
        $messageSid = $request->input('MessageSid');
        $status = $request->input('MessageStatus');
        $errorCode = $request->input('ErrorCode');
        $errorMessage = $request->input('ErrorMessage');
        
        Log::info('WhatsApp message status update from Twilio', [
            'message_sid' => $messageSid,
            'status' => $status,
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
        ]);

        // Update message status in the log
        if ($messageSid) {
            try {
                $this->messageLogger->updateStatus($messageSid, $status, [
                    'error_code' => $errorCode,
                    'error_message' => $errorMessage,
                    'updated_at' => now()->toDateTimeString(),
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to update message status in log', [
                    'message_sid' => $messageSid,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response('', 200);
    }
}

