<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarExchangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'public_token',
        'customer_name',
        'customer_email',
        'customer_phone',
        'current_vehicle_make',
        'current_vehicle_model',
        'current_vehicle_year',
        'current_vehicle_registration',
        'current_vehicle_mileage',
        'current_vehicle_condition',
        'current_vehicle_description',
        'current_vehicle_images',
        'desired_vehicle_make_id',
        'desired_vehicle_model_id',
        'desired_min_year',
        'desired_max_year',
        'desired_fuel_type',
        'desired_transmission',
        'desired_body_type',
        'max_budget',
        'notes',
        'location',
        'status',
        'approved_by',
        'approved_at',
        'sent_to_dealer_id',
        'sent_to_dealer_at',
        'accepted_quotation_id',
    ];

    protected $casts = [
        'current_vehicle_year' => 'integer',
        'current_vehicle_mileage' => 'integer',
        'desired_min_year' => 'integer',
        'desired_max_year' => 'integer',
        'max_budget' => 'integer',
        'current_vehicle_images' => 'array',
        'approved_at' => 'datetime',
        'sent_to_dealer_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function desiredMake(): BelongsTo
    {
        return $this->belongsTo(VehicleMake::class, 'desired_vehicle_make_id');
    }

    public function desiredModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'desired_vehicle_model_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function sentToDealer(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'sent_to_dealer_id');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(DealerExchangeQuotation::class, 'exchange_request_id');
    }

    public function acceptedQuotation(): BelongsTo
    {
        return $this->belongsTo(DealerExchangeQuotation::class, 'accepted_quotation_id');
    }
}
