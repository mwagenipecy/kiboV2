<?php

namespace App\Services;

use App\Jobs\SendOtpSms;
use App\Jobs\SendSparePartOrderConfirmationEmail;
use App\Models\ChatbotConversation;
use App\Models\SparePartOrder;
use App\Models\User;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Log;

class SparePartOrderChatbotService
{
    /**
     * Handle sparepart order flow
     */
    public function handleOrderFlow(ChatbotConversation $conversation, string $message): string
    {
        $subStep = $conversation->getContext('sparepart_substep', 'start');

        return match ($subStep) {
            'start' => $this->handleStart($conversation, $message),
            'same_whatsapp_phone_question' => $this->handleSameWhatsappPhoneQuestion($conversation, $message),
            'contact_phone_request' => $this->handleContactPhoneRequest($conversation, $message),
            'otp_verification' => $this->handleOtpVerification($conversation, $message),
            'vehicle_make' => $this->handleVehicleMake($conversation, $message),
            'vehicle_model' => $this->handleVehicleModel($conversation, $message),
            'part_name' => $this->handlePartName($conversation, $message),
            'has_image' => $this->handleHasImage($conversation, $message),
            'image_upload' => $this->handleImageUpload($conversation, $message),
            'delivery_description' => $this->handleDeliveryDescription($conversation, $message),
            'part_explanation' => $this->handlePartExplanation($conversation, $message),
            'add_more' => $this->handleAddMore($conversation, $message),
            'review_orders' => $this->handleReviewOrders($conversation, $message),
            'confirm_orders' => $this->handleConfirmOrders($conversation, $message),
            default => $this->handleStart($conversation, $message),
        };
    }

    /**
     * Start the order flow
     */
    protected function handleStart(ChatbotConversation $conversation, string $message): string
    {
        $conversation->setContext('sparepart_orders', []);
        $conversation->setContext('sparepart_otp', null);
        $conversation->setContext('sparepart_otp_expires_at', null);
        $conversation->setContext('sparepart_contact_phone', null);
        $conversation->setContext('sparepart_verified_via_whatsapp', null);
        $conversation->setContext('sparepart_used_sms_otp', null);
        $conversation->setContext('sparepart_substep', 'same_whatsapp_phone_question');

        $locale = $conversation->language === 'sw' ? 'sw' : 'en';
        $display = $this->formatPhoneForDisplay($this->normalizedConversationPhone($conversation));

        return $locale === 'sw'
            ? "Karibu! Ili kuendelea na agizo la sehemu za ziada, je nambari ya simu ya mawasiliano na utoaji ni *hiyo hiyo* na nambari ya WhatsApp unayotumia sasa ({$display})?\n\nJibu *NDIYO* au *HAPANA*.\n\nAu jibu *99*, *menu*, au *rudi* kurudi menyu kuu."
            : "Welcome! To continue your spare part order, is your *contact and delivery phone number the same* as this WhatsApp number ({$display})?\n\nReply *YES* or *NO*.\n\nOr reply *99*, *menu*, or *back* to return to the main menu.";
    }

    /**
     * Ask whether contact phone matches WhatsApp; if yes, skip SMS OTP.
     */
    protected function handleSameWhatsappPhoneQuestion(ChatbotConversation $conversation, string $message): string
    {
        $trimmed = trim($message);
        $lower = strtolower($trimmed);
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        if (in_array($lower, ['back', 'cancel', 'rudi', 'ghairi', 'menu']) || $this->isBackToMainMenuCommand($trimmed)) {
            $conversation->setContext('sparepart_substep', null);
            $conversation->updateStep('main_menu');

            return $this->getMainMenuMessage($conversation);
        }

        $yes = in_array($lower, ['yes', 'y', '1', 'ndiyo', 'ndio', 'sawa', 'ndio ndiyo', 'same', 'sawa sawa', 'nd'])
            || $trimmed === '1';
        $no = in_array($lower, ['no', 'n', '2', 'hapana', 'different', 'tofauti', 'la'])
            || $trimmed === '2';

        if (! $yes && ! $no) {
            return $locale === 'sw'
                ? "Tafadhali jibu *NDIYO* ikiwa nambari ni ile ile, au *HAPANA* ikiwa ni nambari tofauti.\n\nAu jibu *99*, *menu*, au *rudi* kurudi menyu kuu."
                : "Please reply *YES* if it is the same number, or *NO* if you use a different contact number.\n\nOr reply *99*, *menu*, or *back* to return to the main menu.";
        }

        if ($yes) {
            $normalized = $this->normalizedConversationPhone($conversation);
            if ($normalized === '') {
                return $locale === 'sw'
                    ? 'Hatukuweza kutambua nambari ya WhatsApp yako. Tafadhali chagua HAPANA na uingize nambari ya simu ya mawasiliano.'
                    : 'We could not detect your WhatsApp number. Please reply NO and enter your contact phone number.';
            }

            $conversation->setContext('sparepart_contact_phone', $normalized);
            $conversation->setContext('sparepart_verified_via_whatsapp', true);
            $conversation->setContext('sparepart_used_sms_otp', false);

            return $this->proceedToVehicleMakeSelection($conversation);
        }

        $conversation->setContext('sparepart_substep', 'contact_phone_request');

        return $locale === 'sw'
            ? "Sawa. Tafadhali ingiza nambari yako ya simu ya mawasiliano (mfano 07XXXXXXXX au +2557XXXXXXXX). Tutatuma nambari ya uthibitisho (OTP) kwa SMS.\n\nAu andika 'rudi' kuuliza swali tena."
            : "Okay. Please enter your contact phone number (e.g. 07XXXXXXXX or +2557XXXXXXXX). We will send a verification code (OTP) by SMS.\n\nOr type 'back' to change your answer.";
    }

