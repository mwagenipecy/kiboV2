<?php

namespace App\Services;

use App\Models\ChatbotConversation;
use App\Models\SparePartOrder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class WhatsAppChatbotService
{
    protected TwilioService $twilioService;

    protected array $services;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
        $this->services = $this->getAvailableServices();
    }

    /**
     * Process incoming WhatsApp message
     * Returns array with 'message' and optionally 'use_template' and 'template_sid'
     */
    public function processMessage(string $phoneNumber, string $message): array
    {
        // Get conversation first to check context before termination check
        $conversation = ChatbotConversation::where('phone_number', $phoneNumber)->first();

        // Check if user is in OTP verification step - don't treat "resend" as termination
        $messageLower = strtolower(trim($message));
        $isResendRequest = in_array($messageLower, ['resend', 'tuma tena', 'tuma upya', 'retry']);
        $isInOtpVerification = $conversation && $conversation->getContext('sparepart_substep') === 'otp_verification';

        $trimLower = strtolower(trim($message));
        $skipTerminateForNav = $conversation && (
            ($conversation->current_step === 'track_order_input' && ($this->isNumericMainMenuShortcut($message) || in_array($trimLower, ['back', 'menu', 'rudi', 'orodha', 'cancel', 'ghairi'], true)))
            || ($conversation->current_step === 'kibo_services_menu' && ($this->isNumericMainMenuShortcut($message) || in_array($trimLower, ['back', 'menu', 'rudi', 'orodha'], true)))
            || ($conversation->current_step === 'service_selected' && ($this->isNumericMainMenuShortcut($message) || in_array($trimLower, ['back', 'menu', 'rudi', 'orodha'], true)))
        );

        // If user is in OTP verification and types resend, skip termination check
        if (($isResendRequest && $isInOtpVerification) || $skipTerminateForNav) {
            // Continue to normal processing
        } elseif ($this->isTerminationRequest($message)) {
            // FIRST: Check if user wants to terminate/reset the session
            if ($conversation) {
                $conversation->reset();
                // Ensure step is set to language_selection for buttons
                $conversation->updateStep('language_selection');
                Log::info('Session terminated by user request', [
                    'phone_number' => $phoneNumber,
                    'message' => $message,
                    'termination_keyword' => trim($message),
                ]);
            } else {
                $conversation = ChatbotConversation::getOrCreate($phoneNumber);
                $conversation->updateStep('language_selection');
            }

            // Return welcome message; use template for selection if configured, else buttons
            $templateSid = config('services.twilio.language_selection_template_sid');
            $useTemplate = ! empty($templateSid);

            return [
                'message' => $this->getWelcomeMessage(),
                'use_buttons' => ! $useTemplate,
                'buttons' => $useTemplate ? [] : [
                    ['id' => '1', 'title' => __('common.english')],
                    ['id' => '2', 'title' => __('common.swahili')],
                ],
                'use_template' => $useTemplate,
                'template_sid' => $useTemplate ? $templateSid : null,
            ];
        }

        // Get or create conversation if not already retrieved (this will check for expiration and reset if needed)
        if (! $conversation) {
            $conversation = ChatbotConversation::getOrCreate($phoneNumber);
        }

        // Store the OLD last_interaction_at before updating
        $oldLastInteraction = $conversation->last_interaction_at;

        // Update last interaction to mark user as active
        $conversation->last_interaction_at = now();
        $conversation->is_active = true;
        $conversation->save();

        // Refresh to ensure we have the latest data
        $conversation->refresh();

        // Check if session was expired BEFORE we updated the timestamp
        // Use the old timestamp to check expiration, not the new one
        // This prevents false expiration during active conversation
        if ($oldLastInteraction) {
            $idleSeconds = $oldLastInteraction->diffInSeconds(now());
            $minIdleTimeoutSeconds = config('chatbot.min_idle_timeout_minutes', 4) * 60;
            $idleTimeoutMinutes = config('chatbot.idle_timeout_minutes', 30);

            // Only check expiration if at least 4 minutes have passed
            // This prevents premature expiration during active conversation
            if ($idleSeconds >= $minIdleTimeoutSeconds) {
                $idleMinutes = $oldLastInteraction->diffInMinutes(now());

                // Check if truly expired (using old timestamp)
                if ($idleMinutes >= $idleTimeoutMinutes) {
                    // Check if message is a language selection attempt ("1" or "2")
                    // If so, don't reset - user is trying to select language
                    $trimmedMsg = trim($message);
                    $isLanguageSelection = in_array($trimmedMsg, ['1', '2']) ||
                                           in_array(strtolower($trimmedMsg), ['1', '2', 'en', 'sw', 'english', 'swahili']);

                    if (! $isLanguageSelection) {
                        Log::info('Chatbot session expired, resetting conversation', [
                            'phone_number' => $phoneNumber,
                            'last_interaction' => $oldLastInteraction,
                            'idle_minutes' => $idleMinutes,
                            'idle_seconds' => $idleSeconds,
                        ]);
                        $conversation->reset();
                    } else {
                        Log::info('Session expired but user is selecting language, not resetting', [
                            'phone_number' => $phoneNumber,
                            'message' => $message,
                        ]);
                    }
                }
            } else {
                // User is actively chatting (less than 4 minutes), never expire
                Log::debug('User is actively chatting, not checking expiration', [
                    'phone_number' => $phoneNumber,
                    'idle_seconds' => $idleSeconds,
                    'min_timeout' => $minIdleTimeoutSeconds,
                ]);
            }
        }

        // Set locale based on conversation language
        App::setLocale($conversation->language);

        // Store current step before processing
        $currentStep = $conversation->current_step;

        Log::info('Processing message', [
            'phone_number' => $phoneNumber,
            'message' => $message,
            'current_step' => $currentStep,
            'language' => $conversation->language,
            'context' => $conversation->context,
        ]);

        // Process based on current step
        Log::info('Routing to handler', [
            'phone_number' => $phoneNumber,
            'current_step' => $currentStep,
            'message' => $message,
        ]);

        $responseMessage = match ($currentStep) {
            'welcome' => $this->handleWelcome($conversation, $message),
            'language_selection' => $this->handleLanguageSelection($conversation, $message),
            'main_menu' => $this->handleMainMenu($conversation, $message),
            'kibo_services_menu' => $this->handleKiboServicesMenu($conversation, $message),
            'track_order_input' => $this->handleTrackOrderInput($conversation, $message),
            'service_selected' => $this->handleServiceSelected($conversation, $message),
            default => $this->handleDefault($conversation, $message),
        };

        Log::info('Handler returned response', [
            'phone_number' => $phoneNumber,
            'response_length' => strlen($responseMessage ?? ''),
            'response_preview' => substr($responseMessage ?? '', 0, 100),
            'is_main_menu' => str_contains($responseMessage ?? '', __('chatbot.main_menu_title')),
        ]);

        // Reload conversation from database to get the latest state after processing
        // This ensures we have the updated step and context
        // Store ID before reloading
        $conversationId = $conversation->id;

        // Use fresh() to bypass any model cache and get latest from DB
        $conversation = $conversation->fresh();

        if (! $conversation) {
            Log::error('Failed to reload conversation after processing', [
                'phone_number' => $phoneNumber,
                'conversation_id' => $conversationId,
            ]);
            // Fallback: try to get it again
            $conversation = ChatbotConversation::where('phone_number', $phoneNumber)->first();

            if (! $conversation) {
                Log::error('Could not find conversation after processing', [
                    'phone_number' => $phoneNumber,
                ]);
                // Create a new one as last resort
                $conversation = ChatbotConversation::getOrCreate($phoneNumber);
            }
        }

        Log::info('After processing', [
            'phone_number' => $phoneNumber,
            'updated_step' => $conversation->current_step,
            'updated_language' => $conversation->language,
            'updated_context' => $conversation->context,
        ]);

        // Check if we should use buttons or template for selection messages
        $useButtons = false;
        $buttons = [];
        $useTemplate = false;
        $templateSid = null;

        $isLanguageSelectionMessage = str_contains($responseMessage, __('chatbot.select_language'))
                                   || str_contains($responseMessage, __('chatbot.welcome'));
        $isMainMenuMessage = str_contains($responseMessage, __('chatbot.main_menu_title'));
        $isOtherMenuMessage = str_contains($responseMessage, __('chatbot.reply_with_number'))
                           && ! $isLanguageSelectionMessage
                           && ! $isMainMenuMessage;

        if ($conversation->current_step === 'language_selection' && $isLanguageSelectionMessage && ! $isMainMenuMessage) {
            $templateSid = config('services.twilio.language_selection_template_sid');
            if ($templateSid) {
                $useTemplate = true;
                Log::info('Using template for language selection', [
                    'phone_number' => $phoneNumber,
                    'template_sid' => $templateSid,
                ]);
            } else {
                $useButtons = true;
                $buttons = [
                    ['id' => '1', 'title' => __('common.english')],
                    ['id' => '2', 'title' => __('common.swahili')],
                ];
                Log::info('Adding buttons to language selection message', [
                    'phone_number' => $phoneNumber,
                    'step' => $conversation->current_step,
                ]);
            }
        } elseif ($isMainMenuMessage) {
            $templateSid = config('services.twilio.main_menu_template_sid');
            if ($templateSid) {
                $useTemplate = true;
                Log::info('Using template for main menu', [
                    'phone_number' => $phoneNumber,
                    'template_sid' => $templateSid,
                ]);
            }
        } elseif ($isOtherMenuMessage) {
            $templateSid = config('services.twilio.menu_template_sid');
            if ($templateSid) {
                $useTemplate = true;
                Log::info('Using template for menu selection', [
                    'phone_number' => $phoneNumber,
                    'template_sid' => $templateSid,
                ]);
            }
        }

        if (! $useTemplate && ! $useButtons) {
            Log::info('Not using template or buttons', [
                'phone_number' => $phoneNumber,
                'step' => $conversation->current_step,
                'is_language_selection_msg' => $isLanguageSelectionMessage,
                'is_main_menu_msg' => $isMainMenuMessage,
                'is_other_menu_msg' => $isOtherMenuMessage,
                'response_preview' => substr($responseMessage, 0, 50),
            ]);
        }

        return [
            'message' => $responseMessage,
            'use_buttons' => $useButtons,
            'buttons' => $buttons,
            'use_template' => $useTemplate,
            'template_sid' => $useTemplate ? $templateSid : null,
        ];
    }

    /**
     * Handle welcome message
     */
    protected function handleWelcome(ChatbotConversation $conversation, string $message): ?string
    {
        // Normalize message for comparison
        $trimmedMessage = trim($message);
        $normalizedMessage = strtolower($trimmedMessage);

        // FIRST: Check if user is trying to select language ("1" or "2")
        // This handles cases where session expired but user is responding to language selection
        $isEnglish = $trimmedMessage === '1' || $normalizedMessage === '1' ||
                     in_array($normalizedMessage, ['1', 'en', 'english', 'kiingereza']);
        $isSwahili = $trimmedMessage === '2' || $normalizedMessage === '2' ||
                     in_array($normalizedMessage, ['2', 'sw', 'swahili', 'kiswahili']);

        if ($isEnglish || $isSwahili) {
            // User is selecting language, move directly to language selection step and process it
            $conversation->updateStep('language_selection');

            // Now handle it as language selection
            return $this->handleLanguageSelection($conversation, $message);
        }

        // Check if user wants to start/reset or sent a greeting
        $greetings = ['hi', 'hello', 'hey', 'start', 'hujambo', 'mambo', 'habari', 'salam', 'salaam'];

        if (in_array($normalizedMessage, $greetings)) {
            // User sent a greeting, move to language selection
            $conversation->updateStep('language_selection', [
                'greeting_received' => true,
                'greeting_at' => now()->toDateTimeString(),
            ]);

            return $this->getWelcomeMessage();
        }

        // If not a recognized greeting, show welcome anyway and move to language selection
        // This handles cases where user sends something unexpected
        $conversation->updateStep('language_selection', [
            'unexpected_message' => $message,
            'greeting_at' => now()->toDateTimeString(),
        ]);

        return $this->getWelcomeMessage();
    }

    /**
     * Handle language selection
     */
    protected function handleLanguageSelection(ChatbotConversation $conversation, string $message): ?string
    {
        // Normalize message - handle button payloads and text
        // Remove any whitespace and convert to lowercase
        $trimmedMessage = trim($message);
        $normalizedMessage = strtolower($trimmedMessage);

        Log::info('Handling language selection', [
            'phone_number' => $conversation->phone_number,
            'conversation_id' => $conversation->id,
            'original_message' => $message,
            'trimmed_message' => $trimmedMessage,
            'normalized_message' => $normalizedMessage,
            'message_length' => strlen($message),
            'trimmed_length' => strlen($trimmedMessage),
            'message_bytes' => bin2hex($message),
            'current_step' => $conversation->current_step,
        ]);

        // SIMPLIFIED: Check if message is "1" or "2" (most common case)
        // Use the simplest possible check - just compare the trimmed message
        $isEnglish = false;
        $isSwahili = false;

        // Direct string comparison - this should catch "1" exactly
        if ($trimmedMessage === '1') {
            $isEnglish = true;
        }
        // Also check normalized for other variations
        elseif ($normalizedMessage === '1' || in_array($normalizedMessage, ['1', 'en', 'english', 'kiingereza'])) {
            $isEnglish = true;
        }
        // Regex as fallback
        elseif (preg_match('/^1\s*\.?\s*$/i', $trimmedMessage)) {
            $isEnglish = true;
        }
        // Check for Swahili - same logic
        elseif ($trimmedMessage === '2') {
            $isSwahili = true;
        } elseif ($normalizedMessage === '2' || in_array($normalizedMessage, ['2', 'sw', 'swahili', 'kiswahili'])) {
            $isSwahili = true;
        } elseif (preg_match('/^2\s*\.?\s*$/i', $trimmedMessage)) {
            $isSwahili = true;
        }

        Log::info('Language selection check result', [
            'phone_number' => $conversation->phone_number,
            'is_english' => $isEnglish,
            'is_swahili' => $isSwahili,
            'trimmed' => $trimmedMessage,
            'normalized' => $normalizedMessage,
        ]);

        if ($isEnglish) {
            // Set language on the model BEFORE updating step
            $conversation->language = 'en';
            App::setLocale('en');

            // Update step with context - this will save everything including language
            $conversation->updateStep('main_menu', [
                'language_selected' => 'en',
                'selected_at' => now()->toDateTimeString(),
                'selection_method' => 'button_or_text',
                'original_message' => $message,
            ]);

            // Ensure language is persisted (updateStep should handle this, but double-check)
            if ($conversation->language !== 'en') {
                $conversation->language = 'en';
                $conversation->save();
            }

            // Refresh to get latest state
            $conversation->refresh();

            Log::info('Language selected: English', [
                'phone_number' => $conversation->phone_number,
                'new_step' => $conversation->current_step,
                'context' => $conversation->context,
                'language' => $conversation->language,
            ]);

            $mainMenu = $this->getMainMenu();
            Log::info('Returning main menu', [
                'phone_number' => $conversation->phone_number,
                'menu_preview' => substr($mainMenu, 0, 100),
            ]);

            return $mainMenu;
        }

        if ($isSwahili) {
            // Set language on the model BEFORE updating step
            $conversation->language = 'sw';
            App::setLocale('sw');

            // Update step with context - this will save everything including language
            $conversation->updateStep('main_menu', [
                'language_selected' => 'sw',
                'selected_at' => now()->toDateTimeString(),
                'selection_method' => 'button_or_text',
                'original_message' => $message,
            ]);

            // Ensure language is persisted (updateStep should handle this, but double-check)
            if ($conversation->language !== 'sw') {
                $conversation->language = 'sw';
                $conversation->save();
            }

            // Refresh to get latest state
            $conversation->refresh();

            Log::info('Language selected: Swahili', [
                'phone_number' => $conversation->phone_number,
                'new_step' => $conversation->current_step,
                'context' => $conversation->context,
                'language' => $conversation->language,
            ]);

            $mainMenu = $this->getMainMenu();
            Log::info('Returning main menu', [
                'phone_number' => $conversation->phone_number,
                'menu_preview' => substr($mainMenu, 0, 100),
            ]);

            return $mainMenu;
        }

        // Invalid selection - tell user it's wrong and show language menu again
        Log::warning('Invalid language selection', [
            'phone_number' => $conversation->phone_number,
            'message' => $message,
            'trimmed_message' => $trimmedMessage,
            'normalized' => $normalizedMessage,
            'message_bytes' => bin2hex($message),
        ]);

        return __('chatbot.wrong_language_selection')."\n\n".$this->getLanguageSelectionMessage();
    }

    /**
     * Handle main menu selection (top level: spare parts, garage, tracking, FAQ, Kibo services).
     */
    protected function handleMainMenu(ChatbotConversation $conversation, string $message): ?string
    {
        $message = strtolower(trim($message));

        if ($message === '0' || in_array($message, ['start over', 'startover', 'rudi mwanzo', 'anza upya', 'anza'])) {
            $conversation->reset();
            $conversation->updateStep('language_selection');

            return $this->getWelcomeMessage();
        }

        $choice = $this->resolveMainMenuChoice($message);
        if ($choice === null) {
            return $this->getMainMenu()."\n\n".__('chatbot.invalid_selection');
        }

        $conversation->setContext('from_kibo_submenu', false);

        return match ($choice) {
            'spare_parts' => $this->enterSparePartsFromMainMenu($conversation, $message),
            'garage' => $this->enterLeafServiceFromMainMenu($conversation, 'garage'),
            'tracking' => $this->enterTrackingFromMainMenu($conversation),
            'faq' => $this->enterFaqFromMainMenu($conversation, $message),
            'kibo_services' => $this->enterKiboSubmenuFromMainMenu($conversation),
        };
    }

    protected function resolveMainMenuChoice(string $message): ?string
    {
        $byNumber = [
            '1' => 'spare_parts',
            '2' => 'garage',
            '3' => 'tracking',
            '4' => 'faq',
            '5' => 'kibo_services',
        ];
        if (isset($byNumber[$message])) {
            return $byNumber[$message];
        }

        $tSpare = strtolower(__('chatbot.service.spare_parts'));
        $tGarage = strtolower(__('chatbot.service.garage'));
        $tTrack = strtolower(__('chatbot.service.tracking'));
        $tFaq = strtolower(__('chatbot.service.faq'));
        $tKibo = strtolower(__('chatbot.main_menu.kibo_services'));

        if ($message === $tSpare || str_contains($message, 'spare') || str_contains($message, 'sehemu')) {
            return 'spare_parts';
        }
        if ($message === $tGarage || str_contains($message, 'garage') || str_contains($message, 'karakana')) {
            return 'garage';
        }
        if ($message === $tTrack || str_contains($message, 'track') || str_contains($message, 'fuatilia')) {
            return 'tracking';
        }
        if ($message === $tFaq || str_contains($message, 'faq') || ($message !== $tKibo && str_contains($message, 'maswali'))) {
            return 'faq';
        }
        if ($message === $tKibo || str_contains($message, 'kibo service') || str_contains($message, 'huduma za kibo')) {
            return 'kibo_services';
        }

        return null;
    }

    protected function enterSparePartsFromMainMenu(ChatbotConversation $conversation, string $message): string
    {
        $conversation->setContext('selected_service', 'spare_parts');
        $conversation->updateStep('service_selected', [
            'selected_service' => 'spare_parts',
            'selected_at' => now()->toDateTimeString(),
        ]);
        $orderService = new \App\Services\SparePartOrderChatbotService;
        $conversation->setContext('sparepart_substep', 'start');

        return $orderService->handleOrderFlow($conversation, $message);
    }

    protected function enterLeafServiceFromMainMenu(ChatbotConversation $conversation, string $serviceKey): string
    {
        $service = collect($this->services)->firstWhere('key', $serviceKey);
        if (! $service) {
            return $this->getMainMenu()."\n\n".__('chatbot.invalid_selection');
        }
        $conversation->setContext('selected_service', $serviceKey);
        $conversation->updateStep('service_selected', [
            'selected_service' => $serviceKey,
            'selected_at' => now()->toDateTimeString(),
        ]);

        return $this->getServiceDetails($service);
    }

    protected function enterTrackingFromMainMenu(ChatbotConversation $conversation): string
    {
        $conversation->setContext('selected_service', null);
        $conversation->updateStep('track_order_input', [
            'entered_tracking_at' => now()->toDateTimeString(),
        ]);

        return __('chatbot.tracking.prompt');
    }

    protected function enterFaqFromMainMenu(ChatbotConversation $conversation, string $message): string
    {
        $conversation->setContext('selected_service', 'faq');
        $conversation->updateStep('service_selected', [
            'selected_service' => 'faq',
            'selected_at' => now()->toDateTimeString(),
        ]);

        return $this->handleFaqFlow($conversation, $message);
    }

    protected function enterKiboSubmenuFromMainMenu(ChatbotConversation $conversation): string
    {
        $conversation->updateStep('kibo_services_menu', [
            'entered_kibo_submenu_at' => now()->toDateTimeString(),
        ]);

        return $this->getKiboServicesMenuMessage();
    }

    /**
     * Cars, trucks, leasing, financing, valuation, sell.
     */
    protected function getKiboNestedServices(): array
    {
        $byKey = collect($this->services)->keyBy('key');

        return collect(['cars', 'trucks', 'leasing', 'financing', 'valuation', 'sell'])
            ->map(fn (string $k) => $byKey->get($k))
            ->filter()
            ->values()
            ->all();
    }

    protected function getKiboServicesMenuMessage(): string
    {
        $msg = __('chatbot.kibo_submenu.title')."\n\n";
        $msg .= '0. '.__('chatbot.kibo_submenu.back')."\n";
        foreach ($this->getKiboNestedServices() as $index => $service) {
            $msg .= ($index + 1).'. '.__($service['translation_key'])."\n";
        }
        $msg .= "\n".__('chatbot.reply_with_number');

        return $msg;
    }

    protected function handleKiboServicesMenu(ChatbotConversation $conversation, string $message): ?string
    {
        $trimmed = trim($message);
        $lower = strtolower($trimmed);

        if ($this->isNumericMainMenuShortcut($message) || in_array($lower, ['back', 'menu', 'rudi', 'orodha'])) {
            $conversation->updateStep('main_menu');

            return $this->getMainMenu();
        }

        $nested = $this->getKiboNestedServices();
        $selected = null;

        foreach ($nested as $index => $service) {
            $n = (string) ($index + 1);
            $serviceName = strtolower($service['name']);
            $trans = strtolower(__($service['translation_key']));
            if ($lower === $n ||
                $lower === $serviceName ||
                $lower === $trans ||
                str_contains($lower, $serviceName) ||
                str_contains($lower, $trans)) {
                $selected = $service;
                break;
            }
        }

        if (! $selected) {
            return $this->getKiboServicesMenuMessage()."\n\n".__('chatbot.invalid_selection');
        }

        $conversation->setContext('from_kibo_submenu', true);
        $conversation->setContext('selected_service', $selected['key']);
        $conversation->updateStep('service_selected', [
            'selected_service' => $selected['key'],
            'selected_at' => now()->toDateTimeString(),
        ]);

        return $this->getServiceDetails($selected);
    }

    protected function handleTrackOrderInput(ChatbotConversation $conversation, string $message): ?string
    {
        $trimmed = trim($message);
        $lower = strtolower($trimmed);

        if ($this->isNumericMainMenuShortcut($message) || in_array($lower, ['back', 'menu', 'rudi', 'orodha', 'cancel', 'ghairi'])) {
            $conversation->updateStep('main_menu');

            return $this->getMainMenu();
        }

        if ($conversation->current_step !== 'track_order_input') {
            $conversation->updateStep('track_order_input');
        }

        $order = $this->resolveSparePartOrderFromTrackingInput($trimmed);
        if (! $order) {
            return __('chatbot.tracking.not_found')."\n\n".__('chatbot.tracking.prompt');
        }

        return $this->formatSparePartOrderTrackingSummary($order)."\n\n".__('chatbot.tracking.prompt_again');
    }

    protected function resolveSparePartOrderFromTrackingInput(string $raw): ?SparePartOrder
    {
        $normalizedOrder = strtoupper(preg_replace('/\s+/', '', $raw));
        if (preg_match('/^SPO-\d{8}-[A-Z0-9]+$/', $normalizedOrder)) {
            return SparePartOrder::query()
                ->with(['vehicleMake', 'vehicleModel'])
                ->where('order_number', $normalizedOrder)
                ->first();
        }

        if (preg_match('/\/spare-parts\/track\/([a-f0-9]{40})/i', $raw, $matches)) {
            $token = strtolower($matches[1]);

            return SparePartOrder::query()
                ->with(['vehicleMake', 'vehicleModel'])
                ->where('public_token', $token)
                ->first();
        }

        $hex = preg_replace('/[^a-f0-9]/i', '', $raw);
        if (strlen($hex) === 40) {
            return SparePartOrder::query()
                ->with(['vehicleMake', 'vehicleModel'])
                ->where('public_token', strtolower($hex))
                ->first();
        }

        return null;
    }

    protected function formatSparePartOrderTrackingSummary(SparePartOrder $order): string
    {
        $lines = [];
        $lines[] = '📦 *'.__('chatbot.tracking.summary_title').'*';
        $lines[] = '*'.__('chatbot.tracking.label_order').'* '.$order->order_number;
        $lines[] = '*'.__('chatbot.tracking.label_status').'* '.$this->translateOrderStatus($order->status);

        if (! empty($order->part_name)) {
            $lines[] = '*'.__('chatbot.tracking.label_part').'* '.$order->part_name;
        }

        $make = $order->vehicleMake?->name;
        $model = $order->vehicleModel?->name;
        if ($make || $model) {
            $lines[] = '*'.__('chatbot.tracking.label_vehicle').'* '.trim(($make ?? '').' '.($model ?? ''));
        }

        if (! empty($order->delivery_address)) {
            $lines[] = '*'.__('chatbot.tracking.label_delivery').'* '.$order->delivery_address;
        }

        if ($order->quoted_price !== null) {
            $currency = $order->currency ?? 'TZS';
            $lines[] = '*'.__('chatbot.tracking.label_quote').'* '.number_format((float) $order->quoted_price, 0).' '.$currency;
        }

        if (! empty($order->estimated_delivery_date)) {
            try {
                $lines[] = '*'.__('chatbot.tracking.label_estimated_delivery').'* '.\Illuminate\Support\Carbon::parse($order->estimated_delivery_date)->format('d M Y');
            } catch (\Throwable) {
                $lines[] = '*'.__('chatbot.tracking.label_estimated_delivery').'* '.$order->estimated_delivery_date;
            }
        }

        if (! empty($order->tracking_number) && in_array($order->status, ['shipped', 'delivered', 'completed'], true)) {
            $lines[] = '*'.__('chatbot.tracking.label_shipment_tracking').'* '.$order->tracking_number;
        }

        $trackUrl = route('spare-parts.track', ['token' => $order->public_token], true);
        $lines[] = '*'.__('chatbot.tracking.label_view_online').'* '.$trackUrl;

        return implode("\n", $lines);
    }

    protected function translateOrderStatus(string $status): string
    {
        $key = 'chatbot.order_status.'.$status;
        $trans = __($key);

        return $trans !== $key ? $trans : ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * If conversation somehow has tracking as selected_service, treat input as track lookup.
     */
    protected function handleTrackingServiceFollowUp(ChatbotConversation $conversation, string $message): ?string
    {
        return $this->handleTrackOrderInput($conversation, $message);
    }

    /**
     * True when user sends 0 (or 00 / 0.) to return to main menu. Must not match 4-digit OTPs like 0000.
     */
    protected function isNumericMainMenuShortcut(string $message): bool
    {
        $t = strtolower(trim($message));

        return in_array($t, ['0', '0.', '00', '00.'], true);
    }

    /**
     * Leave service_selected: main menu, or Kibo submenu if user opened service from there.
     */
    protected function goBackFromServiceToMenus(ChatbotConversation $conversation): string
    {
        $conversation->setContext('sparepart_substep', null);
        $conversation->setContext('cars_substep', null);

        if ($conversation->getContext('from_kibo_submenu')) {
            $conversation->setContext('from_kibo_submenu', false);
            $conversation->updateStep('kibo_services_menu', ['previous_step' => 'service_selected']);

            return $this->getKiboServicesMenuMessage();
        }

        $conversation->updateStep('main_menu', ['previous_step' => 'service_selected']);

        return $this->getMainMenu();
    }

    /**
     * Handle service selected
     */
    protected function handleServiceSelected(ChatbotConversation $conversation, string $message): ?string
    {
        $rawTrimmed = trim($message);
        $message = strtolower($rawTrimmed);

        // Check for back to menu (or Kibo submenu if user came from there). Include 0 = main menu (not language reset).
        if ($this->isNumericMainMenuShortcut($rawTrimmed) || in_array($message, ['back', 'menu', 'rudi', 'orodha'])) {
            return $this->goBackFromServiceToMenus($conversation);
        }

        // Check for reset/start over
        if (in_array($message, ['reset', 'start', 'new', 'anza', 'anza upya'])) {
            $conversation->reset();

            return $this->getWelcomeMessage();
        }

        $serviceKey = $conversation->getContext('selected_service');
        $service = collect($this->services)->firstWhere('key', $serviceKey);

        if (! $service) {
            $conversation->updateStep('main_menu', ['previous_step' => 'service_selected', 'error' => 'service_not_found']);

            return $this->getMainMenu();
        }

        // Special handling for spare parts - automatically start order flow
        if ($serviceKey === 'spare_parts') {
            $subStep = $conversation->getContext('sparepart_substep');
            // If not already in order flow, start it immediately
            if (! $subStep) {
                $orderService = new \App\Services\SparePartOrderChatbotService;
                $conversation->setContext('sparepart_substep', 'start');

                return $orderService->handleOrderFlow($conversation, $message);
            }
        }

        // Handle service-specific actions
        return $this->handleServiceAction($conversation, $service, $message);
    }

    /**
     * Handle default/unknown messages
     */
    protected function handleDefault(ChatbotConversation $conversation, string $message): ?string
    {
        // Check if session expired (should already be handled in processMessage, but double-check)
        if ($conversation->isExpired()) {
            $conversation->reset();

            return $this->getWelcomeMessage();
        }

        // If we're in an unknown state, try to route to main menu
        // This handles edge cases where step might be null or invalid
        $conversation->updateStep('main_menu');

        return $this->getMainMenu();
    }

    /**
     * Handle service-specific actions
     */
    protected function handleServiceAction(ChatbotConversation $conversation, array $service, string $message): ?string
    {
        $serviceKey = $service['key'];

        // Route to service-specific handler
        return match ($serviceKey) {
            'cars' => $this->handleCarsFlow($conversation, $message),
            'trucks' => $this->handleTrucksFlow($conversation, $message),
            'spare_parts' => $this->handleSparePartsFlow($conversation, $message),
            'garage' => $this->handleGarageFlow($conversation, $message),
            'leasing' => $this->handleLeasingFlow($conversation, $message),
            'financing' => $this->handleFinancingFlow($conversation, $message),
            'valuation' => $this->handleValuationFlow($conversation, $message),
            'sell' => $this->handleSellFlow($conversation, $message),
            'faq' => $this->handleFaqFlow($conversation, $message),
            'tracking' => $this->handleTrackingServiceFollowUp($conversation, $message),
            default => $this->getServiceDetails($service)."\n\n".__('chatbot.service_help')."\n".__('chatbot.visit_website').': '.$service['url'],
        };
    }

    /**
     * Handle cars service flow
     */
    protected function handleCarsFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $rawTrimmed = trim($message);
        $lower = strtolower($rawTrimmed);
        if ($this->isNumericMainMenuShortcut($rawTrimmed) || in_array($lower, ['back', 'menu', 'rudi', 'orodha'])) {
            return $this->goBackFromServiceToMenus($conversation);
        }

        $subStep = $conversation->getContext('cars_substep', 'menu');

        return match ($subStep) {
            'menu' => $this->getCarsMenu($conversation),
            'search' => $this->handleCarsSearch($conversation, $message),
            'browse' => $this->handleCarsBrowse($conversation, $message),
            default => $this->getCarsMenu($conversation),
        };
    }

    /**
     * Get cars menu
     */
    protected function getCarsMenu(ChatbotConversation $conversation): string
    {
        $conversation->setContext('cars_substep', 'menu');

        $message = __('chatbot.service.cars')."\n\n";
        $message .= __('chatbot.cars.menu_title')."\n\n";
        $message .= '1. '.__('chatbot.cars.search')."\n";
        $message .= '2. '.__('chatbot.cars.browse_new')."\n";
        $message .= '3. '.__('chatbot.cars.browse_used')."\n";
        $message .= '4. '.__('chatbot.cars.sell_car')."\n";
        $message .= '5. '.__('chatbot.cars.value_car')."\n";
        $message .= '6. '.__('chatbot.cars.insurance')."\n\n";
        $message .= __('chatbot.reply_with_number');

        return $message;
    }

    /**
     * Handle cars search
     */
    protected function handleCarsSearch(ChatbotConversation $conversation, string $message): ?string
    {
        $conversation->setContext('cars_substep', 'search');

        return __('chatbot.cars.search_prompt')."\n".__('chatbot.visit_website').': '.config('app.url').'/cars/search';
    }

    /**
     * Handle cars browse
     */
    protected function handleCarsBrowse(ChatbotConversation $conversation, string $message): ?string
    {
        $message = strtolower(trim($message));

        if ($message === '2') {
            $conversation->setContext('cars_substep', 'browse');

            return __('chatbot.cars.browse_new_prompt')."\n".__('chatbot.visit_website').': '.config('app.url').'/cars/new';
        }

        if ($message === '3') {
            $conversation->setContext('cars_substep', 'browse');

            return __('chatbot.cars.browse_used_prompt')."\n".__('chatbot.visit_website').': '.config('app.url').'/cars/used';
        }

        return $this->getCarsMenu($conversation);
    }

    /**
     * Handle trucks service flow
     */
    protected function handleTrucksFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'trucks'));
        $response .= "\n\n".__('chatbot.service_help');

        return $response;
    }

    /**
     * Handle spare parts service flow
     */
    protected function handleSparePartsFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $subStep = $conversation->getContext('sparepart_substep');

        // If already in order flow, use the order service
        if ($subStep && $subStep !== 'start') {
            $orderService = new \App\Services\SparePartOrderChatbotService;

            return $orderService->handleOrderFlow($conversation, $message);
        }

        // If no substep is set, this is the first time selecting spare parts
        // Automatically start the order flow
        if (! $subStep) {
            $orderService = new \App\Services\SparePartOrderChatbotService;
            $conversation->setContext('sparepart_substep', 'start');

            return $orderService->handleOrderFlow($conversation, $message);
        }

        // Check if user wants to start ordering (for cases where they might have cancelled)
        $messageLower = strtolower(trim($message));
        $orderKeywords = ['order', 'buy', 'purchase', 'agizo', 'nunua', 'omba', 'yes', 'y', 'ndiyo', 'ndio', '1'];

        // Check if user wants to order
        $wantsToOrder = false;
        foreach ($orderKeywords as $keyword) {
            if ($messageLower === $keyword || str_contains($messageLower, $keyword)) {
                $wantsToOrder = true;
                break;
            }
        }

        // If user explicitly wants to order, start order flow
        if ($wantsToOrder) {
            $orderService = new \App\Services\SparePartOrderChatbotService;
            $conversation->setContext('sparepart_substep', 'start');

            return $orderService->handleOrderFlow($conversation, $message);
        }

        // Otherwise show service details with order option
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'spare_parts'));
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';
        $response .= "\n\n".($locale === 'sw'
            ? "Andika 'agizo' au 'nunua' au 'ndiyo' ili kuanza kuagiza sehemu za ziada."
            : "Type 'order' or 'buy' or 'yes' to start ordering spare parts.");

        return $response;
    }

    /**
     * Handle garage service flow
     */
    protected function handleGarageFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'garage'));
        $response .= "\n\n".__('chatbot.service_help');

        return $response;
    }

    /**
     * Handle leasing service flow
     */
    protected function handleLeasingFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'leasing'));
        $response .= "\n\n".__('chatbot.service_help');

        return $response;
    }

    /**
     * Handle financing service flow
     */
    protected function handleFinancingFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'financing'));
        $response .= "\n\n".__('chatbot.service_help');

        return $response;
    }

    /**
     * Handle valuation service flow
     */
    protected function handleValuationFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'valuation'));
        $response .= "\n\n".__('chatbot.service_help');

        return $response;
    }

    /**
     * Handle sell service flow
     */
    protected function handleSellFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'sell'));
        $response .= "\n\n".__('chatbot.service_help');

        return $response;
    }

    /**
     * FAQ and contact information for Kibo Auto.
     */
    protected function handleFaqFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $baseUrl = config('app.url');
        $contact = config('kibo.contact', []);

        return __('chatbot.faq.full', [
            'url' => $baseUrl,
            'email' => $contact['email'] ?? '',
            'phone' => $contact['phone'] ?? '',
            'location' => $contact['location'] ?? '',
        ])."\n\n".__('chatbot.service_help');
    }

    /**
     * Get welcome message
     */
    protected function getWelcomeMessage(): string
    {
        return __('chatbot.welcome')."\n\n".$this->getLanguageSelectionMessage();
    }

    /**
     * Get language selection message
     */
    protected function getLanguageSelectionMessage(): string
    {
        // Return just the prompt - buttons will be added by TwilioService
        return __('chatbot.select_language');
    }

    /**
     * Get main menu
     */
    protected function getMainMenu(): string
    {
        $message = __('chatbot.main_menu_title')."\n\n";
        $message .= '0. '.__('chatbot.start_over')."\n";
        $message .= '1. '.__('chatbot.service.spare_parts')."\n";
        $message .= '2. '.__('chatbot.service.garage')."\n";
        $message .= '3. '.__('chatbot.service.tracking')."\n";
        $message .= '4. '.__('chatbot.service.faq')."\n";
        $message .= '5. '.__('chatbot.main_menu.kibo_services')."\n";
        $message .= "\n".__('chatbot.reply_with_number');

        return $message;
    }

    /**
     * Get service details
     */
    protected function getServiceDetails(array $service): string
    {
        $message = __($service['translation_key'])."\n\n";
        $message .= __($service['description_key'])."\n\n";
        $message .= __('chatbot.visit_website').': '.$service['url'];

        return $message;
    }

    /**
     * Check if message is a termination/reset request
     */
    protected function isTerminationRequest(string $message): bool
    {
        $normalized = strtolower(trim($message));

        // Exclude "resend" from termination keywords (it's used for OTP resend)
        if (in_array($normalized, ['resend', 'tuma tena', 'tuma upya', 'retry'])) {
            return false;
        }

        // English termination keywords
        $englishKeywords = [
            'reset', 'restart', 'start over', 'start again', 'new', 'new session',
            'clear', 'cancel', 'end', 'stop', 'exit', 'quit', 'bye', 'goodbye',
            'menu', 'main menu', 'home', 'back to start',
        ];

        // Swahili termination keywords
        $swahiliKeywords = [
            'anza', 'anza upya', 'anza tena', 'rudi', 'rudi mwanzo',
            'futa', 'ondoa', 'mwisho', 'acha', 'toka', 'kwaheri',
            'orodha', 'menyu', 'nyumbani', 'rudi nyumbani',
        ];

        // Check if message matches any termination keyword
        foreach ($englishKeywords as $keyword) {
            if ($normalized === $keyword || str_contains($normalized, $keyword)) {
                return true;
            }
        }

        foreach ($swahiliKeywords as $keyword) {
            if ($normalized === $keyword || str_contains($normalized, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get available services
     */
    protected function getAvailableServices(): array
    {
        $baseUrl = config('app.url');

        return [
            [
                'key' => 'cars',
                'name' => 'Cars',
                'translation_key' => 'chatbot.service.cars',
                'description_key' => 'chatbot.service.cars_description',
                'url' => $baseUrl.'/cars',
            ],
            [
                'key' => 'trucks',
                'name' => 'Trucks',
                'translation_key' => 'chatbot.service.trucks',
                'description_key' => 'chatbot.service.trucks_description',
                'url' => $baseUrl.'/trucks',
            ],
            [
                'key' => 'spare_parts',
                'name' => 'Spare Parts',
                'translation_key' => 'chatbot.service.spare_parts',
                'description_key' => 'chatbot.service.spare_parts_description',
                'url' => $baseUrl.'/spare-parts',
            ],
            [
                'key' => 'garage',
                'name' => 'Garage Services',
                'translation_key' => 'chatbot.service.garage',
                'description_key' => 'chatbot.service.garage_description',
                'url' => $baseUrl.'/garage',
            ],
            [
                'key' => 'tracking',
                'name' => 'Track order',
                'translation_key' => 'chatbot.service.tracking',
                'description_key' => 'chatbot.service.tracking_description',
                'url' => $baseUrl.'/spare-parts/track',
            ],
            [
                'key' => 'leasing',
                'name' => 'Vehicle Leasing',
                'translation_key' => 'chatbot.service.leasing',
                'description_key' => 'chatbot.service.leasing_description',
                'url' => $baseUrl.'/cars/leasing',
            ],
            [
                'key' => 'financing',
                'name' => 'Vehicle Financing',
                'translation_key' => 'chatbot.service.financing',
                'description_key' => 'chatbot.service.financing_description',
                'url' => $baseUrl.'/import-financing',
            ],
            [
                'key' => 'valuation',
                'name' => 'Vehicle Valuation',
                'translation_key' => 'chatbot.service.valuation',
                'description_key' => 'chatbot.service.valuation_description',
                'url' => $baseUrl.'/cars/value',
            ],
            [
                'key' => 'sell',
                'name' => 'Sell Your Vehicle',
                'translation_key' => 'chatbot.service.sell',
                'description_key' => 'chatbot.service.sell_description',
                'url' => $baseUrl.'/cars/sell',
            ],
            [
                'key' => 'faq',
                'name' => 'FAQ',
                'translation_key' => 'chatbot.service.faq',
                'description_key' => 'chatbot.service.faq_description',
                'url' => $baseUrl,
            ],
        ];
    }
}
