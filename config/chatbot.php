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
     * Minimum: 4 minutes (to prevent premature expiration during active conversation)
     * Default: 30 minutes
     */
    'idle_timeout_minutes' => env('CHATBOT_IDLE_TIMEOUT_MINUTES', 30),
    
    /**
     * Minimum idle timeout in minutes
     * Session will never expire if less than this time has passed
     * This prevents premature expiration during active conversation
     * 
     * Default: 4 minutes
     */
    'min_idle_timeout_minutes' => env('CHATBOT_MIN_IDLE_TIMEOUT_MINUTES', 4),

    /**
     * Maximum session lifetime in hours
     * Even if user is active, reset session after this many hours
     * 
     * Default: 24 hours
     */
    'max_session_lifetime_hours' => env('CHATBOT_MAX_SESSION_LIFETIME_HOURS', 24),

    /**
     * Send messages synchronously (for testing)
     * Set to true to send messages immediately instead of via queue
     * 
     * WARNING: Only use for testing! Production should use queue (false)
     * 
     * Default: false (uses queue)
     */
    'send_sync' => env('CHATBOT_SEND_SYNC', false),
];