    /**
     * Collect alternate contact phone and send SMS OTP only.
     */
    protected function handleContactPhoneRequest(ChatbotConversation $conversation, string $message): string
    {
        $trimmed = trim($message);
        $lower = strtolower($trimmed);
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        if ($this->isBackToMainMenuCommand($trimmed) || in_array($lower, ['menu'])) {
            $conversation->setContext('sparepart_substep', null);
            $conversation->setContext('sparepart_otp', null);
            $conversation->setContext('sparepart_otp_expires_at', null);
            $conversation->updateStep('main_menu');

            return $this->getMainMenuMessage($conversation);
        }

        if (in_array($lower, ['back', 'cancel', 'rudi', 'ghairi'])) {
            $conversation->setContext('sparepart_substep', 'same_whatsapp_phone_question');
            $display = $this->formatPhoneForDisplay($this->normalizedConversationPhone($conversation));

            return $locale === 'sw'
                ? "Je nambari ya mawasiliano ni ile ile na WhatsApp ({$display})? Jibu NDIYO au HAPANA."
                : "Is your contact number the same as this WhatsApp number ({$display})? Reply YES or NO.";
        }

        $sms = app(SelcomSmsService::class);
        $normalized = $sms->normalizeTanzaniaNumber($trimmed);

        if ($normalized === '') {
            return $locale === 'sw'
                ? 'Nambari si sahihi. Tafadhali tumia nambari ya Tanzania (07XXXXXXXX au +2557XXXXXXXX).'
                : 'That phone number is not valid. Please use a Tanzania number (07XXXXXXXX or +2557XXXXXXXX).';
        }

        $conversation->setContext('sparepart_contact_phone', $normalized);
        $conversation->setContext('sparepart_verified_via_whatsapp', false);

        return $this->issueAndSendOtp($conversation, false);
    }

    /**
     * Resend OTP
     */
    protected function resendOtp(ChatbotConversation $conversation): string
    {
        $phone = $conversation->getContext('sparepart_contact_phone');
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';
        if (! $phone) {
            $conversation->setContext('sparepart_substep', 'contact_phone_request');

            return $locale === 'sw'
                ? 'Nambari ya simu haijasajiliwa. Tafadhali ingiza nambari ya mawasiliano.'
                : 'Phone number not set. Please enter your contact phone number.';
        }

        return $this->issueAndSendOtp($conversation, true);
    }

    /**
     * Get main menu message (helper method)
     */
    protected function getMainMenuMessage(ChatbotConversation $conversation): string
    {
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        // This will be handled by WhatsAppChatbotService, but we return a simple message
        return $locale === 'sw'
            ? 'Umerudi kwenye menyu kuu.'
            : "You've returned to the main menu.";
    }

