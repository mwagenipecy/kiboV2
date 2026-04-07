<?php

namespace Database\Seeders;

use App\Models\PricingPlan;
use Illuminate\Database\Seeder;

class PricingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(FreePricingPlanSeeder::class);

        // Cars: monthly dealer subscription (TZS). max_listings = uploads allowed per billing period.
        $carsPlans = [
            [
                'name' => 'Basic Plan',
                'category' => 'cars',
                'description' => 'Ideal for smaller dealers — list up to 10 cars every month.',
                'price' => 30000,
                'currency' => 'TZS',
                'duration_days' => 30,
                'max_listings' => 10,
                'features' => [
                    'Upload up to 10 cars per month',
                    '30-day billing period',
                    'Standard listing tools',
                    'Email support',
                ],
                'is_featured' => false,
                'is_popular' => false,
                'is_active' => true,
                'is_free_tier' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Premium Plan',
                'category' => 'cars',
                'description' => 'More capacity for growing inventory — up to 30 cars per month.',
                'price' => 60000,
                'currency' => 'TZS',
                'duration_days' => 30,
                'max_listings' => 30,
                'features' => [
                    'Upload up to 30 cars per month',
                    '30-day billing period',
                    'Priority placement options',
                    'Email & phone support',
                ],
                'is_featured' => true,
                'is_popular' => true,
                'is_active' => true,
                'is_free_tier' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Ultimate Plan',
                'category' => 'cars',
                'description' => 'Maximum volume — up to 60 cars per month for high-throughput dealers.',
                'price' => 90000,
                'currency' => 'TZS',
                'duration_days' => 30,
                'max_listings' => 60,
                'features' => [
                    'Upload up to 60 cars per month',
                    '30-day billing period',
                    'Top-tier visibility',
                    'Priority support',
                ],
                'is_featured' => true,
                'is_popular' => false,
                'is_active' => true,
                'is_free_tier' => false,
                'sort_order' => 3,
            ],
        ];

        // Trucks Pricing Plans
        $trucksPlans = [
            [
                'name' => 'Standard Listing',
                'category' => 'trucks',
                'description' => 'Essential listing package for trucks',
                'price' => 39.99,
                'currency' => 'GBP',
                'duration_days' => 30,
                'features' => [
                    'Up to 15 photos',
                    'Standard listing placement',
                    'Contact form enabled',
                    'Email support',
                    '30 days listing duration',
                    'Truck specifications display',
                ],
                'is_featured' => false,
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional Listing',
                'category' => 'trucks',
                'description' => 'Professional package for commercial vehicles',
                'price' => 79.99,
                'currency' => 'GBP',
                'duration_days' => 60,
                'features' => [
                    'Up to 40 photos',
                    'Featured placement',
                    'Priority search position',
                    'Contact form + phone number',
                    'Email & phone support',
                    '60 days listing duration',
                    'Professional badge',
                    'Commercial vehicle promotion',
                    'Specifications & documentation',
                ],
                'is_featured' => true,
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise Listing',
                'category' => 'trucks',
                'description' => 'Complete solution for fleet sellers',
                'price' => 149.99,
                'currency' => 'GBP',
                'duration_days' => 90,
                'features' => [
                    'Unlimited photos',
                    'Top placement priority',
                    'Homepage featured spot',
                    'All contact methods',
                    '24/7 dedicated support',
                    '90 days listing duration',
                    'Enterprise badge',
                    'Multi-channel promotion',
                    'Advanced analytics',
                    'Bulk listing tools',
                    'Fleet management features',
                ],
                'is_featured' => true,
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        // Garage Pricing Plans
        $garagePlans = [
            [
                'name' => 'Basic Profile',
                'category' => 'garage',
                'description' => 'Essential garage listing package',
                'price' => 49.99,
                'currency' => 'GBP',
                'duration_days' => 30,
                'features' => [
                    'Business profile listing',
                    'Up to 10 photos',
                    'Contact information',
                    'Service listings',
                    'Basic search visibility',
                    '30 days active listing',
                ],
                'is_featured' => false,
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional Profile',
                'category' => 'garage',
                'description' => 'Complete garage business package',
                'price' => 99.99,
                'currency' => 'GBP',
                'duration_days' => 60,
                'features' => [
                    'Enhanced business profile',
                    'Up to 30 photos',
                    'All contact methods',
                    'Unlimited service listings',
                    'Featured in search results',
                    'Priority placement',
                    'Customer reviews enabled',
                    'Booking system integration',
                    '60 days active listing',
                    'Email & phone support',
                ],
                'is_featured' => true,
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium Garage',
                'category' => 'garage',
                'description' => 'Ultimate package for established garages',
                'price' => 199.99,
                'currency' => 'GBP',
                'duration_days' => 90,
                'features' => [
                    'Premium business profile',
                    'Unlimited photos & videos',
                    'All contact & booking methods',
                    'Unlimited services & promotions',
                    'Top search placement',
                    'Homepage featured spot',
                    'Advanced review system',
                    'Full booking management',
                    'Analytics dashboard',
                    'Lead management tools',
                    'Social media integration',
                    '90 days active listing',
                    '24/7 priority support',
                ],
                'is_featured' => true,
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        $carPlanNames = collect($carsPlans)->pluck('name')->all();

        foreach ($carsPlans as $plan) {
            PricingPlan::updateOrCreate(
                [
                    'category' => 'cars',
                    'name' => $plan['name'],
                ],
                $plan
            );
        }

        PricingPlan::query()
            ->where('category', 'cars')
            ->where('is_free_tier', false)
            ->whereNotIn('name', $carPlanNames)
            ->delete();

        foreach ($trucksPlans as $plan) {
            PricingPlan::updateOrCreate(
                [
                    'category' => 'trucks',
                    'name' => $plan['name'],
                ],
                $plan
            );
        }

        foreach ($garagePlans as $plan) {
            PricingPlan::updateOrCreate(
                [
                    'category' => 'garage',
                    'name' => $plan['name'],
                ],
                $plan
            );
        }
    }
}
