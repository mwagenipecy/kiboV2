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
     */
    public function processMessage(string $phoneNumber, string $message): void
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
        
        // Update last interaction timestamp (session is now active)
        $conversation->last_interaction_at = now();
        $conversation->is_active = true;
        $conversation->save();

        // Process based on current step
        $response = match($conversation->current_step) {
            'welcome' => $this->handleWelcome($conversation, $message),
            'language_selection' => $this->handleLanguageSelection($conversation, $message),
            'main_menu' => $this->handleMainMenu($conversation, $message),
            'service_selected' => $this->handleServiceSelected($conversation, $message),
            default => $this->handleDefault($conversation, $message),
        };

        // Send response
        if ($response) {
            SendWhatsAppMessage::dispatch($phoneNumber, $response);
            
            Log::info('Chatbot response sent', [
                'phone_number' => $phoneNumber,
                'step' => $conversation->current_step,
                'message_length' => strlen($response),
            ]);
        }
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
            $conversation->updateStep('language_selection');
            return $this->getWelcomeMessage();
        }

        // If not a recognized greeting, show welcome anyway and move to language selection
        // This handles cases where user sends something unexpected
        $conversation->updateStep('language_selection');
        return $this->getWelcomeMessage();
    }

    /**
     * Handle language selection
     */
    protected function handleLanguageSelection(ChatbotConversation $conversation, string $message): ?string
    {
        $message = strtolower(trim($message));
        
        // Check for language selection
        if (in_array($message, ['1', 'en', 'english', 'kiingereza'])) {
            $conversation->language = 'en';
            $conversation->save();
            App::setLocale('en');
            $conversation->updateStep('main_menu');
            return $this->getMainMenu();
        }
        
        if (in_array($message, ['2', 'sw', 'swahili', 'kiswahili'])) {
            $conversation->language = 'sw';
            $conversation->save();
            App::setLocale('sw');
            $conversation->updateStep('main_menu');
            return $this->getMainMenu();
        }

        // Invalid selection, show language menu again
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
            $conversation->updateStep('service_selected');
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
            $conversation->updateStep('main_menu');
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
            $conversation->updateStep('main_menu');
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
        // For now, provide basic information and link
        // You can extend this to handle specific queries, searches, etc.
        
        $response = $this->getServiceDetails($service);
        $response .= "\n\n" . __('chatbot.service_help');
        $response .= "\n" . __('chatbot.visit_website') . ": " . $service['url'];
        
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
        $message = __('chatbot.select_language') . "\n\n";
        $message .= "1. " . __('common.english') . "\n";
        $message .= "2. " . __('common.swahili') . "\n\n";
        $message .= __('chatbot.reply_with_number');
        
        return $message;
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

