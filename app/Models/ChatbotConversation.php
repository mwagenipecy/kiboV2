<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ChatbotConversation extends Model
{
    protected $fillable = [
        'phone_number',
        'language',
        'current_step',
        'context',
        'is_active',
        'last_interaction_at',
    ];

    protected $casts = [
        'context' => 'array',
        'is_active' => 'boolean',
        'last_interaction_at' => 'datetime',
    ];

    /**
     * Get or create conversation for a phone number
     * Checks for session expiration and resets if needed
     */
    public static function getOrCreate(string $phoneNumber): self
    {
        // Remove whatsapp: prefix if present
        $phoneNumber = str_replace('whatsapp:', '', $phoneNumber);
        
        $conversation = static::firstOrCreate(
            ['phone_number' => $phoneNumber],
            [
                'language' => 'en',
                'current_step' => 'welcome',
                'context' => [],
                'is_active' => true,
                'last_interaction_at' => now(),
            ]
        );
        
        // Check if session has expired (idle timeout)
        if ($conversation->isExpired()) {
            $conversation->reset();
        }
        
        return $conversation;
    }

    /**
     * Check if the conversation session has expired due to inactivity
     * Default idle timeout: 30 minutes
     */
    public function isExpired(): bool
    {
        if (!$this->last_interaction_at) {
            return false;
        }
        
        $idleTimeoutMinutes = config('chatbot.idle_timeout_minutes', 30);
        $maxLifetimeHours = config('chatbot.max_session_lifetime_hours', 24);
        
        // Check idle timeout (minutes since last interaction)
        $idleMinutes = $this->last_interaction_at->diffInMinutes(now());
        if ($idleMinutes > $idleTimeoutMinutes) {
            return true;
        }
        
        // Check max session lifetime (hours since creation)
        $sessionAgeHours = $this->created_at->diffInHours(now());
        if ($sessionAgeHours > $maxLifetimeHours) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if conversation is active (not expired)
     */
    public function isActive(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Update conversation step
     */
    public function updateStep(string $step, ?array $context = null): void
    {
        // Ensure context is always an array (never null)
        $currentContext = $this->context;
        if ($currentContext === null || !is_array($currentContext)) {
            $currentContext = [];
        }
        
        // Merge new context with existing context
        if ($context !== null && is_array($context)) {
            $currentContext = array_merge($currentContext, $context);
        }
        
        // Use update() to ensure all fields are saved atomically
        $updated = static::where('id', $this->id)->update([
            'current_step' => $step,
            'last_interaction_at' => now(),
            'is_active' => true,
            'context' => $currentContext,
            // Also update language if it was changed on the model
            'language' => $this->language ?? $this->getOriginal('language'),
        ]);
        
        // Refresh the model to get the latest data
        $this->refresh();
        
        // Log for debugging
        \Log::info('Updated conversation step', [
            'phone_number' => $this->phone_number,
            'conversation_id' => $this->id,
            'step_requested' => $step,
            'current_step_after_update' => $this->current_step,
            'context' => $this->context,
            'language' => $this->language,
            'rows_updated' => $updated,
            'was_dirty' => $this->isDirty(),
        ]);
        
        // Verify the step was actually updated
        if ($this->current_step !== $step) {
            \Log::error('Step update failed!', [
                'phone_number' => $this->phone_number,
                'conversation_id' => $this->id,
                'requested_step' => $step,
                'actual_step' => $this->current_step,
            ]);
        }
    }

    /**
     * Get context value
     */
    public function getContext(string $key, $default = null)
    {
        return $this->context[$key] ?? $default;
    }

    /**
     * Set context value
     */
    public function setContext(string $key, $value): void
    {
        $context = $this->context ?? [];
        $context[$key] = $value;
        $this->context = $context;
        $this->save();
    }

    /**
     * Reset conversation
     */
    public function reset(): void
    {
        $this->current_step = 'welcome';
        $this->context = [];
        $this->last_interaction_at = now();
        $this->save();
    }
}
