<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValuationPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'vehicle_make_id',
        'urgency',
        'price',
        'currency',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Available types for valuation
     */
    public const TYPES = [
        'car' => 'Car',
        'truck' => 'Truck',
        'house' => 'House/Property',
    ];

    /**
     * Available urgency levels
     */
    public const URGENCIES = [
        'standard' => 'Standard (3-5 days)',
        'urgent' => 'Urgent (24-48 hours)',
    ];

    /**
     * Available currencies
     */
    public const CURRENCIES = [
        'TZS' => 'TSh (Tanzanian Shilling)',
        'USD' => '$ (US Dollar)',
        'GBP' => '£ (British Pound)',
        'EUR' => '€ (Euro)',
        'KES' => 'KSh (Kenyan Shilling)',
    ];

    /**
     * Currency symbols for display
     */
    public const CURRENCY_SYMBOLS = [
        'TZS' => 'TSh',
        'USD' => '$',
        'GBP' => '£',
        'EUR' => '€',
        'KES' => 'KSh',
    ];

    /**
     * Relationship to vehicle make (optional)
     */
    public function vehicleMake(): BelongsTo
    {
        return $this->belongsTo(VehicleMake::class);
    }

    /**
     * Scope for active prices
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for specific urgency
     */
    public function scopeOfUrgency($query, string $urgency)
    {
        return $query->where('urgency', $urgency);
    }

    /**
     * Get formatted price with currency symbol
     */
    public function getFormattedPriceAttribute(): string
    {
        $symbol = self::CURRENCY_SYMBOLS[$this->currency] ?? $this->currency;
        return $symbol . ' ' . number_format($this->price, 0);
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Get urgency label
     */
    public function getUrgencyLabelAttribute(): string
    {
        return self::URGENCIES[$this->urgency] ?? $this->urgency;
    }

    /**
     * Get valuation price for a specific type, urgency, and optionally make
     */
    public static function getPrice(string $type, string $urgency = 'standard', ?int $makeId = null): ?self
    {
        $query = self::active()
            ->ofType($type)
            ->ofUrgency($urgency)
            ->orderBy('sort_order');

        // If make is provided, try to find a specific price first
        if ($makeId) {
            $specific = (clone $query)->where('vehicle_make_id', $makeId)->first();
            if ($specific) {
                return $specific;
            }
        }

        // Return the general price (null make)
        return $query->whereNull('vehicle_make_id')->first();
    }

    /**
     * Get all prices grouped by type
     */
    public static function getPricesGroupedByType(): array
    {
        $prices = self::active()
            ->with('vehicleMake')
            ->orderBy('type')
            ->orderBy('urgency')
            ->orderBy('sort_order')
            ->get();

        return $prices->groupBy('type')->toArray();
    }
}

