<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ImportFinancingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'request_type',
        'car_link',
        'extracted_car_info',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'vehicle_price',
        'vehicle_currency',
        'vehicle_condition',
        'vehicle_location',
        'tax_amount',
        'transport_cost',
        'total_clearing_cost',
        'financing_amount_requested',
        'loan_term_months',
        'down_payment',
        'documents',
        'customer_notes',
        'admin_notes',
        'status',
        'reviewed_by',
        'reviewed_at',
        'accepted_offer_id',
    ];

    protected $casts = [
        'extracted_car_info' => 'array',
        'documents' => 'array',
        'vehicle_year' => 'integer',
        'vehicle_price' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'transport_cost' => 'decimal:2',
        'total_clearing_cost' => 'decimal:2',
        'financing_amount_requested' => 'decimal:2',
        'loan_term_months' => 'integer',
        'down_payment' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->reference_number)) {
                $model->reference_number = 'IFR-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(ImportFinancingOffer::class);
    }

    public function acceptedOffer(): BelongsTo
    {
        return $this->belongsTo(ImportFinancingOffer::class, 'accepted_offer_id');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'under_review' => 'blue',
            'approved' => 'green',
            'rejected' => 'red',
            'with_lenders' => 'purple',
            'offer_received' => 'indigo',
            'accepted' => 'green',
            'completed' => 'green',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending Review',
            'under_review' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'with_lenders' => 'With Lenders',
            'offer_received' => 'Offer Received',
            'accepted' => 'Offer Accepted',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    public function getRequestTypeLabelAttribute(): string
    {
        return match($this->request_type) {
            'buy_car' => 'Buy Car (Import)',
            'tax_transport' => 'Tax & Transport',
            default => ucfirst($this->request_type),
        };
    }

    public function getTotalFinancingNeededAttribute(): float
    {
        if ($this->request_type === 'buy_car') {
            return $this->financing_amount_requested ?? $this->vehicle_price ?? 0;
        }
        
        return ($this->tax_amount ?? 0) + ($this->transport_cost ?? 0) + ($this->total_clearing_cost ?? 0);
    }
}

