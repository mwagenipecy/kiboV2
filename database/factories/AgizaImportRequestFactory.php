<?php

namespace Database\Factories;

use App\Models\AgizaImportRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgizaImportRequestFactory extends Factory
{
    protected $model = AgizaImportRequest::class;

    public function definition(): array
    {
        return [
            'request_number' => AgizaImportRequest::generateRequestNumber(),
            'user_id' => User::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '07' . fake()->numerify('########'),
            'vehicle_make' => fake()->randomElement(['Toyota', 'Honda', 'Nissan', 'BMW', 'Mercedes-Benz']),
            'vehicle_model' => fake()->randomElement(['Land Cruiser', 'Prado', 'Harrier', 'X5', 'C-Class']),
            'vehicle_year' => fake()->numberBetween(2015, 2025),
            'vehicle_condition' => fake()->randomElement(['new', 'used']),
            'vehicle_link' => fake()->url(),
            'source_country' => fake()->randomElement(['Japan', 'United Kingdom', 'United States', 'Germany', 'South Africa']),
            'request_type' => fake()->randomElement(['with_link', 'already_contacted']),
            'dealer_contact_info' => fake()->optional()->text(200),
            'estimated_price' => fake()->optional()->randomFloat(2, 10000, 100000),
            'price_currency' => 'USD',
            'special_requirements' => fake()->optional()->text(150),
            'customer_notes' => fake()->optional()->text(200),
            'documents' => [],
            'vehicle_images' => [],
            'status' => fake()->randomElement(['pending', 'under_review', 'quote_provided', 'accepted', 'in_progress']),
            'assigned_to' => null,
            'admin_notes' => fake()->optional()->text(150),
            'quoted_import_cost' => fake()->optional()->randomFloat(2, 3000, 10000),
            'quoted_total_cost' => fake()->optional()->randomFloat(2, 5000, 15000),
            'quote_currency' => 'USD',
            'quoted_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'accepted_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function underReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'under_review',
        ]);
    }

    public function quoted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'quote_provided',
            'quoted_import_cost' => fake()->randomFloat(2, 3000, 10000),
            'quoted_total_cost' => fake()->randomFloat(2, 5000, 15000),
            'quote_currency' => 'USD',
            'quoted_at' => now(),
        ]);
    }
}
