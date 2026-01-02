<?php

namespace App\Models;

use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'origin',
        'registration_number',
        'condition',
        'vehicle_make_id',
        'vehicle_model_id',
        'variant',
        'year',
        'body_type',
        'fuel_type',
        'transmission',
        'engine_capacity',
        'engine_cc',
        'drive_type',
        'color_exterior',
        'color_interior',
        'doors',
        'seats',
        'mileage',
        'vin',
        'price',
        'currency',
        'original_price',
        'negotiable',
        'features',
        'safety_features',
        'additional_specs',
        'image_front',
        'image_side',
        'image_back',
        'other_images',
        'documents',
        'entity_id',
        'registered_by',
        'status',
        'notes',
        'approved_at',
        'approved_by',
        'sold_at',
    ];

    protected $casts = [
        'year' => 'integer',
        'engine_cc' => 'integer',
        'doors' => 'integer',
        'seats' => 'integer',
        'mileage' => 'integer',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'negotiable' => 'boolean',
        'features' => 'array',
        'safety_features' => 'array',
        'additional_specs' => 'array',
        'other_images' => 'array',
        'documents' => 'array',
        'approved_at' => 'datetime',
        'sold_at' => 'datetime',
        'status' => VehicleStatus::class,
    ];

    /**
     * Get the make of the vehicle
     */
    public function make(): BelongsTo
    {
        return $this->belongsTo(VehicleMake::class, 'vehicle_make_id');
    }

    /**
     * Get the model of the vehicle
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    /**
     * Get the entity (dealer/owner) of the vehicle
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * Get the user who registered the vehicle
     */
    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * Get the user who approved the vehicle
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all views for this vehicle
     */
    public function views()
    {
        return $this->hasMany(VehicleView::class);
    }

    /**
     * Get all likes for this vehicle
     */
    public function likes()
    {
        return $this->hasMany(VehicleLike::class);
    }

    /**
     * Get unique users who viewed this vehicle
     */
    public function viewers()
    {
        return $this->belongsToMany(User::class, 'vehicle_views')
            ->withTimestamps();
    }

    /**
     * Get users who liked this vehicle
     */
    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'vehicle_likes')
            ->withTimestamps();
    }

    /**
     * Check if the vehicle is liked by a user
     */
    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Get total views count
     */
    public function getTotalViewsAttribute(): int
    {
        return $this->views()->count();
    }

    /**
     * Get unique viewers count
     */
    public function getUniqueViewersAttribute(): int
    {
        return $this->views()->distinct('user_id')->count('user_id');
    }

    /**
     * Get total likes count
     */
    public function getTotalLikesAttribute(): int
    {
        return $this->likes()->count();
    }

    /**
     * Scope to filter by status
     */
    public function scopeWithStatus($query, VehicleStatus|string $status)
    {
        if ($status instanceof VehicleStatus) {
            return $query->where('status', $status);
        }
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by origin
     */
    public function scopeByOrigin($query, string $origin)
    {
        return $query->where('origin', $origin);
    }

    /**
     * Scope for pending vehicles
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'awaiting_approval']);
    }

    /**
     * Scope for approved vehicles
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for sold vehicles
     */
    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    /**
     * Get the full vehicle name
     */
    public function getFullNameAttribute(): string
    {
        $parts = [
            $this->year,
            $this->make?->name,
            $this->model?->name,
            $this->variant,
        ];

        return implode(' ', array_filter($parts));
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status->badgeClass();
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' ' . $this->currency;
    }

    /**
     * Check if vehicle is local (Tanzania)
     */
    public function isLocal(): bool
    {
        return $this->origin === 'local';
    }

    /**
     * Check if vehicle is international
     */
    public function isInternational(): bool
    {
        return $this->origin === 'international';
    }

    /**
     * Mark vehicle as approved
     */
    public function approve(User $user): void
    {
        $this->update([
            'status' => VehicleStatus::APPROVED,
            'approved_at' => now(),
            'approved_by' => $user->id,
        ]);
    }

    /**
     * Mark vehicle as sold
     */
    public function markAsSold(): void
    {
        $this->update([
            'status' => VehicleStatus::SOLD,
            'sold_at' => now(),
        ]);
    }
}
