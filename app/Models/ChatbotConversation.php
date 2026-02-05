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
        $this->current_step = $step;
        $this->last_interaction_at = now();
        
        if ($context !== null) {
            $this->context = array_merge($this->context ?? [], $context);
        }
        
        $this->save();
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