    /**
     * Handle OTP verification
     */
    protected function handleOtpVerification(ChatbotConversation $conversation, string $message): string
    {
        $message = strtolower(trim($message));
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        // Check for resend request
        if (in_array($message, ['resend', 'send again', 'tuma tena', 'tuma upya', 'retry'])) {
            return $this->resendOtp($conversation);
        }

        // Main menu shortcut 99 — never matches a 4-digit OTP
        if ($this->isBackToMainMenuCommand($message) || $message === 'menu') {
            $conversation->setContext('sparepart_otp', null);
            $conversation->setContext('sparepart_otp_expires_at', null);
            $conversation->setContext('sparepart_substep', null);
            $conversation->updateStep('main_menu');

            return $this->getMainMenuMessage($conversation);
        }

        // Check for back/cancel
        if (in_array($message, ['back', 'cancel', 'rudi', 'ghairi'])) {
            $conversation->setContext('sparepart_otp', null);
            $conversation->setContext('sparepart_otp_expires_at', null);
            $conversation->setContext('sparepart_substep', 'contact_phone_request');

            return $locale === 'sw'
                ? 'Umerudi nyuma. Tafadhali ingiza nambari ya simu ya mawasiliano tena (tutatuma OTP kwa SMS).'
                : 'You went back. Please enter your contact phone number again (we will send an OTP by SMS).';
        }

        $otpCode = trim($message);
        $storedOtp = $conversation->getContext('sparepart_otp');
        $otpExpiresAtStr = $conversation->getContext('sparepart_otp_expires_at');

        // Check if OTP expired (parse the datetime string)
        if ($otpExpiresAtStr) {
            try {
                $otpExpiresAt = \Carbon\Carbon::parse($otpExpiresAtStr);
                if (now()->greaterThan($otpExpiresAt)) {
                    return $locale === 'sw'
                        ? "Nambari ya uthibitisho imeisha muda wake. Andika 'tuma tena' ili kupata nambari mpya."
                        : "The verification code has expired. Type 'resend' to get a new code.";
                }
            } catch (\Exception $e) {
                Log::warning('Failed to parse OTP expiration date: '.$e->getMessage());
            }
        }

        // Check if OTP matches
        if ($otpCode !== $storedOtp) {
            return $locale === 'sw'
                ? "Nambari ya uthibitisho si sahihi. Tafadhali jaribu tena. Au andika 'tuma tena' ili kupata nambari mpya."
                : "The verification code is incorrect. Please try again. Or type 'resend' to get a new code.";
        }

        // OTP verified, clear it and move to vehicle make selection
        $conversation->setContext('sparepart_otp', null);
        $conversation->setContext('sparepart_otp_expires_at', null);
        $conversation->setContext('sparepart_used_sms_otp', true);

        return $this->proceedToVehicleMakeSelection($conversation);
    }

    protected function proceedToVehicleMakeSelection(ChatbotConversation $conversation): string
    {
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';
        $conversation->setContext('sparepart_substep', 'vehicle_make');

        $makes = VehicleMake::active()->orderBy('name')->get();

        if ($makes->isEmpty()) {
            return $locale === 'sw'
                ? 'Hakuna aina za gari zilizopo kwa sasa. Tafadhali wasiliana nasi moja kwa moja.'
                : 'No vehicle makes available at the moment. Please contact us directly.';
        }

        $header = $locale === 'sw'
            ? "✓ Nambari ya simu imethibitishwa!\n\nTafadhali chagua aina ya gari (Make):\n\n"
            : "✓ Phone number verified!\n\nPlease select the vehicle make:\n\n";

        $message = $header;
        foreach ($makes as $index => $make) {
            $message .= ($index + 1).'. '.$make->name."\n";
        }

        $message .= "\n".($locale === 'sw' ? 'Jibu kwa nambari au jina la aina ya gari.' : 'Reply with the number or name of the vehicle make.');

        $conversation->setContext('sparepart_makes', $makes->pluck('id', 'name')->toArray());

        return $message;
    }

    protected function issueAndSendOtp(ChatbotConversation $conversation, bool $isResend): string
    {
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';
        $phoneNumber = $conversation->getContext('sparepart_contact_phone');

        if (empty($phoneNumber)) {
            $conversation->setContext('sparepart_substep', 'contact_phone_request');

            return $locale === 'sw'
                ? 'Tafadhali ingiza nambari ya simu ya mawasiliano kwanza.'
                : 'Please enter your contact phone number first.';
        }

        $otpCode = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $conversation->setContext('sparepart_otp', $otpCode);
        $conversation->setContext('sparepart_otp_expires_at', now()->addMinutes(10)->toDateTimeString());

        try {
            SendOtpSms::dispatchSync($phoneNumber, $otpCode);
        } catch (\Exception $e) {
            Log::error('Failed to dispatch sparepart OTP SMS: '.$e->getMessage(), [
                'phone' => $phoneNumber,
            ]);

            return $locale === 'sw'
                ? 'Kumekuwa na hitilafu katika kutuma nambari ya uthibitisho. Tafadhali jaribu tena baadaye.'
                : 'There was an error sending the verification code. Please try again later.';
        }

        $conversation->setContext('sparepart_substep', 'otp_verification');

        if ($isResend) {
            return $locale === 'sw'
                ? "✓ Nambari mpya ya uthibitisho imetumwa kwa SMS kwenye simu yako.\n\nTafadhali ingiza nambari ya uthibitisho (tarakimu 4)."
                : "✓ A new verification code has been sent by SMS to your phone.\n\nPlease enter the verification code (4 digits).";
        }

        return $locale === 'sw'
            ? "Tumetuma nambari ya uthibitisho kwa SMS kwenye simu uliyoingiza.\n\nTafadhali ingiza nambari ya uthibitisho (tarakimu 4).\n\nIkiwa hujapokea, andika 'tuma tena'."
            : "We've sent a verification code by SMS to the number you entered.\n\nPlease enter the verification code (4 digits).\n\nIf you didn't receive it, type 'resend'.";
    }

