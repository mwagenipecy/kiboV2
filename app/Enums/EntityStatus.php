<?php

namespace App\Enums;

enum EntityStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case INACTIVE = 'inactive';
    case REJECTED = 'rejected';

    /**
     * Get the label for the entity status
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending Approval',
            self::ACTIVE => 'Active',
            self::SUSPENDED => 'Suspended',
            self::INACTIVE => 'Inactive',
            self::REJECTED => 'Rejected',
        };
    }

    /**
     * Get the badge color class
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::ACTIVE => 'bg-green-100 text-green-800',
            self::SUSPENDED => 'bg-red-100 text-red-800',
            self::INACTIVE => 'bg-gray-100 text-gray-800',
            self::REJECTED => 'bg-red-100 text-red-800',
        };
    }

    /**
     * Get all statuses for dropdown
     */
    public static function forSelect(): array
    {
        return array_combine(
            array_map(fn($case) => $case->value, self::cases()),
            array_map(fn($case) => $case->label(), self::cases())
        );
    }
}

