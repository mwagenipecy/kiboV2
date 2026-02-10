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
        
        // Update last interaction when conversation is retrieved (user is active)
        // This prevents false expiration if user is actively chatting
        $conversation->last_interaction_at = now();
        $conversation->is_active = true;
        $conversation->save();
        
        // Only check expiration if last_interaction_at exists and is significantly old
        // Don't reset if conversation was just created or recently active
        if ($conversation->last_interaction_at && $conversation->isExpired()) {
            $conversation->reset();
        }
        
        return $conversation;
    }

    /**
     * Check if the conversation session has expired due to inactivity
     * Default idle timeout: 30 minutes
     * Minimum timeout: 4 minutes (prevents premature expiration)
     */
    public function isExpired(): bool
    {
        if (!$this->last_interaction_at) {
            return false;
        }
        
        $idleTimeoutMinutes = config('chatbot.idle_timeout_minutes', 30);
        $minIdleTimeoutMinutes = config('chatbot.min_idle_timeout_minutes', 4);
        $maxLifetimeHours = config('chatbot.max_session_lifetime_hours', 24);
        
        // Calculate time since last interaction
        $idleMinutes = $this->last_interaction_at->diffInMinutes(now());
        $idleSeconds = $this->last_interaction_at->diffInSeconds(now());
        
        // NEVER expire if less than minimum timeout has passed
        // This prevents premature expiration during active conversation
        if ($idleSeconds < ($minIdleTimeoutMinutes * 60)) {
            return false;
        }
        
        // Check idle timeout (minutes since last interaction)
        if ($idleMinutes >= $idleTimeoutMinutes) {
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
        
        // Prepare update data
        $updateData = [
            'current_step' => $step,
            'last_interaction_at' => now(),
            'is_active' => true,
            'context' => $currentContext,
        ];
        
        // Include language if it was set on the model (it might have been changed before updateStep was called)
        if (isset($this->language) && $this->language !== null) {
            $updateData['language'] = $this->language;
        }
        
        // Use update() to ensure all fields are saved atomically
        $updated = static::where('id', $this->id)->update($updateData);
        
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
