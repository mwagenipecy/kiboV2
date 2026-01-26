<?php

namespace App\Livewire\Admin\LendingCriteria;

use App\Models\Entity;
use App\Models\LendingCriteria;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CriteriaForm extends Component
{
    public $criteriaId = null;
    public $entity_id = '';
    public $name = '';
    public $description = '';
    public $userIsAdmin = false;
    public $userEntityName = '';
    
    // Vehicle Requirements
    public $min_vehicle_year = '';
    public $max_vehicle_year = '';
    public $min_vehicle_price = '';
    public $max_vehicle_price = '';
    public $max_mileage = '';
    public $allowed_fuel_types = [];
    public $allowed_transmissions = [];
    public $allowed_body_types = [];
    public $allowed_conditions = [];
    
    // Loan Terms
    public $min_loan_amount = '';
    public $max_loan_amount = '';
    public $interest_rate = '';
    public $min_loan_term_months = 12;
    public $max_loan_term_months = 84;
    public $down_payment_percentage = '';
    
    // Borrower Requirements
    public $min_credit_score = '';
    public $min_monthly_income = '';
    public $max_debt_to_income_ratio = '';
    public $min_employment_months = '';
    public $require_collateral = true;
    public $require_guarantor = false;
    
    // Additional
    public $additional_requirements = '';
    public $required_documents = [];
    public $processing_time_days = '';
    public $processing_fee = '';
    public $is_active = true;
    public $priority = 0;

    protected function rules()
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;
        
        return [
            'entity_id' => $userRole === 'admin' ? 'required|exists:entities,id' : 'required|exists:entities,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'min_vehicle_year' => 'nullable|integer|min:1900',
        'max_vehicle_year' => 'nullable|integer|min:1900',
        'min_vehicle_price' => 'nullable|numeric|min:0',
        'max_vehicle_price' => 'nullable|numeric|min:0',
        'max_mileage' => 'nullable|integer|min:0',
        'min_loan_amount' => 'nullable|numeric|min:0',
        'max_loan_amount' => 'nullable|numeric|min:0',
        'interest_rate' => 'required|numeric|min:0|max:100',
        'min_loan_term_months' => 'required|integer|min:1',
        'max_loan_term_months' => 'required|integer|min:1',
        'down_payment_percentage' => 'required|numeric|min:0|max:100',
        'min_credit_score' => 'nullable|integer|min:0|max:850',
        'min_monthly_income' => 'nullable|numeric|min:0',
        'max_debt_to_income_ratio' => 'nullable|numeric|min:0|max:100',
        'min_employment_months' => 'nullable|integer|min:0',
        'processing_time_days' => 'nullable|integer|min:0',
        'processing_fee' => 'nullable|numeric|min:0',
            'priority' => 'nullable|integer',
        ];
    }

    public function mount($id = null)
    {
        $user = Auth::user();
        $this->userIsAdmin = $user->isAdmin();
        
        if (!$this->userIsAdmin && $user->entity) {
            $this->entity_id = $user->entity_id;
            $this->userEntityName = $user->entity->name;
        }
        
        if ($id) {
            $this->criteriaId = $id;
            $this->loadCriteria();
        }
    }

    public function loadCriteria()
    {
        $criteria = LendingCriteria::findOrFail($this->criteriaId);
        
        // For non-admin users, ensure they can only edit their own entity's criteria
        if (!$this->userIsAdmin) {
            $user = Auth::user();
            if ($criteria->entity_id !== $user->entity_id) {
                session()->flash('error', 'You do not have permission to edit this lending criteria.');
                return redirect()->route('admin.lending-criteria.index');
            }
            // Ensure entity_id and entity name are set for non-admin users
            if ($user->entity) {
                $this->entity_id = $user->entity_id;
                $this->userEntityName = $user->entity->name;
            }
        } else {
            $this->entity_id = $criteria->entity_id;
        }
        $this->name = $criteria->name;
        $this->description = $criteria->description;
        $this->min_vehicle_year = $criteria->min_vehicle_year;
        $this->max_vehicle_year = $criteria->max_vehicle_year;
        $this->min_vehicle_price = $criteria->min_vehicle_price;
        $this->max_vehicle_price = $criteria->max_vehicle_price;
        $this->max_mileage = $criteria->max_mileage;
        $this->allowed_fuel_types = $criteria->allowed_fuel_types ?? [];
        $this->allowed_transmissions = $criteria->allowed_transmissions ?? [];
        $this->allowed_body_types = $criteria->allowed_body_types ?? [];
        $this->allowed_conditions = $criteria->allowed_conditions ?? [];
        $this->min_loan_amount = $criteria->min_loan_amount;
        $this->max_loan_amount = $criteria->max_loan_amount;
        $this->interest_rate = $criteria->interest_rate;
        $this->min_loan_term_months = $criteria->min_loan_term_months;
        $this->max_loan_term_months = $criteria->max_loan_term_months;
        $this->down_payment_percentage = $criteria->down_payment_percentage;
        $this->min_credit_score = $criteria->min_credit_score;
        $this->min_monthly_income = $criteria->min_monthly_income;
        $this->max_debt_to_income_ratio = $criteria->max_debt_to_income_ratio;
        $this->min_employment_months = $criteria->min_employment_months;
        $this->require_collateral = $criteria->require_collateral;
        $this->require_guarantor = $criteria->require_guarantor;
        $this->additional_requirements = $criteria->additional_requirements;
        $this->required_documents = $criteria->required_documents ?? [];
        $this->processing_time_days = $criteria->processing_time_days;
        $this->processing_fee = $criteria->processing_fee;
        $this->is_active = $criteria->is_active;
        $this->priority = $criteria->priority;
    }

    public function save()
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;
        
        // Ensure entity_id is set for non-admin users
        if ($userRole !== 'admin') {
            if (!$user->entity_id) {
                session()->flash('error', 'You cannot create lending criteria without an associated entity. Please contact an administrator.');
                return;
            }
            $this->entity_id = $user->entity_id;
        }
        
        $this->validate();

        $data = [
            'entity_id' => $this->entity_id,
            'name' => $this->name,
            'description' => $this->description,
            'min_vehicle_year' => $this->min_vehicle_year ?: null,
            'max_vehicle_year' => $this->max_vehicle_year ?: null,
            'min_vehicle_price' => $this->min_vehicle_price ?: null,
            'max_vehicle_price' => $this->max_vehicle_price ?: null,
            'max_mileage' => $this->max_mileage ?: null,
            'allowed_fuel_types' => $this->allowed_fuel_types ?: null,
            'allowed_transmissions' => $this->allowed_transmissions ?: null,
            'allowed_body_types' => $this->allowed_body_types ?: null,
            'allowed_conditions' => $this->allowed_conditions ?: null,
            'min_loan_amount' => $this->min_loan_amount ?: null,
            'max_loan_amount' => $this->max_loan_amount ?: null,
            'interest_rate' => $this->interest_rate,
            'min_loan_term_months' => $this->min_loan_term_months,
            'max_loan_term_months' => $this->max_loan_term_months,
            'down_payment_percentage' => $this->down_payment_percentage,
            'min_credit_score' => $this->min_credit_score ?: null,
            'min_monthly_income' => $this->min_monthly_income ?: null,
            'max_debt_to_income_ratio' => $this->max_debt_to_income_ratio ?: null,
            'min_employment_months' => $this->min_employment_months ?: null,
            'require_collateral' => $this->require_collateral,
            'require_guarantor' => $this->require_guarantor,
            'additional_requirements' => $this->additional_requirements,
            'required_documents' => $this->required_documents ?: null,
            'processing_time_days' => $this->processing_time_days ?: null,
            'processing_fee' => $this->processing_fee ?: null,
            'is_active' => $this->is_active,
            'priority' => $this->priority,
        ];

        if ($this->criteriaId) {
            LendingCriteria::findOrFail($this->criteriaId)->update($data);
            session()->flash('success', 'Lending criteria updated successfully!');
        } else {
            LendingCriteria::create($data);
            session()->flash('success', 'Lending criteria created successfully!');
        }

        return redirect()->route('admin.lending-criteria.index');
    }

    public function render()
    {
        $entities = Entity::where('type', 'lender')->get();
        
        return view('livewire.admin.lending-criteria.criteria-form', [
            'entities' => $entities,
            'userIsAdmin' => $this->userIsAdmin,
            'userEntityName' => $this->userEntityName,
        ]);
    }
}

