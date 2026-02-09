<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealerExchangeQuotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'exchange_request_id',
        'entity_id',
        'user_id',
        'current_vehicle_valuation',
        'desired_vehicle_price',
        'price_difference',
        'currency',
        'offered_vehicle_id',
        'message',
        'quotation_documents',
        'status',
        'sent_at',
        'accepted_at',
        'rejected_at',
    ];

    protected $casts = [
        'current_vehicle_valuation' => 'decimal:2',
        'desired_vehicle_price' => 'decimal:2',
        'price_difference' => 'decimal:2',
        'quotation_documents' => 'array',
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function exchangeRequest(): BelongsTo
    {
        return $this->belongsTo(CarExchangeRequest::class, 'exchange_request_id');
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function offeredVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'offered_vehicle_id');
    }
}
