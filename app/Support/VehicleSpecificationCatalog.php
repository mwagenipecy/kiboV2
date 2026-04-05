<?php

namespace App\Support;

/**
 * Canonical comfort / safety labels for vehicle listings (stored in features + safety_features JSON).
 */
final class VehicleSpecificationCatalog
{
    /**
     * @return list<string>
     */
    public static function comfort(): array
    {
        return [
            'Air Conditioning',
            'Climate Control',
            'Fog Lights',
            'Daytime Running Lights',
            'LED Headlights',
            'Sunroof',
            'Panoramic Roof',
            'Leather Seats',
            'Heated Seats',
            'Ventilated Seats',
            'Power Windows',
            'Power Mirrors',
            'Electric Seats',
            'Memory Seats',
            'Keyless Entry',
            'Push Button Start',
            'Cruise Control',
            'Parking Sensors',
            'Rear Parking Camera',
            'Backup Camera',
            '360 Camera',
            'Apple CarPlay',
            'Android Auto',
            'Bluetooth',
            'Navigation',
            'Infotainment Screen',
            'Wireless Charging',
            'Alloy Wheels',
            'Roof Rails',
            'Tow Bar',
            'Spare Wheel',
            'Rain-Sensing Wipers',
        ];
    }

    /**
     * @return list<string>
     */
    public static function safety(): array
    {
        return [
            'ABS',
            'Airbags',
            'Side Airbags',
            'Curtain Airbags',
            'Electronic Stability Control',
            'Stability Control',
            'Traction Control',
            'ISOFIX',
            'Tyre Pressure Monitoring',
            'Lane Departure Warning',
            'Lane Keeping Assist',
            'Blind Spot Monitoring',
            'Adaptive Cruise Control',
            'Forward Collision Warning',
            'Automatic Emergency Braking',
            'Hill Start Assist',
            'Hill Descent Control',
            'Parking Sensors',
            'Backup Camera',
            'Alarm',
            'Immobiliser',
            'Toyota Safety Sense',
            'Honda Sensing',
            'Honda Sensing Suite',
            'Nissan Safety Shield 360',
            'Hyundai SmartSense',
            'i-ACTIVSENSE Safety Suite',
            'IQ.DRIVE Safety',
            'Co-Pilot360',
            'EyeSight Driver Assist',
            'Active Safety Features',
            'Active Driving Assistant',
            'Driving Assistant Professional',
            'Active Brake Assist',
            'Advanced Safety Package',
            'Collision Mitigation',
            'Parking Assistant',
            'Night Vision',
        ];
    }

    /**
     * Whether a stored list includes this catalog label (case-insensitive).
     *
     * @param  list<string>|null  $stored
     */
    public static function hasLabel(?array $stored, string $label): bool
    {
        foreach ($stored ?? [] as $item) {
            if (strcasecmp(trim((string) $item), trim($label)) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Values present in $stored that are not in the given catalog (case-insensitive).
     *
     * @param  list<string>|null  $stored
     * @param  list<string>  $catalog
     * @return list<string>
     */
    public static function extrasNotInCatalog(?array $stored, array $catalog): array
    {
        $out = [];
        foreach ($stored ?? [] as $item) {
            $item = trim((string) $item);
            if ($item === '') {
                continue;
            }
            $inCatalog = false;
            foreach ($catalog as $label) {
                if (strcasecmp($item, $label) === 0) {
                    $inCatalog = true;
                    break;
                }
            }
            if (! $inCatalog) {
                $out[] = $item;
            }
        }

        return array_values(array_unique($out));
    }

    /**
     * Intersect submitted checkboxes with allowed catalog labels (preserves catalog order).
     *
     * @param  list<string>|null  $submitted
     * @return list<string>
     */
    public static function filterToCatalog(?array $submitted, array $catalog): array
    {
        $submitted = $submitted ?? [];
        $allowed = [];
        foreach ($catalog as $label) {
            foreach ($submitted as $v) {
                if (strcasecmp(trim((string) $v), $label) === 0) {
                    $allowed[] = $label;
                    break;
                }
            }
        }

        return array_values(array_unique($allowed));
    }
}
