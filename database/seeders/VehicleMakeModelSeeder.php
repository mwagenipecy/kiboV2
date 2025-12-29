<?php

namespace Database\Seeders;

use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleMakeModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $makes = [
            [
                'name' => 'Toyota',
                'status' => 'active',
                'models' => ['Corolla', 'Camry', 'RAV4', 'Highlander', 'Prius', 'Land Cruiser', 'Hilux', 'Yaris', 'Avalon', 'Sienna']
            ],
            [
                'name' => 'Honda',
                'status' => 'active',
                'models' => ['Civic', 'Accord', 'CR-V', 'Pilot', 'Odyssey', 'HR-V', 'Ridgeline', 'Passport', 'Insight', 'Fit']
            ],
            [
                'name' => 'Ford',
                'status' => 'active',
                'models' => ['F-150', 'Mustang', 'Explorer', 'Escape', 'Edge', 'Ranger', 'Bronco', 'Expedition', 'Transit', 'Maverick']
            ],
            [
                'name' => 'Chevrolet',
                'status' => 'active',
                'models' => ['Silverado', 'Equinox', 'Malibu', 'Traverse', 'Tahoe', 'Suburban', 'Colorado', 'Blazer', 'Camaro', 'Corvette']
            ],
            [
                'name' => 'BMW',
                'status' => 'active',
                'models' => ['3 Series', '5 Series', '7 Series', 'X1', 'X3', 'X5', 'X7', 'M3', 'M5', 'i4']
            ],
            [
                'name' => 'Mercedes-Benz',
                'status' => 'active',
                'models' => ['C-Class', 'E-Class', 'S-Class', 'GLA', 'GLC', 'GLE', 'GLS', 'A-Class', 'CLA', 'AMG GT']
            ],
            [
                'name' => 'Audi',
                'status' => 'active',
                'models' => ['A3', 'A4', 'A6', 'A8', 'Q3', 'Q5', 'Q7', 'Q8', 'e-tron', 'TT']
            ],
            [
                'name' => 'Nissan',
                'status' => 'active',
                'models' => ['Altima', 'Sentra', 'Maxima', 'Rogue', 'Murano', 'Pathfinder', 'Frontier', 'Titan', 'Armada', 'Leaf']
            ],
            [
                'name' => 'Volkswagen',
                'status' => 'active',
                'models' => ['Jetta', 'Passat', 'Tiguan', 'Atlas', 'Golf', 'Arteon', 'Taos', 'ID.4', 'Beetle', 'Touareg']
            ],
            [
                'name' => 'Hyundai',
                'status' => 'active',
                'models' => ['Elantra', 'Sonata', 'Tucson', 'Santa Fe', 'Palisade', 'Kona', 'Venue', 'Ioniq', 'Genesis', 'Accent']
            ],
            [
                'name' => 'Kia',
                'status' => 'active',
                'models' => ['Forte', 'Optima', 'Sportage', 'Sorento', 'Telluride', 'Soul', 'Seltos', 'Carnival', 'Stinger', 'EV6']
            ],
            [
                'name' => 'Mazda',
                'status' => 'active',
                'models' => ['Mazda3', 'Mazda6', 'CX-3', 'CX-5', 'CX-9', 'CX-30', 'CX-50', 'MX-5 Miata', 'CX-90', 'CX-70']
            ],
            [
                'name' => 'Subaru',
                'status' => 'active',
                'models' => ['Impreza', 'Legacy', 'Outback', 'Forester', 'Crosstrek', 'Ascent', 'WRX', 'BRZ', 'Solterra', 'Wilderness']
            ],
            [
                'name' => 'Tesla',
                'status' => 'active',
                'models' => ['Model S', 'Model 3', 'Model X', 'Model Y', 'Cybertruck', 'Roadster']
            ],
            [
                'name' => 'Lexus',
                'status' => 'active',
                'models' => ['ES', 'IS', 'LS', 'NX', 'RX', 'GX', 'LX', 'UX', 'LC', 'RC']
            ],
            [
                'name' => 'Jeep',
                'status' => 'active',
                'models' => ['Wrangler', 'Grand Cherokee', 'Cherokee', 'Compass', 'Renegade', 'Gladiator', 'Wagoneer', 'Grand Wagoneer']
            ],
            [
                'name' => 'RAM',
                'status' => 'active',
                'models' => ['1500', '2500', '3500', 'ProMaster', 'ProMaster City']
            ],
            [
                'name' => 'GMC',
                'status' => 'active',
                'models' => ['Sierra', 'Terrain', 'Acadia', 'Yukon', 'Canyon', 'Hummer EV']
            ],
            [
                'name' => 'Volvo',
                'status' => 'active',
                'models' => ['S60', 'S90', 'V60', 'V90', 'XC40', 'XC60', 'XC90', 'C40']
            ],
            [
                'name' => 'Porsche',
                'status' => 'active',
                'models' => ['911', 'Cayenne', 'Macan', 'Panamera', 'Taycan', 'Boxster', 'Cayman']
            ],
        ];

        foreach ($makes as $makeData) {
            $models = $makeData['models'];
            unset($makeData['models']);

            // Create the make
            $make = VehicleMake::firstOrCreate(
                ['name' => $makeData['name']],
                $makeData
            );

            // Create models for this make
            foreach ($models as $modelName) {
                VehicleModel::firstOrCreate(
                    [
                        'name' => $modelName,
                        'vehicle_make_id' => $make->id
                    ],
                    [
                        'status' => 'active'
                    ]
                );
            }
        }

        $this->command->info('Vehicle makes and models seeded successfully!');
    }
}