    protected function normalizedConversationPhone(ChatbotConversation $conversation): string
    {
        return app(SelcomSmsService::class)->normalizeTanzaniaNumber($conversation->phone_number ?? '');
    }

    protected function formatPhoneForDisplay(string $normalized255): string
    {
        if (strlen($normalized255) === 12 && str_starts_with($normalized255, '255')) {
            return '0'.substr($normalized255, 3);
        }

        return $normalized255 !== '' ? $normalized255 : '—';
    }

    /**
     * Handle vehicle make selection
     */
    protected function handleVehicleMake(ChatbotConversation $conversation, string $message): string
    {
        $messageLower = strtolower(trim($message));
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        // Check for back/cancel
        if (in_array($messageLower, ['back', 'cancel', 'rudi', 'ghairi'])) {
            if ($conversation->getContext('sparepart_used_sms_otp')) {
                $conversation->setContext('sparepart_substep', 'otp_verification');

                return $locale === 'sw'
                    ? 'Umerudi nyuma. Tafadhali ingiza nambari ya uthibitisho (OTP).'
                    : 'You went back. Please enter the verification code (OTP).';
            }

            $conversation->setContext('sparepart_substep', 'same_whatsapp_phone_question');
            $display = $this->formatPhoneForDisplay($this->normalizedConversationPhone($conversation));

            return $locale === 'sw'
                ? "Umerudi nyuma. Je nambari ya mawasiliano ni ile ile na WhatsApp ({$display})? Jibu NDIYO au HAPANA."
                : "You went back. Is your contact number the same as this WhatsApp number ({$display})? Reply YES or NO.";
        }

        $message = trim($message);
        $makes = VehicleMake::active()->orderBy('name')->get();
        $selectedMake = null;

        // Try to find by number
        if (is_numeric($message)) {
            $index = (int) $message - 1;
            if ($index >= 0 && $index < $makes->count()) {
                $selectedMake = $makes[$index];
            }
        } else {
            // Try to find by name
            $selectedMake = $makes->first(function ($make) use ($message) {
                return stripos($make->name, $message) !== false;
            });
        }

        if (! $selectedMake) {
            // Show list again with better message
            $response = $locale === 'sw'
                ? "Aina ya gari haijapatikana. Tafadhali chagua nambari au jina kutoka kwenye orodha hapa chini:\n\n"
                : "Vehicle make not found. Please select a number or name from the list below:\n\n";

            foreach ($makes as $index => $make) {
                $response .= ($index + 1).'. '.$make->name."\n";
            }

            $response .= "\n".($locale === 'sw' ? 'Jibu kwa nambari au jina la aina ya gari.' : 'Reply with the number or name of the vehicle make.');

            return $response;
        }

        // Store selected make
        $conversation->setContext('sparepart_current_make_id', $selectedMake->id);
        $conversation->setContext('sparepart_current_make_name', $selectedMake->name);

        // Get models for this make
        $models = VehicleModel::where('vehicle_make_id', $selectedMake->id)
            ->orderBy('name')
            ->get();

        if ($models->isEmpty()) {
            return $locale === 'sw'
                ? "Hakuna mifano ya gari inapatikana kwa {$selectedMake->name}. Tafadhali chagua aina nyingine ya gari."
                : "No vehicle models available for {$selectedMake->name}. Please select another vehicle make.";
        }

        $response = $locale === 'sw'
            ? "✓ Aina ya gari: {$selectedMake->name}\n\nTafadhali chagua modeli ya gari:\n\n"
            : "✓ Vehicle make: {$selectedMake->name}\n\nPlease select the vehicle model:\n\n";

        foreach ($models as $index => $model) {
            $response .= ($index + 1).'. '.$model->name."\n";
        }

        $response .= "\n".($locale === 'sw' ? 'Jibu kwa nambari au jina la modeli.' : 'Reply with the number or name of the model.');

        $conversation->setContext('sparepart_substep', 'vehicle_model');
        $conversation->setContext('sparepart_models', $models->pluck('id', 'name')->toArray());

        return $response;
    }

