<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportFinancingOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_financing_request_id',
        'entity_id',
        'user_id',
        'offered_amount',
        'interest_rate',
        'loan_term_months',
        'monthly_payment',
        'processing_fee',
        'total_repayment',
        'terms_conditions',
        'notes',
        'valid_until',
        'status',
    ];

    protected $casts = [
        'offered_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'loan_term_months' => 'integer',
        'monthly_payment' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'total_repayment' => 'decimal:2',
        'valid_until' => 'date',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(ImportFinancingRequest::class, 'import_financing_request_id');
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'accepted' => 'green',
            'rejected' => 'red',
            'expired' => 'gray',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    public function isExpired(): bool
    {
        return $this->valid_until && $this->valid_until->isPast();
    }
}

