<?php

$kiboOfficeLocations = [
    'Branch: Sinza Mori, Near Kitambaa Cheupe',
    'Headquarters  Tan House, 9th Floor',
];

return [
    /*
    | Fallback limits when no active Free tier row exists in advertising_pricing.
    | Prefer editing the “Free” plan (slug free, is_free_tier) in admin pricing.
    | Counts exclude vehicles/trucks with status "sold".
    */
    'free_max_cars' => (int) env('KIBO_FREE_MAX_CARS', 6),
    'free_max_trucks' => (int) env('KIBO_FREE_MAX_TRUCKS', 1),
    'free_max_leases' => (int) env('KIBO_FREE_MAX_LEASES', 1),

    /*
    | KiboAuto contact (shown on car/truck detail when listing is not on a paid plan).
    | locations: display lines (footer, etc.). location: single string (e.g. WhatsApp); override with KIBO_CONTACT_LOCATION.
    */
    'contact' => [
        'email' => env('KIBO_CONTACT_EMAIL', 'info@kiboauto.co.tz'),
        'phone' => env('KIBO_CONTACT_PHONE', '0794 777772'),
        'locations' => $kiboOfficeLocations,
        'location' => env('KIBO_CONTACT_LOCATION', implode("\n", $kiboOfficeLocations)),
    ],
];