    /**
     * Handle vehicle model selection
     */
    protected function handleVehicleModel(ChatbotConversation $conversation, string $message): string
    {
        $messageLower = strtolower(trim($message));
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        // Check for back/cancel
        if (in_array($messageLower, ['back', 'cancel', 'rudi', 'ghairi'])) {
            $conversation->setContext('sparepart_substep', 'vehicle_make');
            $makes = VehicleMake::active()->orderBy('name')->get();

            $response = $locale === 'sw'
                ? "Umerudi nyuma. Tafadhali chagua aina ya gari:\n\n"
                : "You went back. Please select the vehicle make:\n\n";

            foreach ($makes as $index => $make) {
                $response .= ($index + 1).'. '.$make->name."\n";
            }

            $response .= "\n".($locale === 'sw' ? 'Jibu kwa nambari au jina la aina ya gari.' : 'Reply with the number or name of the vehicle make.');

            return $response;
        }

        $message = trim($message);
        $makeId = $conversation->getContext('sparepart_current_make_id');
        $models = VehicleModel::where('vehicle_make_id', $makeId)->orderBy('name')->get();
        $selectedModel = null;

        // Try to find by number
        if (is_numeric($message)) {
            $index = (int) $message - 1;
            if ($index >= 0 && $index < $models->count()) {
                $selectedModel = $models[$index];
            }
        } else {
            // Try to find by name
            $selectedModel = $models->first(function ($model) use ($message) {
                return stripos($model->name, $message) !== false;
            });
        }

        if (! $selectedModel) {
            // Show list again with better message
            $makeName = $conversation->getContext('sparepart_current_make_name');
            $response = $locale === 'sw'
                ? "Modeli ya gari haijapatikana. Tafadhali chagua nambari au jina kutoka kwenye orodha hapa chini:\n\n"
                : "Vehicle model not found. Please select a number or name from the list below:\n\n";

            foreach ($models as $index => $model) {
                $response .= ($index + 1).'. '.$model->name."\n";
            }

            $response .= "\n".($locale === 'sw' ? 'Jibu kwa nambari au jina la modeli.' : 'Reply with the number or name of the model.');

            return $response;
        }

        // Store selected model
        $conversation->setContext('sparepart_current_model_id', $selectedModel->id);
        $conversation->setContext('sparepart_current_model_name', $selectedModel->name);
        $conversation->setContext('sparepart_substep', 'part_name');

        $makeName = $conversation->getContext('sparepart_current_make_name');

        return $locale === 'sw'
            ? "✓ Modeli: {$selectedModel->name}\n\nTafadhali ingiza jina la sehemu ya ziada unayohitaji:"
            : "✓ Model: {$selectedModel->name}\n\nPlease enter the name of the spare part you need:";
    }

    /**
     * Handle part name input
     */
    protected function handlePartName(ChatbotConversation $conversation, string $message): string
    {
        $messageLower = strtolower(trim($message));
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        // Check for back/cancel
        if (in_array($messageLower, ['back', 'cancel', 'rudi', 'ghairi'])) {
            $makeId = $conversation->getContext('sparepart_current_make_id');
            $models = VehicleModel::where('vehicle_make_id', $makeId)->orderBy('name')->get();

            $response = $locale === 'sw'
                ? "Umerudi nyuma. Tafadhali chagua modeli ya gari:\n\n"
                : "You went back. Please select the vehicle model:\n\n";

            foreach ($models as $index => $model) {
                $response .= ($index + 1).'. '.$model->name."\n";
            }

            $response .= "\n".($locale === 'sw' ? 'Jibu kwa nambari au jina la modeli.' : 'Reply with the number or name of the model.');
            $conversation->setContext('sparepart_substep', 'vehicle_model');

            return $response;
        }

        $partName = trim($message);

        if (empty($partName)) {
            return $locale === 'sw'
                ? 'Jina la sehemu ya ziada ni lazima. Tafadhali ingiza jina la sehemu:'
                : 'Spare part name is required. Please enter the part name:';
        }

        // Store part name
        $conversation->setContext('sparepart_current_part_name', $partName);
        $conversation->setContext('sparepart_substep', 'has_image');

        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        return $locale === 'sw'
            ? "✓ Jina la sehemu: {$partName}\n\nJe, una picha ya sehemu hii? (Jibu 'ndiyo' au 'hapana')"
            : "✓ Part name: {$partName}\n\nDo you have an image of this part? (Reply 'yes' or 'no')";
    }

    /**
     * Handle has image question
     */
    protected function handleHasImage(ChatbotConversation $conversation, string $message): string
    {
        $message = strtolower(trim($message));
        $hasImage = in_array($message, ['yes', 'y', 'ndiyo', 'ndio', '1']);

        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        if ($hasImage) {
            $conversation->setContext('sparepart_current_has_image', true);
            $conversation->setContext('sparepart_substep', 'image_upload');
            $conversation->setContext('sparepart_current_images', []);

            return $locale === 'sw'
                ? "Tafadhali tuma picha ya sehemu ya ziada. Unaweza kutuma picha moja au zaidi. Baada ya kutuma picha, andika 'tayari' au 'done'."
                : "Please send the image(s) of the spare part. You can send one or more images. After sending images, type 'done' or 'ready'.";
        } else {
            $conversation->setContext('sparepart_current_has_image', false);
            $conversation->setContext('sparepart_current_images', []);
            $conversation->setContext('sparepart_substep', 'delivery_description');

            return $locale === 'sw'
                ? 'Sawa. Tafadhali toa maelezo ya uwasilishaji (anwani kamili ya uwasilishaji):'
                : 'Okay. Please provide delivery description (full delivery address):';
        }
    }

