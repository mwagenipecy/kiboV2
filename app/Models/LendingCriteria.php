<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LendingCriteria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lending_criteria';

    protected $fillable = [
        'entity_id',
        'name',
        'description',
        'min_vehicle_year',
        'max_vehicle_year',
        'min_vehicle_price',
        'max_vehicle_price',
        'max_mileage',
        'allowed_fuel_types',
        'allowed_transmissions',
        'allowed_body_types',
        'allowed_conditions',
        'min_loan_amount',
        'max_loan_amount',
        'interest_rate',
        'min_loan_term_months',
        'max_loan_term_months',
        'down_payment_percentage',
        'min_credit_score',
        'min_monthly_income',
        'max_debt_to_income_ratio',
        'min_employment_months',
        'require_collateral',
        'require_guarantor',
        'additional_requirements',
        'required_documents',
        'processing_time_days',
        'processing_fee',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'min_vehicle_price' => 'decimal:2',
        'max_vehicle_price' => 'decimal:2',
        'min_loan_amount' => 'decimal:2',
        'max_loan_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'down_payment_percentage' => 'decimal:2',
        'max_debt_to_income_ratio' => 'decimal:2',
        'min_monthly_income' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'allowed_fuel_types' => 'array',
        'allowed_transmissions' => 'array',
        'allowed_body_types' => 'array',
        'allowed_conditions' => 'array',
        'required_documents' => 'array',
        'require_collateral' => 'boolean',
        'require_guarantor' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the entity (lender) that owns the criteria
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * Scope for active criteria
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific entity
     */
    public function scopeForEntity($query, $entityId)
    {
        return $query->where('entity_id', $entityId);
    }

    /**
     * Check if a vehicle meets this criteria
     */
    public function vehicleMeetsCriteria(Vehicle $vehicle): bool
    {
        // Check year range
        if ($this->min_vehicle_year && $vehicle->year < $this->min_vehicle_year) {
            return false;
        }
        if ($this->max_vehicle_year && $vehicle->year > $this->max_vehicle_year) {
            return false;
        }

        // Check price range
        if ($this->min_vehicle_price && $vehicle->price < $this->min_vehicle_price) {
            return false;
        }
        if ($this->max_vehicle_price && $vehicle->price > $this->max_vehicle_price) {
            return false;
        }

        // Check mileage
        if ($this->max_mileage && $vehicle->mileage > $this->max_mileage) {
            return false;
        }

        // Check fuel type
        if ($this->allowed_fuel_types && !in_array($vehicle->fuel_type, $this->allowed_fuel_types)) {
            return false;
        }

        // Check transmission
        if ($this->allowed_transmissions && !in_array($vehicle->transmission, $this->allowed_transmissions)) {
            return false;
        }

        // Check body type
        if ($this->allowed_body_types && !in_array($vehicle->body_type, $this->allowed_body_types)) {
            return false;
        }

        // Check condition
        if ($this->allowed_conditions && !in_array($vehicle->condition, $this->allowed_conditions)) {
            return false;
        }

        return true;
    }

    /**
     * Calculate monthly payment for a loan
     */
    public function calculateMonthlyPayment(float $loanAmount, int $termMonths): float
    {
        $monthlyRate = ($this->interest_rate / 100) / 12;
        
        if ($monthlyRate == 0) {
            return $loanAmount / $termMonths;
        }

        $payment = $loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $termMonths)) / 
                   (pow(1 + $monthlyRate, $termMonths) - 1);

        return round($payment, 2);
    }

    
}

