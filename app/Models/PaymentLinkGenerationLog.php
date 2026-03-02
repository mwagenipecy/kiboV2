<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLinkGenerationLog extends Model
{
    protected $fillable = [
        'payment_link_id',
        'success',
        'error_message',
        'request_payload',
        'response_payload',
        'request_id',
    ];

    protected $casts = [
        'success' => 'boolean',
        'request_payload' => 'array',
        'response_payload' => 'array',
    ];

    public function paymentLink(): BelongsTo
    {
        return $this->belongsTo(PaymentLink::class);
    }
}
