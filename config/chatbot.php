<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chatbot Session Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how long a chatbot conversation session remains active
    | before expiring due to inactivity.
    |
    */

    /**
     * Idle timeout in minutes
     * After this many minutes of inactivity, the session will expire
     * and the conversation will reset to welcome when user sends a new message.
     * 
     * Default: 30 minutes
     */
    'idle_timeout_minutes' => env('CHATBOT_IDLE_TIMEOUT_MINUTES', 30),

    /**
     * Maximum session lifetime in hours
     * Even if user is active, reset session after this many hours
     * 
     * Default: 24 hours
     */
    'max_session_lifetime_hours' => env('CHATBOT_MAX_SESSION_LIFETIME_HOURS', 24),
];

