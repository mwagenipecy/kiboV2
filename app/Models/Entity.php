<?php

namespace App\Models;

use App\Enums\EntityStatus;
use App\Enums\EntityType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'status',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'registration_number',
        'tax_id',
        'website',
        'description',
        'metadata',
    ];

    protected $casts = [
        'type' => EntityType::class,
        'status' => EntityStatus::class,
        'metadata' => 'array',
    ];

    /**
     * Get the users for the entity
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the primary user (first user) of the entity
     */
    public function primaryUser()
    {
        return $this->users()->oldest()->first();
    }

    /**
     * Scope to filter by entity type
     */
    public function scopeOfType($query, EntityType $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by status
     */
    public function scopeWithStatus($query, EntityStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status->badgeClass();
    }

    /**
     * Get the type label
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type->label();
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }
}

