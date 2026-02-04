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
     */
    public static function getOrCreate(string $phoneNumber): self
    {
        // Remove whatsapp: prefix if present
        $phoneNumber = str_replace('whatsapp:', '', $phoneNumber);
        
        return static::firstOrCreate(
            ['phone_number' => $phoneNumber],
            [
                'language' => 'en',
                'current_step' => 'welcome',
                'context' => [],
                'is_active' => true,
                'last_interaction_at' => now(),
            ]
        );
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