    /**
     * Handle image upload (for WhatsApp, we'll note that images were sent)
     */
    protected function handleImageUpload(ChatbotConversation $conversation, string $message): string
    {
        $message = strtolower(trim($message));
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        // Check if user says done/ready
        if (in_array($message, ['done', 'ready', 'tayari', 'tayarisha', 'complete', 'finish'])) {
            $conversation->setContext('sparepart_substep', 'delivery_description');

            return $locale === 'sw'
                ? 'Asante kwa picha. Tafadhali toa maelezo ya uwasilishaji (anwani kamili ya uwasilishaji):'
                : 'Thank you for the images. Please provide delivery description (full delivery address):';
        }

        // If message is not done, assume they're still sending images
        // In a real implementation, you'd handle image attachments here
        // For now, we'll just acknowledge and ask them to type 'done' when finished
        return $locale === 'sw'
            ? "Picha imepokelewa. Unaweza kuendelea kutuma picha zaidi, au andika 'tayari' ili kuendelea."
            : "Image received. You can continue sending more images, or type 'done' to continue.";
    }

    /**
     * Handle delivery description
     */
    protected function handleDeliveryDescription(ChatbotConversation $conversation, string $message): string
    {
        $messageLower = strtolower(trim($message));
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        // Check for back/cancel
        if (in_array($messageLower, ['back', 'cancel', 'rudi', 'ghairi'])) {
            $conversation->setContext('sparepart_substep', 'has_image');
            $partName = $conversation->getContext('sparepart_current_part_name');

            return $locale === 'sw'
                ? "Umerudi nyuma.\n\nJe, una picha ya sehemu hii? (Jibu 'ndiyo' au 'hapana')"
                : "You went back.\n\nDo you have an image of this part? (Reply 'yes' or 'no')";
        }

        $deliveryDescription = trim($message);

        if (empty($deliveryDescription)) {
            return $locale === 'sw'
                ? 'Maelezo ya uwasilishaji ni lazima. Tafadhali toa anwani kamili ya uwasilishaji:'
                : 'Delivery description is required. Please provide the full delivery address:';
        }

        // Store delivery description
        $conversation->setContext('sparepart_current_delivery_description', $deliveryDescription);
        $conversation->setContext('sparepart_substep', 'part_explanation');

        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        return $locale === 'sw'
            ? "✓ Maelezo ya uwasilishaji: {$deliveryDescription}\n\nTafadhali toa maelezo yoyote ya ziada kuhusu sehemu hii (au andika 'hakuna' ikiwa huna maelezo zaidi):"
            : "✓ Delivery description: {$deliveryDescription}\n\nPlease provide any additional explanation about this part (or type 'none' if you don't have additional details):";
    }

    /**
     * Handle part explanation
     */
    protected function handlePartExplanation(ChatbotConversation $conversation, string $message): string
    {
        $explanation = trim($message);

        // If user says none/no, set empty
        if (in_array(strtolower($explanation), ['none', 'no', 'hakuna', 'hapana', 'n/a', 'na'])) {
            $explanation = '';
        }

        // Save current order to the orders array
        $orders = $conversation->getContext('sparepart_orders', []);

        $order = [
            'make_id' => $conversation->getContext('sparepart_current_make_id'),
            'make_name' => $conversation->getContext('sparepart_current_make_name'),
            'model_id' => $conversation->getContext('sparepart_current_model_id'),
            'model_name' => $conversation->getContext('sparepart_current_model_name'),
            'part_name' => $conversation->getContext('sparepart_current_part_name'),
            'has_image' => $conversation->getContext('sparepart_current_has_image', false),
            'images' => $conversation->getContext('sparepart_current_images', []),
            'delivery_description' => $conversation->getContext('sparepart_current_delivery_description'),
            'explanation' => $explanation,
        ];

        $orders[] = $order;
        $conversation->setContext('sparepart_orders', $orders);

        // Clear current order context
        $this->clearCurrentOrderContext($conversation);

        // Ask if they want to add more
        $conversation->setContext('sparepart_substep', 'add_more');

        $locale = $conversation->language === 'sw' ? 'sw' : 'en';
        $orderCount = count($orders);

        return $locale === 'sw'
            ? "✓ Sehemu ya ziada imeongezwa kwenye agizo lako!\n\nJe, ungependa kuongeza sehemu nyingine? (Jibu 'ndiyo' au 'hapana')"
            : "✓ Spare part added to your order!\n\nWould you like to add another spare part? (Reply 'yes' or 'no')";
    }

