<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $table = 'email_logs';

    protected $fillable = [
        'type',
        'promotion_campaign_id',
        'recipient_email',
        'recipient_name',
        'recipient_type',
        'subject',
        'sent_by_user_id',
        'sent_at',
        'status',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by_user_id');
    }

    public function promotionCampaign(): BelongsTo
    {
        return $this->belongsTo(PromotionCampaign::class);
    }

    public function scopePromotion($query)
    {
        return $query->where('type', 'promotion');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
