<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppMessageLog extends Model
{
    protected $table = 'whatsapp_message_logs';

    protected $fillable = [
        'phone_number',
        'direction',
        'message_body',
        'message_sid',
        'button_payload',
        'button_text',
        'from_number',
        'to_number',
        'status',
        'used_buttons',
        'used_template',
        'template_sid',
        'metadata',
        'sent_at',
    ];

    protected $casts = [
        'used_buttons' => 'boolean',
        'used_template' => 'boolean',
        'metadata' => 'array',
        'sent_at' => 'datetime',
    ];

    /**
     * Scope to filter by direction
     */
    public function scopeIncoming($query)
    {
        return $query->where('direction', 'incoming');
    }

    /**
     * Scope to filter by direction
     */
    public function scopeOutgoing($query)
    {
        return $query->where('direction', 'outgoing');
    }

    /**
     * Scope to filter by phone number
     */
    public function scopeForPhoneNumber($query, string $phoneNumber)
    {
        return $query->where('phone_number', $phoneNumber);
    }

    /**
     * Scope to filter by status
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