    /**
     * Handle add more question
     */
    protected function handleAddMore(ChatbotConversation $conversation, string $message): string
    {
        $message = strtolower(trim($message));
        $addMore = in_array($message, ['yes', 'y', 'ndiyo', 'ndio', '1']);

        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        if ($addMore) {
            // Start new order flow
            $conversation->setContext('sparepart_substep', 'vehicle_make');

            // Get vehicle makes again
            $makes = VehicleMake::active()->orderBy('name')->get();

            if ($makes->isEmpty()) {
                return $locale === 'sw'
                    ? 'Hakuna aina za gari zilizopo kwa sasa.'
                    : 'No vehicle makes available at the moment.';
            }

            $response = $locale === 'sw'
                ? "Sawa! Tafadhali chagua aina ya gari kwa sehemu inayofuata:\n\n"
                : "Great! Please select the vehicle make for the next part:\n\n";

            foreach ($makes as $index => $make) {
                $response .= ($index + 1).'. '.$make->name."\n";
            }

            $response .= "\n".($locale === 'sw' ? 'Jibu kwa nambari au jina la aina ya gari.' : 'Reply with the number or name of the vehicle make.');

            // Store makes in context
            $conversation->setContext('sparepart_makes', $makes->pluck('id', 'name')->toArray());

            return $response;
        } else {
            // Show review of all orders
            $conversation->setContext('sparepart_substep', 'review_orders');

            return $this->getOrdersReview($conversation);
        }
    }

    /**
     * Get orders review
     */
    protected function getOrdersReview(ChatbotConversation $conversation): string
    {
        $orders = $conversation->getContext('sparepart_orders', []);
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        if (empty($orders)) {
            return $locale === 'sw'
                ? 'Hakuna maagizo yaliyohifadhiwa.'
                : 'No orders saved.';
        }

        $response = $locale === 'sw'
            ? "📋 HAKIKI AAGIZO LAKO:\n\n"
            : "📋 REVIEW YOUR ORDER:\n\n";

        foreach ($orders as $index => $order) {
            $orderNum = $index + 1;
            $response .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            $response .= ($locale === 'sw' ? "Agizo #{$orderNum}" : "Order #{$orderNum}")."\n";
            $response .= ($locale === 'sw' ? 'Aina ya gari' : 'Vehicle Make').": {$order['make_name']}\n";
            $response .= ($locale === 'sw' ? 'Modeli' : 'Model').": {$order['model_name']}\n";
            $response .= ($locale === 'sw' ? 'Jina la sehemu' : 'Part Name').": {$order['part_name']}\n";
            $response .= ($locale === 'sw' ? 'Picha' : 'Image').': '.($order['has_image'] ? ($locale === 'sw' ? 'Ndiyo' : 'Yes') : ($locale === 'sw' ? 'Hapana' : 'No'))."\n";
            $response .= ($locale === 'sw' ? 'Maelezo ya uwasilishaji' : 'Delivery').": {$order['delivery_description']}\n";
            if (! empty($order['explanation'])) {
                $response .= ($locale === 'sw' ? 'Maelezo zaidi' : 'Explanation').": {$order['explanation']}\n";
            }
            $response .= "\n";
        }

        $response .= "\n".($locale === 'sw'
            ? "Je, ungependa kuthibitisha agizo hili? (Jibu 'ndiyo' au 'hapana')\n\nAu andika 'rudi' ili kuongeza sehemu nyingine."
            : "Would you like to confirm this order? (Reply 'yes' or 'no')\n\nOr type 'back' to add another part.");

        return $response;
    }

    /**
     * Handle review orders
     */
    protected function handleReviewOrders(ChatbotConversation $conversation, string $message): string
    {
        $message = strtolower(trim($message));
        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        // Check for back
        if (in_array($message, ['back', 'rudi', 'modify', 'badilisha'])) {
            // Go back to add more step
            $conversation->setContext('sparepart_substep', 'add_more');

            return $locale === 'sw'
                ? "Je, ungependa kuongeza sehemu nyingine? (Jibu 'ndiyo' au 'hapana')"
                : "Would you like to add another spare part? (Reply 'yes' or 'no')";
        }

        $confirm = in_array($message, ['yes', 'y', 'ndiyo', 'ndio', '1', 'confirm', 'thibitisha']);

        if ($confirm) {
            $conversation->setContext('sparepart_substep', 'confirm_orders');

            return $this->createOrders($conversation);
        } else {
            // User wants to cancel
            if (in_array($message, ['no', 'hapana', 'cancel', 'ghairi'])) {
                $conversation->setContext('sparepart_substep', null);
                $conversation->setContext('sparepart_orders', []);
                $conversation->updateStep('main_menu');

                return $locale === 'sw'
                    ? "Agizo limeghairiwa. Unaweza kuanza upya kwa kuandika 'spare parts' au 'sehemu za ziada'."
                    : "Order cancelled. You can start over by typing 'spare parts'.";
            }

            // Show review again with instructions
            return $this->getOrdersReview($conversation);
        }
    }

    /**
     * Handle confirm orders - create the orders in database
     */
    protected function handleConfirmOrders(ChatbotConversation $conversation, string $message): string
    {
        // This should not be called directly, but if it is, create orders
        return $this->createOrders($conversation);
    }

