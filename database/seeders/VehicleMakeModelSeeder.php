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
        $this->seedMakesAndModels($this->definitions());

        $this->command->info('Vehicle makes and models seeded successfully!');
    }

    /**
     * @param  array<int, array{name: string, status?: string, models: array<int, string>}>  $makes
     */
    private function seedMakesAndModels(array $makes): void
    {
        foreach ($makes as $makeData) {
            $modelNames = $makeData['models'];
            $name = $makeData['name'];
            $status = $makeData['status'] ?? 'active';

            $make = VehicleMake::where('name', $name)->first();
            if ($make === null) {
                $make = VehicleMake::create([
                    'name' => $name,
                    'status' => $status,
                ]);
            }

            foreach ($modelNames as $modelName) {
                if (VehicleModel::query()
                    ->where('vehicle_make_id', $make->id)
                    ->where('name', $modelName)
                    ->exists()) {
                    continue;
                }

                VehicleModel::create([
                    'name' => $modelName,
                    'vehicle_make_id' => $make->id,
                    'status' => 'active',
                ]);
            }
        }
    }

    /**
     * Passenger vehicles and commercial trucks (shared vehicle_makes / vehicle_models tables).
     *
     * @return array<int, array{name: string, status?: string, models: array<int, string>}>
     */
    private function definitions(): array
    {
        return [

            // ─────────────────────────────────────────
            // JAPANESE BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'Toyota',
                'status' => 'active',
                'models' => [
                    'Corolla', 'Camry', 'RAV4', 'Highlander', 'Prius', 'Land Cruiser', 'Hilux', 'Yaris',
                    'Avalon', 'Sienna', 'Tacoma', 'Tundra', '4Runner', 'Sequoia', 'C-HR', 'bZ4X',
                    'GR86', 'Supra', 'Venza', 'Corolla Cross', 'Crown', 'Grand Highlander', 'Mirai',
                ],
            ],
            [
                'name' => 'Honda',
                'status' => 'active',
                'models' => [
                    'Civic', 'Accord', 'CR-V', 'Pilot', 'Odyssey', 'HR-V', 'Ridgeline', 'Passport',
                    'Insight', 'Fit', 'Civic Type R', 'Prologue', 'CR-Z', 'Jazz', 'WR-V', 'Elevate',
                ],
            ],
            [
                'name' => 'Nissan',
                'status' => 'active',
                'models' => [
                    'Altima', 'Sentra', 'Maxima', 'Rogue', 'Murano', 'Pathfinder', 'Frontier', 'Titan',
                    'Armada', 'Leaf', 'Kicks', 'Versa', 'Z', 'GT-R', 'NV200', 'NV Cargo', 'Titan XD',
                    'Terra', 'X-Trail', 'Navara', 'Patrol',
                ],
            ],
            [
                'name' => 'Mazda',
                'status' => 'active',
                'models' => [
                    'Mazda3', 'Mazda6', 'CX-3', 'CX-5', 'CX-9', 'CX-30', 'CX-50', 'MX-5 Miata',
                    'CX-90', 'CX-70', 'CX-60', 'MX-30',
                ],
            ],
            [
                'name' => 'Subaru',
                'status' => 'active',
                'models' => [
                    'Impreza', 'Legacy', 'Outback', 'Forester', 'Crosstrek', 'Ascent', 'WRX', 'BRZ',
                    'Solterra', 'Wilderness',
                ],
            ],
            [
                'name' => 'Mitsubishi',
                'status' => 'active',
                'models' => [
                    'Outlander', 'Eclipse Cross', 'Mirage', 'Lancer', 'Pajero', 'ASX',
                    'Outlander PHEV', 'Triton', 'Fuso Canter',
                ],
            ],
            [
                'name' => 'Suzuki',
                'status' => 'active',
                'models' => [
                    'Swift', 'Vitara', 'Jimny', 'Grand Vitara', 'S-Cross', 'Baleno', 'Celerio',
                    'Carry', 'APV', 'Ertiga', 'Ciaz', 'Brezza',
                ],
            ],
            [
                'name' => 'Lexus',
                'status' => 'active',
                'models' => [
                    'ES', 'IS', 'LS', 'NX', 'RX', 'GX', 'LX', 'UX', 'LC', 'RC', 'RZ', 'TX', 'LM',
                ],
            ],
            [
                'name' => 'Acura',
                'status' => 'active',
                'models' => [
                    'Integra', 'TLX', 'RDX', 'MDX', 'NSX', 'ZDX',
                ],
            ],
            [
                'name' => 'Infiniti',
                'status' => 'active',
                'models' => [
                    'Q50', 'Q60', 'QX50', 'QX55', 'QX60', 'QX80',
                ],
            ],
            [
                'name' => 'Daihatsu',
                'status' => 'active',
                'models' => [
                    'Terios', 'Rocky', 'Sirion', 'Gran Max', 'Move', 'Hijet', 'Taft', 'Ayla', 'Sigra',
                ],
            ],
            [
                'name' => 'Isuzu',
                'status' => 'active',
                'models' => [
                    'D-Max', 'MU-X', 'NPR', 'NQR', 'FVR', 'Elf', 'Forward', 'Giga', 'N-Series',
                ],
            ],
            [
                'name' => 'Hino',
                'status' => 'active',
                'models' => [
                    '300 Series', '500 Series', '700 Series', 'Dutro', 'Ranger', 'Profia',
                ],
            ],
            [
                'name' => 'UD Trucks',
                'status' => 'active',
                'models' => [
                    'Quon', 'Croner', 'Condor', 'Kuzer',
                ],
            ],

            // ─────────────────────────────────────────
            // AMERICAN BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'Ford',
                'status' => 'active',
                'models' => [
                    'F-150', 'Mustang', 'Explorer', 'Escape', 'Edge', 'Ranger', 'Bronco', 'Expedition',
                    'Transit', 'Maverick', 'F-250', 'F-350', 'F-450', 'F-550', 'Super Duty',
                    'E-Transit', 'Bronco Sport', 'EcoSport', 'Fusion', 'F-150 Lightning',
                ],
            ],
            [
                'name' => 'Chevrolet',
                'status' => 'active',
                'models' => [
                    'Silverado', 'Equinox', 'Malibu', 'Traverse', 'Tahoe', 'Suburban', 'Colorado',
                    'Blazer', 'Camaro', 'Corvette', 'Trax', 'Bolt EV', 'Bolt EUV', 'Express',
                    'Low Cab Forward', 'Silverado EV', 'Blazer EV', 'Equinox EV',
                ],
            ],
            [
                'name' => 'GMC',
                'status' => 'active',
                'models' => [
                    'Sierra', 'Terrain', 'Acadia', 'Yukon', 'Canyon', 'Hummer EV', 'Savana',
                    'Sierra HD', 'Envoy', 'Envista',
                ],
            ],
            [
                'name' => 'Buick',
                'status' => 'active',
                'models' => [
                    'Enclave', 'Encore', 'Encore GX', 'Envision', 'LaCrosse', 'Envista',
                ],
            ],
            [
                'name' => 'Cadillac',
                'status' => 'active',
                'models' => [
                    'Escalade', 'XT4', 'XT5', 'XT6', 'CT4', 'CT5', 'Lyriq', 'CT6', 'Celestiq',
                    'Optiq', 'Vistiq',
                ],
            ],
            [
                'name' => 'Tesla',
                'status' => 'active',
                'models' => [
                    'Model S', 'Model 3', 'Model X', 'Model Y', 'Cybertruck', 'Roadster',
                ],
            ],
            [
                'name' => 'Rivian',
                'status' => 'active',
                'models' => [
                    'R1T', 'R1S', 'R2', 'R3', 'Commercial Van',
                ],
            ],
            [
                'name' => 'Lucid',
                'status' => 'active',
                'models' => [
                    'Air', 'Gravity',
                ],
            ],
            [
                'name' => 'Jeep',
                'status' => 'active',
                'models' => [
                    'Wrangler', 'Grand Cherokee', 'Cherokee', 'Compass', 'Renegade', 'Gladiator',
                    'Wagoneer', 'Grand Wagoneer', 'Avenger', 'Grand Cherokee 4xe',
                ],
            ],
            [
                'name' => 'RAM',
                'status' => 'active',
                'models' => [
                    '1500', '2500', '3500', 'ProMaster', 'ProMaster City',
                    'Chassis Cab', 'ProMaster EV', '1500 REV',
                ],
            ],
            [
                'name' => 'Dodge',
                'status' => 'active',
                'models' => [
                    'Durango', 'Hornet', 'Charger', 'Challenger', 'Journey', 'Grand Caravan',
                ],
            ],
            [
                'name' => 'Chrysler',
                'status' => 'active',
                'models' => [
                    'Pacifica', 'Voyager', '300',
                ],
            ],
            [
                'name' => 'Lincoln',
                'status' => 'active',
                'models' => [
                    'Navigator', 'Aviator', 'Nautilus', 'Corsair', 'Continental', 'MKZ',
                ],
            ],

            // ─────────────────────────────────────────
            // AMERICAN HEAVY TRUCK BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'Freightliner',
                'status' => 'active',
                'models' => [
                    'Cascadia', 'M2', '108SD', '114SD', '122SD', 'Business Class', 'eCascadia',
                ],
            ],
            [
                'name' => 'Kenworth',
                'status' => 'active',
                'models' => [
                    'T680', 'T880', 'W900', 'T800', 'K370', 'T270', 'T380',
                ],
            ],
            [
                'name' => 'Peterbilt',
                'status' => 'active',
                'models' => [
                    '579', '389', '567', '365', '520', '220', 'Model 579EV',
                ],
            ],
            [
                'name' => 'Mack',
                'status' => 'active',
                'models' => [
                    'Anthem', 'Pinnacle', 'Granite', 'LR', 'MD', 'TerraPro', 'LRX Electric',
                ],
            ],
            [
                'name' => 'Western Star',
                'status' => 'active',
                'models' => [
                    '4900', '5700XE', '6900', '47X', '49X', '57X',
                ],
            ],
            [
                'name' => 'International',
                'status' => 'active',
                'models' => [
                    'LT Series', 'RH Series', 'MV Series', 'CV Series', 'HX Series', 'Lonestar',
                ],
            ],

            // ─────────────────────────────────────────
            // GERMAN BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'BMW',
                'status' => 'active',
                'models' => [
                    '1 Series', '2 Series', '3 Series', '4 Series', '5 Series', '7 Series', '8 Series',
                    'X1', 'X2', 'X3', 'X4', 'X5', 'X6', 'X7', 'M2', 'M3', 'M4', 'M5', 'M8',
                    'i4', 'i5', 'i7', 'iX', 'iX1', 'iX3', 'XM', 'Z4',
                ],
            ],
            [
                'name' => 'Mercedes-Benz',
                'status' => 'active',
                'models' => [
                    'A-Class', 'B-Class', 'C-Class', 'E-Class', 'S-Class', 'CLA', 'CLS',
                    'GLA', 'GLB', 'GLC', 'GLE', 'GLS', 'AMG GT', 'G-Class', 'SL', 'SLC',
                    'EQA', 'EQB', 'EQC', 'EQE', 'EQS', 'EQV', 'Sprinter', 'Metris', 'eSprinter',
                    'Actros', 'Arocs', 'Atego',
                ],
            ],
            [
                'name' => 'Audi',
                'status' => 'active',
                'models' => [
                    'A1', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q2', 'Q3', 'Q4 e-tron',
                    'Q5', 'Q7', 'Q8', 'e-tron', 'e-tron GT', 'TT', 'R8', 'RS3', 'RS4',
                    'RS5', 'RS6', 'RS7', 'S3', 'S4', 'S5', 'S6', 'S7', 'S8',
                ],
            ],
            [
                'name' => 'Volkswagen',
                'status' => 'active',
                'models' => [
                    'Polo', 'Golf', 'Jetta', 'Passat', 'Arteon', 'Tiguan', 'Taos', 'Atlas',
                    'ID.3', 'ID.4', 'ID.5', 'ID.6', 'ID.7', 'ID. Buzz', 'Touareg', 'Beetle',
                    'Amarok', 'Crafter', 'Caddy', 'Transporter', 'T-Roc', 'T-Cross',
                ],
            ],
            [
                'name' => 'Porsche',
                'status' => 'active',
                'models' => [
                    '911', '718 Boxster', '718 Cayman', 'Cayenne', 'Macan', 'Panamera', 'Taycan',
                    '918 Spyder', 'Cayenne E-Hybrid',
                ],
            ],
            [
                'name' => 'Opel',
                'status' => 'active',
                'models' => [
                    'Corsa', 'Astra', 'Insignia', 'Mokka', 'Crossland', 'Grandland', 'Combo',
                    'Vivaro', 'Movano', 'Zafira',
                ],
            ],
            [
                'name' => 'Vauxhall',
                'status' => 'active',
                'models' => [
                    'Corsa', 'Astra', 'Insignia', 'Mokka', 'Crossland', 'Grandland', 'Combo',
                    'Vivaro', 'Movano',
                ],
            ],
            [
                'name' => 'MAN',
                'status' => 'active',
                'models' => [
                    'TGX', 'TGS', 'TGM', 'TGL', "Lion's Coach", "Lion's City",
                ],
            ],

            // ─────────────────────────────────────────
            // SWEDISH BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'Volvo',
                'status' => 'active',
                'models' => [
                    'S60', 'S90', 'V60', 'V90', 'XC40', 'XC60', 'XC90', 'C40',
                    'EX30', 'EX40', 'EX90', 'FH', 'FM', 'FMX', 'VNL', 'VNR',
                ],
            ],
            [
                'name' => 'Polestar',
                'status' => 'active',
                'models' => [
                    'Polestar 1', 'Polestar 2', 'Polestar 3', 'Polestar 4', 'Polestar 5',
                ],
            ],
            [
                'name' => 'Scania',
                'status' => 'active',
                'models' => [
                    'R Series', 'S Series', 'P Series', 'G Series', 'L Series', 'V8',
                ],
            ],

            // ─────────────────────────────────────────
            // BRITISH BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'Land Rover',
                'status' => 'active',
                'models' => [
                    'Range Rover', 'Range Rover Sport', 'Range Rover Evoque', 'Range Rover Velar',
                    'Discovery', 'Discovery Sport', 'Defender',
                ],
            ],
            [
                'name' => 'Jaguar',
                'status' => 'active',
                'models' => [
                    'F-Pace', 'E-Pace', 'I-Pace', 'XE', 'XF', 'XJ', 'F-Type',
                ],
            ],
            [
                'name' => 'Mini',
                'status' => 'active',
                'models' => [
                    'Cooper', 'Cooper S', 'Clubman', 'Countryman', 'Electric', 'John Cooper Works',
                    'Aceman', 'Paceman',
                ],
            ],
            [
                'name' => 'Rolls-Royce',
                'status' => 'active',
                'models' => [
                    'Ghost', 'Phantom', 'Wraith', 'Dawn', 'Cullinan', 'Spectre',
                ],
            ],
            [
                'name' => 'Bentley',
                'status' => 'active',
                'models' => [
                    'Continental GT', 'Continental GTC', 'Bentayga', 'Flying Spur', 'Mulsanne',
                    'Bacalar',
                ],
            ],
            [
                'name' => 'McLaren',
                'status' => 'active',
                'models' => [
                    'GT', 'Artura', '570S', '600LT', '720S', '750S', '765LT', 'Elva',
                    'Senna', 'Speedtail',
                ],
            ],
            [
                'name' => 'Aston Martin',
                'status' => 'active',
                'models' => [
                    'DB11', 'DB12', 'Vantage', 'DBS', 'DBX', 'Valkyrie', 'Vanquish',
                ],
            ],
            [
                'name' => 'Lotus',
                'status' => 'active',
                'models' => [
                    'Emira', 'Eletre', 'Evija', 'Elise', 'Evora', 'Exige',
                ],
            ],

            // ─────────────────────────────────────────
            // ITALIAN BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'Ferrari',
                'status' => 'active',
                'models' => [
                    '296 GTB', '296 GTS', 'Roma', 'Portofino M', 'SF90 Stradale', 'SF90 Spider',
                    '812 Superfast', '812 GTS', 'GTC4Lusso', 'Purosangue', 'F8 Tributo',
                ],
            ],
            [
                'name' => 'Lamborghini',
                'status' => 'active',
                'models' => [
                    'Huracán', 'Huracán EVO', 'Huracán STO', 'Urus', 'Urus S', 'Urus SE',
                    'Revuelto', 'Lanzador',
                ],
            ],
            [
                'name' => 'Maserati',
                'status' => 'active',
                'models' => [
                    'Ghibli', 'Quattroporte', 'Levante', 'GranTurismo', 'GranCabrio',
                    'GranTurismo Folgore', 'Grecale', 'MC20',
                ],
            ],
            [
                'name' => 'Alfa Romeo',
                'status' => 'active',
                'models' => [
                    'Giulia', 'Stelvio', 'Tonale', '4C', 'Giulietta', 'Junior',
                ],
            ],
            [
                'name' => 'Fiat',
                'status' => 'active',
                'models' => [
                    '500', '500X', '500e', 'Panda', 'Ducato', 'Doblo', 'Tipo', 'Fastback',
                    'Pulse', 'Toro',
                ],
            ],
            [
                'name' => 'Iveco',
                'status' => 'active',
                'models' => [
                    'Daily', 'Eurocargo', 'Stralis', 'S-Way', 'Tector', 'Trakker',
                ],
            ],

            // ─────────────────────────────────────────
            // FRENCH BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'Peugeot',
                'status' => 'active',
                'models' => [
                    '208', '308', '408', '508', '2008', '3008', '5008', 'e-208', 'e-2008',
                    'Partner', 'Boxer', 'Expert', 'Landtrek',
                ],
            ],
            [
                'name' => 'Renault',
                'status' => 'active',
                'models' => [
                    'Clio', 'Megane', 'Captur', 'Kadjar', 'Koleos', 'Zoe', 'Megane E-Tech',
                    'Austral', 'Arkana', 'Master', 'Kangoo', 'Trafic', 'Express',
                ],
            ],
            [
                'name' => 'Citroën',
                'status' => 'active',
                'models' => [
                    'C3', 'C4', 'C5 X', 'Berlingo', 'Jumpy', 'Jumper', 'SpaceTourer',
                    'ë-C3', 'ë-Berlingo', 'Ami',
                ],
            ],
            [
                'name' => 'DS Automobiles',
                'status' => 'active',
                'models' => [
                    'DS 3', 'DS 4', 'DS 7', 'DS 9',
                ],
            ],

            // ─────────────────────────────────────────
            // KOREAN BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'Hyundai',
                'status' => 'active',
                'models' => [
                    'Elantra', 'Sonata', 'Tucson', 'Santa Fe', 'Palisade', 'Kona', 'Venue',
                    'Ioniq', 'Ioniq 5', 'Ioniq 6', 'Ioniq 9', 'Santa Cruz', 'Nexo', 'Veloster',
                    'Accent', 'Creta', 'Staria',
                ],
            ],
            [
                'name' => 'Kia',
                'status' => 'active',
                'models' => [
                    'Forte', 'K5', 'Sportage', 'Sorento', 'Telluride', 'Soul', 'Seltos',
                    'Carnival', 'Stinger', 'EV6', 'EV9', 'Niro', 'Rio', 'Sonet', 'EV3', 'EV4',
                ],
            ],
            [
                'name' => 'Genesis',
                'status' => 'active',
                'models' => [
                    'G70', 'G80', 'G90', 'GV60', 'GV70', 'GV80', 'GV90',
                ],
            ],
            [
                'name' => 'SsangYong',
                'status' => 'active',
                'models' => [
                    'Tivoli', 'Korando', 'Rexton', 'Musso', 'Actyon',
                ],
            ],

            // ─────────────────────────────────────────
            // SPANISH BRAND
            // ─────────────────────────────────────────
            [
                'name' => 'SEAT',
                'status' => 'active',
                'models' => [
                    'Ibiza', 'Leon', 'Arona', 'Ateca', 'Tarraco', 'Alhambra', 'Mii',
                ],
            ],
            [
                'name' => 'CUPRA',
                'status' => 'active',
                'models' => [
                    'Formentor', 'Born', 'Ateca', 'Leon', 'Terramar',
                ],
            ],

            // ─────────────────────────────────────────
            // CZECH BRAND
            // ─────────────────────────────────────────
            [
                'name' => 'Skoda',
                'status' => 'active',
                'models' => [
                    'Fabia', 'Scala', 'Octavia', 'Superb', 'Kamiq', 'Karoq', 'Kodiaq', 'Enyaq',
                    'Slavia',
                ],
            ],

            // ─────────────────────────────────────────
            // ROMANIAN BRAND
            // ─────────────────────────────────────────
            [
                'name' => 'Dacia',
                'status' => 'active',
                'models' => [
                    'Sandero', 'Logan', 'Duster', 'Jogger', 'Spring', 'Bigster',
                ],
            ],

            // ─────────────────────────────────────────
            // DUTCH BRAND
            // ─────────────────────────────────────────
            [
                'name' => 'DAF',
                'status' => 'active',
                'models' => [
                    'XF', 'CF', 'LF', 'XG', 'XG+',
                ],
            ],

            // ─────────────────────────────────────────
            // CHINESE BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'BYD',
                'status' => 'active',
                'models' => [
                    'Seal', 'Atto 3', 'Dolphin', 'Han', 'Tang', 'Song Plus', 'Song Pro',
                    'Yuan Plus', 'Destroyer 05', 'Sea Lion', 'Sealion 6', 'Sealion 7',
                    'Seagull', 'Seal U', 'Shark',
                ],
            ],
            [
                'name' => 'NIO',
                'status' => 'active',
                'models' => [
                    'ET5', 'ET5T', 'ET7', 'ES6', 'ES7', 'ES8', 'EC6', 'EC7', 'EL6', 'EL7',
                ],
            ],
            [
                'name' => 'Geely',
                'status' => 'active',
                'models' => [
                    'Coolray', 'Azkarra', 'Okavango', 'Emgrand', 'Atlas', 'Monjaro',
                    'Galaxy E8', 'Preface', 'Tugella',
                ],
            ],
            [
                'name' => 'Haval',
                'status' => 'active',
                'models' => [
                    'H6', 'H9', 'Jolion', 'Big Dog', 'Cheetah', 'Raptor', 'Poer',
                ],
            ],
            [
                'name' => 'Great Wall',
                'status' => 'active',
                'models' => [
                    'Poer', 'Cannon', 'Wingle',
                ],
            ],
            [
                'name' => 'MG',
                'status' => 'active',
                'models' => [
                    'MG3', 'MG5', 'ZS', 'ZS EV', 'HS', 'RX5', 'One', 'MG4', 'Cyberster',
                    'VS HEV', 'MG7',
                ],
            ],
            [
                'name' => 'Chery',
                'status' => 'active',
                'models' => [
                    'Tiggo 4 Pro', 'Tiggo 5x', 'Tiggo 7 Pro', 'Tiggo 8 Pro', 'Arrizo 5',
                    'Arrizo 6 Pro', 'Omoda 5', 'Omoda C5',
                ],
            ],
            [
                'name' => 'Changan',
                'status' => 'active',
                'models' => [
                    'CS35 Plus', 'CS55 Plus', 'CS75 Plus', 'Uni-T', 'Uni-K', 'Uni-V',
                    'Hunter', 'Deepal S7', 'Deepal L07',
                ],
            ],
            [
                'name' => 'GAC',
                'status' => 'active',
                'models' => [
                    'GS3', 'GS4', 'GS8', 'Empow', 'Aion S', 'Aion V', 'Aion Y', 'Aion LX',
                    'Hyptec HT', 'M8',
                ],
            ],
            [
                'name' => 'BAIC',
                'status' => 'active',
                'models' => [
                    'X35', 'X55', 'X65', 'BJ40', 'BJ80', 'EU5', 'EU7', 'EX3',
                ],
            ],
            [
                'name' => 'JAC',
                'status' => 'active',
                'models' => [
                    'J7', 'T6', 'T8', 'S4', 'iEV6E', 'iEV7S',
                ],
            ],
            [
                'name' => 'Dongfeng',
                'status' => 'active',
                'models' => [
                    'AX7 Pro', 'AX5', 'Fengon 5', 'Fengon 7', 'Rich 6', 'Voyah Free',
                    'Voyah Dream',
                ],
            ],
            [
                'name' => 'FAW',
                'status' => 'active',
                'models' => [
                    'Besturn B50', 'Besturn T55', 'Besturn T77', 'Hongqi H9', 'Hongqi E-HS9',
                    'Hongqi HS5',
                ],
            ],
            [
                'name' => 'SAIC',
                'status' => 'active',
                'models' => [
                    'Roewe RX5', 'Roewe i5', 'Roewe D5', 'Marvel R', 'Maxus T60',
                    'Maxus Deliver 9', 'Maxus MIFA 9',
                ],
            ],
            [
                'name' => 'Foton',
                'status' => 'active',
                'models' => [
                    'Tunland', 'Sauvana', 'View CS2', 'Aumark', 'Auman', 'BJ-Series',
                ],
            ],
            [
                'name' => 'Wuling',
                'status' => 'active',
                'models' => [
                    'Hongguang Mini EV', 'Bingo', 'Air EV', 'Almaz', 'Victory',
                ],
            ],
            [
                'name' => 'Xpeng',
                'status' => 'active',
                'models' => [
                    'P5', 'P7', 'G3', 'G6', 'G9', 'X9',
                ],
            ],
            [
                'name' => 'Li Auto',
                'status' => 'active',
                'models' => [
                    'L6', 'L7', 'L8', 'L9', 'Mega', 'Li ONE',
                ],
            ],
            [
                'name' => 'Zeekr',
                'status' => 'active',
                'models' => [
                    '001', '009', 'X', '7X', 'Mix',
                ],
            ],
            [
                'name' => 'Lynk & Co',
                'status' => 'active',
                'models' => [
                    '01', '02', '03', '05', '06', '08', '09',
                ],
            ],
            [
                'name' => 'Hongqi',
                'status' => 'active',
                'models' => [
                    'H5', 'H7', 'H9', 'HS5', 'HS7', 'E-HS9', 'EH7',
                ],
            ],
            [
                'name' => 'Sinotruk',
                'status' => 'active',
                'models' => [
                    'HOWO A7', 'HOWO T7H', 'HOWO TX', 'Sitrak C7H', 'Sitrak G7',
                ],
            ],
            [
                'name' => 'Shacman',
                'status' => 'active',
                'models' => [
                    'X3000', 'H3000', 'F3000', 'L3000', 'M3000', 'E3000',
                ],
            ],

            // ─────────────────────────────────────────
            // INDIAN BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'Tata',
                'status' => 'active',
                'models' => [
                    'Nexon', 'Harrier', 'Safari', 'Punch', 'Tiago', 'Altroz', 'Nexon EV',
                    'Tigor EV', 'Curvv', 'Prima', 'Signa', 'Ultra',
                ],
            ],
            [
                'name' => 'Mahindra',
                'status' => 'active',
                'models' => [
                    'Thar', 'XUV700', 'XUV400', 'Scorpio', 'Scorpio-N', 'Bolero', 'BE6',
                    'XEV9e', 'Marazzo', 'KUV100', 'Pik-Up', 'Supro',
                ],
            ],
            [
                'name' => 'Maruti Suzuki',
                'status' => 'active',
                'models' => [
                    'Alto', 'S-Presso', 'Celerio', 'Wagon R', 'Swift', 'Dzire', 'Baleno',
                    'Ignis', 'Ciaz', 'Ertiga', 'XL6', 'Brezza', 'Grand Vitara', 'Jimny',
                    'Fronx', 'Invicto', 'e Vitara',
                ],
            ],

            // ─────────────────────────────────────────
            // SOUTHEAST ASIAN BRANDS
            // ─────────────────────────────────────────
            [
                'name' => 'Proton',
                'status' => 'active',
                'models' => [
                    'Saga', 'Persona', 'Iriz', 'Exora', 'X50', 'X70', 'X90', 'S70',
                ],
            ],
            [
                'name' => 'Perodua',
                'status' => 'active',
                'models' => [
                    'Axia', 'Bezza', 'Myvi', 'Alza', 'Aruz', 'Ativa',
                ],
            ],

            // ─────────────────────────────────────────
            // RUSSIAN BRAND
            // ─────────────────────────────────────────
            [
                'name' => 'Lada',
                'status' => 'active',
                'models' => [
                    'Granta', 'Vesta', 'Niva', 'Niva Travel', 'Largus', 'XRAY',
                ],
            ],

            // ─────────────────────────────────────────
            // EUROPEAN LUXURY / EXOTIC
            // ─────────────────────────────────────────
            [
                'name' => 'Bugatti',
                'status' => 'active',
                'models' => [
                    'Chiron', 'Chiron Super Sport', 'Veyron', 'Divo', 'Bolide', 'Tourbillon',
                ],
            ],
            [
                'name' => 'Pagani',
                'status' => 'active',
                'models' => [
                    'Huayra', 'Utopia', 'Zonda',
                ],
            ],
            [
                'name' => 'Koenigsegg',
                'status' => 'active',
                'models' => [
                    'Agera', 'Jesko', 'Gemera', 'Regera', 'CC850',
                ],
            ],

            // ─────────────────────────────────────────
            // MISCELLANEOUS / OTHERS
            // ─────────────────────────────────────────
            [
                'name' => 'Lancia',
                'status' => 'active',
                'models' => [
                    'Ypsilon', 'Delta', 'Thesis',
                ],
            ],
            [
                'name' => 'Fisker',
                'status' => 'active',
                'models' => [
                    'Ocean', 'Alaska', 'Ronin', 'PEAR',
                ],
            ],
            [
                'name' => 'Scout',
                'status' => 'active',
                'models' => [
                    'Terra', 'Traveler',
                ],
            ],
            [
                'name' => 'Vinfast',
                'status' => 'active',
                'models' => [
                    'VF3', 'VF5', 'VF6', 'VF7', 'VF8', 'VF9',
                ],
            ],
        ];
    }
}
