<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use ResourceBundle;

class CountrySeeder extends Seeder
{
    /**
     * Seed countries from ICU region data (PHP intl). No city rows — cities are user-entered on vehicles.
     */
    public function run(): void
    {
        if (! extension_loaded('intl')) {
            $this->command?->error('PHP intl extension is required to seed countries. Enable intl and run: php artisan db:seed --class=CountrySeeder');

            return;
        }

        $root = ResourceBundle::create('en', 'ICUDATA-region');
        $bundle = $root['Countries'] ?? null;

        if (! $bundle) {
            $this->command?->error('Could not load ICU country data.');

            return;
        }

        $count = 0;
        foreach ($bundle as $code => $name) {
            if (strlen((string) $code) !== 2 || ! is_string($name)) {
                continue;
            }

            $code = strtoupper($code);
            Country::query()->updateOrCreate(
                ['code' => $code],
                ['name' => $name]
            );
            $count++;
        }

        $this->command?->info("Countries seeded: {$count}.");
    }
}
