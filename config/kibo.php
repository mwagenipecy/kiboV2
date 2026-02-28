<?php

return [
    /*
    | Free plan limits for dealers (when entity has no pricing_plan_id).
    | Counts exclude vehicles/trucks with status "sold".
    */
    'free_max_cars' => (int) env('KIBO_FREE_MAX_CARS', 3),
    'free_max_trucks' => (int) env('KIBO_FREE_MAX_TRUCKS', 1),
    'free_max_leases' => (int) env('KIBO_FREE_MAX_LEASES', 1),

    /*
    | KiboAuto contact (shown on car/truck detail when listing is not on a paid plan).
    */
    'contact' => [
        'email' => env('KIBO_CONTACT_EMAIL', 'info@kiboauto.co.tz'),
        'phone' => env('KIBO_CONTACT_PHONE', '0794 777772'),
        'location' => env('KIBO_CONTACT_LOCATION', 'Sinza kwa Remi, Tan House 9th Floor'),
    ],
];
