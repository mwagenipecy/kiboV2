<?php

namespace App\Enums;

enum VehicleStatus: string
{
    case PENDING = 'pending';
    case AWAITING_APPROVAL = 'awaiting_approval';
    case APPROVED = 'approved';
    case HOLD = 'hold';
    case SOLD = 'sold';
    case REMOVED = 'removed';

    /**
     * Get the label for the status
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::AWAITING_APPROVAL => 'Awaiting Approval',
            self::APPROVED => 'Approved',
            self::HOLD => 'On Hold',
            self::SOLD => 'Sold',
            self::REMOVED => 'Removed',
        };
    }

    /**
     * Get the badge class for the status
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::AWAITING_APPROVAL => 'bg-orange-100 text-orange-800',
            self::APPROVED => 'bg-green-100 text-green-800',
            self::HOLD => 'bg-gray-100 text-gray-800',
            self::SOLD => 'bg-blue-100 text-blue-800',
            self::REMOVED => 'bg-red-100 text-red-800',
        };
    }

    /**
     * Get all status options
     */
    public static function options(): array
    {
        return [
            self::PENDING->value => self::PENDING->label(),
            self::AWAITING_APPROVAL->value => self::AWAITING_APPROVAL->label(),
            self::APPROVED->value => self::APPROVED->label(),
            self::HOLD->value => self::HOLD->label(),
            self::SOLD->value => self::SOLD->label(),
            self::REMOVED->value => self::REMOVED->label(),
        ];
    }
}
