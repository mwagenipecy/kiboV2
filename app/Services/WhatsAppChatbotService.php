<?php

namespace App\Services;

use App\Models\ChatbotConversation;
use App\Services\TwilioService;
use App\Jobs\SendWhatsAppMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

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
        // Get or create conversation (this will check for expiration and reset if needed)
        $conversation = ChatbotConversation::getOrCreate($phoneNumber);
        
        // Check if session was expired and reset
        $wasExpired = $conversation->isExpired();
        if ($wasExpired) {
            Log::info('Chatbot session expired, resetting conversation', [
                'phone_number' => $phoneNumber,
                'last_interaction' => $conversation->last_interaction_at,
            ]);
            $conversation->reset();
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
        
        $responseMessage = match($currentStep) {
            'welcome' => $this->handleWelcome($conversation, $message),
            'language_selection' => $this->handleLanguageSelection($conversation, $message),
            'main_menu' => $this->handleMainMenu($conversation, $message),
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
        
        if (!$conversation) {
            Log::error('Failed to reload conversation after processing', [
                'phone_number' => $phoneNumber,
                'conversation_id' => $conversationId,
            ]);
            // Fallback: try to get it again
            $conversation = ChatbotConversation::where('phone_number', $phoneNumber)->first();
            
            if (!$conversation) {
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
        
        // Check if we should use buttons for language selection
        // Only add buttons if:
        // 1. Step is still language_selection AND
        // 2. Response message is the language selection message (not main menu)
        $useButtons = false;
        $buttons = [];
        
        $isLanguageSelectionMessage = str_contains($responseMessage, __('chatbot.select_language')) 
                                   || str_contains($responseMessage, __('chatbot.welcome'));
        $isMainMenuMessage = str_contains($responseMessage, __('chatbot.main_menu_title'));
        
        if ($conversation->current_step === 'language_selection' && $isLanguageSelectionMessage && !$isMainMenuMessage) {
            // Use buttons for language selection
            $useButtons = true;
            $buttons = [
                ['id' => '1', 'title' => __('common.english')],
                ['id' => '2', 'title' => __('common.swahili')],
            ];
            
            Log::info('Adding buttons to language selection message', [
                'phone_number' => $phoneNumber,
                'step' => $conversation->current_step,
            ]);
        } else {
            Log::info('Not adding buttons', [
                'phone_number' => $phoneNumber,
                'step' => $conversation->current_step,
                'is_language_selection_msg' => $isLanguageSelectionMessage,
                'is_main_menu_msg' => $isMainMenuMessage,
                'response_preview' => substr($responseMessage, 0, 50),
            ]);
        }

        return [
            'message' => $responseMessage,
            'use_buttons' => $useButtons,
            'buttons' => $buttons,
            'use_template' => false,
            'template_sid' => null,
        ];
    }

    /**
     * Handle welcome message
     */
    protected function handleWelcome(ChatbotConversation $conversation, string $message): ?string
    {
        // Normalize message for comparison
        $message = strtolower(trim($message));
        
        // Check if user wants to start/reset or sent a greeting
        $greetings = ['hi', 'hello', 'hey', 'start', 'hujambo', 'mambo', 'habari', 'salam', 'salaam'];
        
        if (in_array($message, $greetings)) {
            // User sent a greeting, move to language selection
            $conversation->updateStep('language_selection', [
                'greeting_received' => true,
                'greeting_at' => now()->toDateTimeString()
            ]);
            return $this->getWelcomeMessage();
        }

        // If not a recognized greeting, show welcome anyway and move to language selection
        // This handles cases where user sends something unexpected
        $conversation->updateStep('language_selection', [
            'unexpected_message' => $message,
            'greeting_at' => now()->toDateTimeString()
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
        }
        elseif ($normalizedMessage === '2' || in_array($normalizedMessage, ['2', 'sw', 'swahili', 'kiswahili'])) {
            $isSwahili = true;
        }
        elseif (preg_match('/^2\s*\.?\s*$/i', $trimmedMessage)) {
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

        // Invalid selection, show language menu again
        Log::warning('Invalid language selection', [
            'phone_number' => $conversation->phone_number,
            'message' => $message,
            'raw_message' => $rawMessage,
            'normalized' => $normalizedMessage,
            'message_bytes' => bin2hex($message), // Debug any hidden characters
        ]);
        
        return $this->getLanguageSelectionMessage();
    }

    /**
     * Handle main menu selection
     */
    protected function handleMainMenu(ChatbotConversation $conversation, string $message): ?string
    {
        $message = strtolower(trim($message));
        $selectedService = null;

        // Check if message matches a service number or name
        foreach ($this->services as $index => $service) {
            $serviceNumber = (string)($index + 1);
            $serviceName = strtolower($service['name']);
            $serviceNameTranslated = strtolower(__($service['translation_key']));
            
            if ($message === $serviceNumber || 
                $message === $serviceName || 
                $message === $serviceNameTranslated ||
                str_contains($message, $serviceName) ||
                str_contains($message, $serviceNameTranslated)) {
                $selectedService = $service;
                break;
            }
        }

        if ($selectedService) {
            $conversation->setContext('selected_service', $selectedService['key']);
            $conversation->updateStep('service_selected', [
                'selected_service' => $selectedService['key'],
                'selected_at' => now()->toDateTimeString()
            ]);
            return $this->getServiceDetails($selectedService);
        }

        // Invalid selection, show main menu again
        return $this->getMainMenu() . "\n\n" . __('chatbot.invalid_selection');
    }

    /**
     * Handle service selected
     */
    protected function handleServiceSelected(ChatbotConversation $conversation, string $message): ?string
    {
        $message = strtolower(trim($message));
        
        // Check for back to menu
        if (in_array($message, ['back', 'menu', 'rudi', 'orodha'])) {
            $conversation->updateStep('main_menu', ['previous_step' => 'service_selected']);
            return $this->getMainMenu();
        }

        // Check for reset/start over
        if (in_array($message, ['reset', 'start', 'new', 'anza', 'anza upya'])) {
            $conversation->reset();
            return $this->getWelcomeMessage();
        }

        $serviceKey = $conversation->getContext('selected_service');
        $service = collect($this->services)->firstWhere('key', $serviceKey);

        if (!$service) {
            $conversation->updateStep('main_menu', ['previous_step' => 'service_selected', 'error' => 'service_not_found']);
            return $this->getMainMenu();
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
        return match($serviceKey) {
            'cars' => $this->handleCarsFlow($conversation, $message),
            'trucks' => $this->handleTrucksFlow($conversation, $message),
            'spare_parts' => $this->handleSparePartsFlow($conversation, $message),
            'garage' => $this->handleGarageFlow($conversation, $message),
            'leasing' => $this->handleLeasingFlow($conversation, $message),
            'financing' => $this->handleFinancingFlow($conversation, $message),
            'valuation' => $this->handleValuationFlow($conversation, $message),
            'sell' => $this->handleSellFlow($conversation, $message),
            default => $this->getServiceDetails($service) . "\n\n" . __('chatbot.service_help') . "\n" . __('chatbot.visit_website') . ": " . $service['url'],
        };
    }

    /**
     * Handle cars service flow
     */
    protected function handleCarsFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $subStep = $conversation->getContext('cars_substep', 'menu');
        
        return match($subStep) {
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
        
        $message = __('chatbot.service.cars') . "\n\n";
        $message .= __('chatbot.cars.menu_title') . "\n\n";
        $message .= "1. " . __('chatbot.cars.search') . "\n";
        $message .= "2. " . __('chatbot.cars.browse_new') . "\n";
        $message .= "3. " . __('chatbot.cars.browse_used') . "\n";
        $message .= "4. " . __('chatbot.cars.sell_car') . "\n";
        $message .= "5. " . __('chatbot.cars.value_car') . "\n";
        $message .= "6. " . __('chatbot.cars.insurance') . "\n\n";
        $message .= __('chatbot.reply_with_number');
        
        return $message;
    }

    /**
     * Handle cars search
     */
    protected function handleCarsSearch(ChatbotConversation $conversation, string $message): ?string
    {
        $conversation->setContext('cars_substep', 'search');
        return __('chatbot.cars.search_prompt') . "\n" . __('chatbot.visit_website') . ": " . config('app.url') . '/cars/search';
    }

    /**
     * Handle cars browse
     */
    protected function handleCarsBrowse(ChatbotConversation $conversation, string $message): ?string
    {
        $message = strtolower(trim($message));
        
        if ($message === '2') {
            $conversation->setContext('cars_substep', 'browse');
            return __('chatbot.cars.browse_new_prompt') . "\n" . __('chatbot.visit_website') . ": " . config('app.url') . '/cars/new';
        }
        
        if ($message === '3') {
            $conversation->setContext('cars_substep', 'browse');
            return __('chatbot.cars.browse_used_prompt') . "\n" . __('chatbot.visit_website') . ": " . config('app.url') . '/cars/used';
        }
        
        return $this->getCarsMenu($conversation);
    }

    /**
     * Handle trucks service flow
     */
    protected function handleTrucksFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'trucks'));
        $response .= "\n\n" . __('chatbot.service_help');
        return $response;
    }

    /**
     * Handle spare parts service flow
     */
    protected function handleSparePartsFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'spare_parts'));
        $response .= "\n\n" . __('chatbot.service_help');
        return $response;
    }

    /**
     * Handle garage service flow
     */
    protected function handleGarageFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'garage'));
        $response .= "\n\n" . __('chatbot.service_help');
        return $response;
    }

    /**
     * Handle leasing service flow
     */
    protected function handleLeasingFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'leasing'));
        $response .= "\n\n" . __('chatbot.service_help');
        return $response;
    }

    /**
     * Handle financing service flow
     */
    protected function handleFinancingFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'financing'));
        $response .= "\n\n" . __('chatbot.service_help');
        return $response;
    }

    /**
     * Handle valuation service flow
     */
    protected function handleValuationFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'valuation'));
        $response .= "\n\n" . __('chatbot.service_help');
        return $response;
    }

    /**
     * Handle sell service flow
     */
    protected function handleSellFlow(ChatbotConversation $conversation, string $message): ?string
    {
        $response = $this->getServiceDetails(collect($this->services)->firstWhere('key', 'sell'));
        $response .= "\n\n" . __('chatbot.service_help');
        return $response;
    }

    /**
     * Get welcome message
     */
    protected function getWelcomeMessage(): string
    {
        return __('chatbot.welcome') . "\n\n" . $this->getLanguageSelectionMessage();
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
        $message = __('chatbot.main_menu_title') . "\n\n";
        
        foreach ($this->services as $index => $service) {
            $message .= ($index + 1) . ". " . __($service['translation_key']) . "\n";
        }
        
        $message .= "\n" . __('chatbot.reply_with_number');
        
        return $message;
    }

    /**
     * Get service details
     */
    protected function getServiceDetails(array $service): string
    {
        $message = __($service['translation_key']) . "\n\n";
        $message .= __($service['description_key']) . "\n\n";
        $message .= __('chatbot.visit_website') . ": " . $service['url'];
        
        return $message;
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
                'url' => $baseUrl . '/cars',
            ],
            [
                'key' => 'trucks',
                'name' => 'Trucks',
                'translation_key' => 'chatbot.service.trucks',
                'description_key' => 'chatbot.service.trucks_description',
                'url' => $baseUrl . '/trucks',
            ],
            [
                'key' => 'spare_parts',
                'name' => 'Spare Parts',
                'translation_key' => 'chatbot.service.spare_parts',
                'description_key' => 'chatbot.service.spare_parts_description',
                'url' => $baseUrl . '/spare-parts',
            ],
            [
                'key' => 'garage',
                'name' => 'Garage Services',
                'translation_key' => 'chatbot.service.garage',
                'description_key' => 'chatbot.service.garage_description',
                'url' => $baseUrl . '/garage',
            ],
            [
                'key' => 'leasing',
                'name' => 'Vehicle Leasing',
                'translation_key' => 'chatbot.service.leasing',
                'description_key' => 'chatbot.service.leasing_description',
                'url' => $baseUrl . '/cars/leasing',
            ],
            [
                'key' => 'financing',
                'name' => 'Vehicle Financing',
                'translation_key' => 'chatbot.service.financing',
                'description_key' => 'chatbot.service.financing_description',
                'url' => $baseUrl . '/import-financing',
            ],
            [
                'key' => 'valuation',
                'name' => 'Vehicle Valuation',
                'translation_key' => 'chatbot.service.valuation',
                'description_key' => 'chatbot.service.valuation_description',
                'url' => $baseUrl . '/cars/value',
            ],
            [
                'key' => 'sell',
                'name' => 'Sell Your Vehicle',
                'translation_key' => 'chatbot.service.sell',
                'description_key' => 'chatbot.service.sell_description',
                'url' => $baseUrl . '/cars/sell',
            ],
        ];
    }
}

