<?php

namespace Database\Seeders;

use App\Models\Entity;
use App\Models\LendingCriteria;
use Illuminate\Database\Seeder;

class LendingCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all lenders
        $lenders = Entity::where('type', 'lender')->get();

        if ($lenders->isEmpty()) {
            $this->command->warn('⚠️  No lenders found. Please run LenderSeeder first.');
            return;
        }

        $criteriaTemplates = [
            [
                'name' => 'Standard Auto Loan',
                'description' => 'Our most popular auto loan package for used vehicles with competitive rates.',
                'min_vehicle_year' => 2015,
                'max_vehicle_year' => 2024,
                'min_vehicle_price' => 5000,
                'max_vehicle_price' => 50000,
                'max_mileage' => 150000,
                'allowed_fuel_types' => ['petrol', 'diesel', 'hybrid'],
                'allowed_transmissions' => ['manual', 'automatic'],
                'allowed_body_types' => ['sedan', 'suv', 'hatchback', 'wagon'],
                'allowed_conditions' => ['used', 'certified_pre_owned'],
                'min_loan_amount' => 3000,
                'max_loan_amount' => 40000,
                'interest_rate' => 8.99,
                'min_loan_term_months' => 12,
                'max_loan_term_months' => 72,
                'down_payment_percentage' => 20,
                'min_credit_score' => 650,
                'min_monthly_income' => 2000,
                'max_debt_to_income_ratio' => 40,
                'min_employment_months' => 6,
                'require_collateral' => true,
                'require_guarantor' => false,
                'required_documents' => ['id', 'proof_of_income', 'bank_statements'],
                'processing_time_days' => 7,
                'processing_fee' => 250,
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'name' => 'Premium Vehicle Financing',
                'description' => 'Exclusive financing for high-value and luxury vehicles with premium service.',
                'min_vehicle_year' => 2020,
                'max_vehicle_year' => 2025,
                'min_vehicle_price' => 30000,
                'max_vehicle_price' => 150000,
                'max_mileage' => 50000,
                'allowed_fuel_types' => ['petrol', 'diesel', 'electric', 'hybrid'],
                'allowed_transmissions' => ['automatic'],
                'allowed_body_types' => ['sedan', 'suv', 'coupe', 'convertible'],
                'allowed_conditions' => ['new', 'certified_pre_owned'],
                'min_loan_amount' => 20000,
                'max_loan_amount' => 120000,
                'interest_rate' => 6.99,
                'min_loan_term_months' => 24,
                'max_loan_term_months' => 84,
                'down_payment_percentage' => 30,
                'min_credit_score' => 720,
                'min_monthly_income' => 5000,
                'max_debt_to_income_ratio' => 35,
                'min_employment_months' => 12,
                'require_collateral' => true,
                'require_guarantor' => false,
                'required_documents' => ['id', 'proof_of_income', 'bank_statements', 'credit_report', 'employment_letter'],
                'processing_time_days' => 5,
                'processing_fee' => 500,
                'is_active' => true,
                'priority' => 2,
            ],
            [
                'name' => 'First-Time Buyer Program',
                'description' => 'Designed for first-time car buyers with flexible requirements and guidance.',
                'min_vehicle_year' => 2012,
                'max_vehicle_year' => 2024,
                'min_vehicle_price' => 3000,
                'max_vehicle_price' => 25000,
                'max_mileage' => 120000,
                'allowed_fuel_types' => ['petrol', 'diesel'],
                'allowed_transmissions' => ['manual', 'automatic'],
                'allowed_body_types' => ['sedan', 'hatchback', 'wagon'],
                'allowed_conditions' => ['used'],
                'min_loan_amount' => 2000,
                'max_loan_amount' => 20000,
                'interest_rate' => 11.99,
                'min_loan_term_months' => 12,
                'max_loan_term_months' => 60,
                'down_payment_percentage' => 25,
                'min_credit_score' => 600,
                'min_monthly_income' => 1500,
                'max_debt_to_income_ratio' => 45,
                'min_employment_months' => 3,
                'require_collateral' => true,
                'require_guarantor' => true,
                'required_documents' => ['id', 'proof_of_income', 'bank_statements', 'utility_bill'],
                'processing_time_days' => 10,
                'processing_fee' => 150,
                'is_active' => true,
                'priority' => 0,
            ],
            [
                'name' => 'Green Vehicle Financing',
                'description' => 'Special rates for electric and hybrid vehicles to promote eco-friendly transportation.',
                'min_vehicle_year' => 2018,
                'max_vehicle_year' => 2025,
                'min_vehicle_price' => 15000,
                'max_vehicle_price' => 80000,
                'max_mileage' => 60000,
                'allowed_fuel_types' => ['electric', 'hybrid'],
                'allowed_transmissions' => ['automatic'],
                'allowed_body_types' => ['sedan', 'suv', 'hatchback'],
                'allowed_conditions' => ['new', 'used', 'certified_pre_owned'],
                'min_loan_amount' => 10000,
                'max_loan_amount' => 65000,
                'interest_rate' => 5.99,
                'min_loan_term_months' => 24,
                'max_loan_term_months' => 84,
                'down_payment_percentage' => 15,
                'min_credit_score' => 680,
                'min_monthly_income' => 2500,
                'max_debt_to_income_ratio' => 38,
                'min_employment_months' => 6,
                'require_collateral' => true,
                'require_guarantor' => false,
                'required_documents' => ['id', 'proof_of_income', 'bank_statements'],
                'processing_time_days' => 5,
                'processing_fee' => 200,
                'is_active' => true,
                'priority' => 3,
            ],
        ];

        $totalCreated = 0;

        // Create 2-3 criteria for each lender
        foreach ($lenders as $index => $lender) {
            // Each lender gets different combinations
            $criteriaSets = [
                [0, 2], // Standard & First-Time
                [0, 1], // Standard & Premium
                [0, 3], // Standard & Green
                [0, 1, 2], // Standard, Premium & First-Time
                [0, 2, 3], // Standard, First-Time & Green
            ];

            $selectedSet = $criteriaSets[$index % count($criteriaSets)];

            foreach ($selectedSet as $templateIndex) {
                $template = $criteriaTemplates[$templateIndex];
                $template['entity_id'] = $lender->id;
                
                // Slight variation in rates per lender
                $template['interest_rate'] += ($index * 0.25);
                
                LendingCriteria::create($template);
                $totalCreated++;
            }
        }

        $this->command->info("✅ Created {$totalCreated} lending criteria across " . $lenders->count() . " lenders!");
    }
}
