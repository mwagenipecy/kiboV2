<?php

namespace App\Enums;

enum OrderType: string
{
    case VALUATION_REPORT = 'valuation_report';
    case FINANCING_APPLICATION = 'financing_application';
    case CASH_PURCHASE = 'cash_purchase';
    case TEST_DRIVE = 'test_drive';
    case VEHICLE_INSPECTION = 'vehicle_inspection';
    case TRADE_IN = 'trade_in';

    public function label(): string
    {
        return match($this) {
            self::VALUATION_REPORT => 'Valuation Report',
            self::FINANCING_APPLICATION => 'Financing Application',
            self::CASH_PURCHASE => 'Cash Purchase',
            self::TEST_DRIVE => 'Test Drive',
            self::VEHICLE_INSPECTION => 'Vehicle Inspection',
            self::TRADE_IN => 'Trade-In Request',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::VALUATION_REPORT => 'Professional vehicle valuation report',
            self::FINANCING_APPLICATION => 'Apply for car financing',
            self::CASH_PURCHASE => 'Direct purchase with cash',
            self::TEST_DRIVE => 'Schedule a test drive',
            self::VEHICLE_INSPECTION => 'Pre-purchase vehicle inspection',
            self::TRADE_IN => 'Trade in your current vehicle',
        };
    }

    public function fee(): float
    {
        return match($this) {
            self::VALUATION_REPORT => 50.00,
            self::FINANCING_APPLICATION => 0.00,
            self::CASH_PURCHASE => 0.00,
            self::TEST_DRIVE => 0.00,
            self::VEHICLE_INSPECTION => 100.00,
            self::TRADE_IN => 0.00,
        };
    }

    public function requiresPayment(): bool
    {
        return $this->fee() > 0;
    }
}