    /**
     * Create orders in database
     */
    protected function createOrders(ChatbotConversation $conversation): string
    {
        $orders = $conversation->getContext('sparepart_orders', []);
        $contactPhone = $conversation->getContext('sparepart_contact_phone')
            ?: $this->normalizedConversationPhone($conversation);

        if (empty($orders)) {
            $locale = $conversation->language === 'sw' ? 'sw' : 'en';

            return $locale === 'sw'
                ? 'Hakuna maagizo ya kuunda.'
                : 'No orders to create.';
        }

        $locale = $conversation->language === 'sw' ? 'sw' : 'en';

        // Validate all orders have required fields
        foreach ($orders as $index => $orderData) {
            if (empty($orderData['make_id']) || empty($orderData['model_id']) || empty($orderData['part_name']) || empty($orderData['delivery_description'])) {
                return $locale === 'sw'
                    ? 'Kuna hitilafu katika agizo #'.($index + 1).'. Tafadhali anza upya.'
                    : "There's an error in order #".($index + 1).'. Please start over.';
            }
        }

        try {
            $user = $this->findUserByNormalizedPhone($contactPhone);

            $createdOrders = [];

            foreach ($orders as $orderData) {
                $order = SparePartOrder::create([
                    'order_number' => SparePartOrder::generateOrderNumber(),
                    'order_channel' => 'whatsapp',
                    'user_id' => $user?->id,
                    'customer_name' => $user?->name ?? 'Chatbot Customer',
                    'customer_email' => $user?->email,
                    'customer_phone' => $contactPhone,
                    'vehicle_make_id' => $orderData['make_id'],
                    'vehicle_model_id' => $orderData['model_id'],
                    'condition' => 'new', // Default to new
                    'part_name' => $orderData['part_name'],
                    'description' => $orderData['explanation'] ?? null,
                    'images' => $orderData['images'] ?? [],
                    'delivery_address' => $orderData['delivery_description'],
                    'delivery_city' => null,
                    'delivery_region' => null,
                    'delivery_country' => 'Tanzania',
                    'contact_name' => $user?->name ?? 'Chatbot Customer',
                    'contact_phone' => $contactPhone,
                    'contact_email' => $user?->email,
                    'status' => 'pending',
                ]);

                $createdOrders[] = $order;

                if (! empty($order->customer_email)) {
                    SendSparePartOrderConfirmationEmail::dispatch($order);
                }
            }

            // Clear order context
            $conversation->setContext('sparepart_orders', []);
            $conversation->setContext('sparepart_substep', null);
            $conversation->updateStep('main_menu');

            $orderCount = count($createdOrders);
            $orderNumbers = collect($createdOrders)->pluck('order_number')->implode(', ');

            return $locale === 'sw'
                ? "✅ Asante! Agizo lako limeundwa kwa mafanikio!\n\nNambari za agizo: {$orderNumbers}\n\nTutawasiliana nawe kupitia simu uliothibitisha.\n\nTunaweza kukusaidia na nini kingine?"
                : "✅ Thank you! Your order has been created successfully!\n\nOrder number(s): {$orderNumbers}\n\nWe will follow up using the phone number you verified.\n\nHow else can we help you?";

        } catch (\Exception $e) {
            Log::error('Failed to create sparepart orders: '.$e->getMessage());

            return $locale === 'sw'
                ? 'Kumekuwa na hitilafu katika kuunda agizo. Tafadhali jaribu tena baadaye au wasiliana nasi moja kwa moja.'
                : 'There was an error creating your order. Please try again later or contact us directly.';
        }
    }

    /**
     * Clear current order context
     */
    protected function findUserByNormalizedPhone(string $normalized): ?User
    {
        if ($normalized === '' || strlen($normalized) < 12) {
            return null;
        }

        $local = substr($normalized, 3);
        $variants = array_unique(array_filter([
            $normalized,
            '+'.$normalized,
            '0'.$local,
            $local,
        ]));

        return User::whereIn('phone_number', $variants)->first();
    }

    protected function isBackToMainMenuCommand(string $message): bool
    {
        $normalized = preg_replace('/\s+/', '', strtolower(trim($message)));

        return $normalized === '99';
    }

    protected function clearCurrentOrderContext(ChatbotConversation $conversation): void
    {
        $conversation->setContext('sparepart_current_make_id', null);
        $conversation->setContext('sparepart_current_make_name', null);
        $conversation->setContext('sparepart_current_model_id', null);
        $conversation->setContext('sparepart_current_model_name', null);
        $conversation->setContext('sparepart_current_part_name', null);
        $conversation->setContext('sparepart_current_has_image', null);
        $conversation->setContext('sparepart_current_images', null);
        $conversation->setContext('sparepart_current_delivery_description', null);
    }
}
