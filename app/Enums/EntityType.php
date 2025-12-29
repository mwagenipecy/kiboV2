<?php

namespace App\Enums;

enum EntityType: string
{
    case DEALER = 'dealer';
    case LENDER = 'lender';
    case MANUFACTURER = 'manufacturer';
    case INSURANCE = 'insurance';
    case SERVICE_CENTER = 'service_center';

    /**
     * Get the label for the entity type
     */
    public function label(): string
    {
        return match($this) {
            self::DEALER => 'Dealer',
            self::LENDER => 'Lender',
            self::MANUFACTURER => 'Manufacturer',
            self::INSURANCE => 'Insurance Provider',
            self::SERVICE_CENTER => 'Service Center',
        };
    }

    /**
     * Get all entity types as an array
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label()
        ], self::cases());
    }

    /**
     * Get entity types for dropdown
     */
    public static function forSelect(): array
    {
        return array_combine(
            array_map(fn($case) => $case->value, self::cases()),
            array_map(fn($case) => $case->label(), self::cases())
        );
    }
}

