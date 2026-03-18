<?php

namespace Database\Factories;

use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntityFactory extends Factory
{
    protected $model = Entity::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'type' => EntityType::DEALER,
            'status' => EntityStatus::ACTIVE,
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip_code' => fake()->postcode(),
            'country' => 'Kenya',
            'registration_number' => 'REG-' . fake()->unique()->numberBetween(100000, 999999),
            'tax_id' => 'TAX-' . fake()->unique()->numberBetween(100000, 999999),
            'website' => fake()->url(),
            'description' => fake()->paragraph(),
            'metadata' => [
                'primary_user_name' => fake()->name(),
                'primary_user_email' => fake()->unique()->safeEmail(),
            ],
            'pricing_plan_id' => null,
        ];
    }

    public function dealer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => EntityType::DEALER,
        ]);
    }

    public function lender(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => EntityType::LENDER,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EntityStatus::PENDING,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EntityStatus::ACTIVE,
        ]);
    }
}
