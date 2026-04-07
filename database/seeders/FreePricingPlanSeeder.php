<?php

namespace Database\Seeders;

use App\Models\PricingPlan;
use Illuminate\Database\Seeder;

class FreePricingPlanSeeder extends Seeder
{
    /**
     * Seed the dealer free tier row (slug: free).
     *
     * Limits are read by Entity when no paid plan is assigned; not shown on public pricing / checkout.
     */
    public function run(): void
    {
        PricingPlan::updateOrCreate(
            ['slug' => 'free'],
            [
                'name' => 'Free',
                'category' => 'cars',
                'description' => 'No-cost tier — limited active car listings (excluding sold). Upgrade anytime for more capacity.',
                'price' => 0,
                'currency' => 'TZS',
                'duration_days' => null,
                'max_listings' => 6,
                'max_trucks' => 1,
                'max_leases' => 1,
                'features' => [
                    'List up to 6 active cars (excluding sold)',
                    '1 truck listing (excluding sold)',
                    '1 lease listing',
                    'Standard listing tools',
                ],
                'is_featured' => false,
                'is_popular' => false,
                'is_active' => true,
                'is_free_tier' => true,
                'sort_order' => 0,
            ]
        );
    }
}
