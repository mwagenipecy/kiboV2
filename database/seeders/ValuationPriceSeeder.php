<?php

namespace Database\Seeders;

use App\Models\ValuationPrice;
use Illuminate\Database\Seeder;

class ValuationPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = [
            // Car valuations - TZS
            [
                'name' => 'Standard Car Valuation',
                'type' => 'car',
                'urgency' => 'standard',
                'price' => 50000,
                'currency' => 'TZS',
                'description' => 'Professional car valuation report delivered in 3-5 business days',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Urgent Car Valuation',
                'type' => 'car',
                'urgency' => 'urgent',
                'price' => 75000,
                'currency' => 'TZS',
                'description' => 'Express car valuation report delivered in 24-48 hours',
                'is_active' => true,
                'sort_order' => 2,
            ],
            
            // Truck valuations - TZS
            [
                'name' => 'Standard Truck Valuation',
                'type' => 'truck',
                'urgency' => 'standard',
                'price' => 100000,
                'currency' => 'TZS',
                'description' => 'Professional truck valuation report delivered in 3-5 business days',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Urgent Truck Valuation',
                'type' => 'truck',
                'urgency' => 'urgent',
                'price' => 150000,
                'currency' => 'TZS',
                'description' => 'Express truck valuation report delivered in 24-48 hours',
                'is_active' => true,
                'sort_order' => 2,
            ],
            
            // House/Property valuations - TZS
            [
                'name' => 'Standard Property Valuation',
                'type' => 'house',
                'urgency' => 'standard',
                'price' => 200000,
                'currency' => 'TZS',
                'description' => 'Professional property valuation report delivered in 5-7 business days',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Urgent Property Valuation',
                'type' => 'house',
                'urgency' => 'urgent',
                'price' => 350000,
                'currency' => 'TZS',
                'description' => 'Express property valuation report delivered in 48-72 hours',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($prices as $price) {
            ValuationPrice::updateOrCreate(
                [
                    'type' => $price['type'],
                    'urgency' => $price['urgency'],
                    'vehicle_make_id' => null,
                ],
                $price
            );
        }
    }
}

