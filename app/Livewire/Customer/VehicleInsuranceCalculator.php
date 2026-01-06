<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Vehicle;

class VehicleInsuranceCalculator extends Component
{
    // Step 1: Basic inputs
    public $insurableValue = '';
    public $year = '';
    public $startDate = '';
    
    // Step 2: Vehicle Class and Details
    public $vehicleClass = '';
    public $carryingPassengers = 'No';
    public $noPassengers = 4;
    
    // Step 3: Coverage and Status
    public $typeOfCover = '';
    public $claimStatus = 'Free Vehicle';
    
    // Results
    public $calculationResults = null;
    public $showResults = false;
    
    // Vehicle from car details page
    public $vehicleId = null;
    public $vehicle = null;

    // Vehicle Classes
    public $vehicleClasses = [
        'Private Cars' => 'Private Cars',
        'Motorcycle Wheelers 2' => 'Motorcycle Wheelers 2',
        'Motorcycle Wheelers 3' => 'Motorcycle Wheelers 3',
        'Commercial Vehicles' => 'Commercial Vehicles',
        'Special Type of Vehicle' => 'Special Type of Vehicle'
    ];

    // Claim Status options
    public $claimStatusOptions = [
        'Free Vehicle' => 'Free Vehicle',
        'Claim Record' => 'Claim Record'
    ];

    // Coverage types based on vehicle class and claim status - EXACT Excel rates
    public $coverageOptions = [
        'Private Cars' => [
            'Free Vehicle' => [
                'Private Cars Comprehensive Free Vehicle' => ['rate' => 0.035, 'min_premium' => 250000, 'type' => 'CC'],
                'Private Cars Third Party Fire Theft' => ['rate' => 0.02, 'min_premium' => 200000, 'type' => 'TPFT'],
                'Private Cars Third Party Only' => ['rate' => 0, 'min_premium' => 100000, 'type' => 'TPO']
            ],
            'Claim Record' => [
                'Private Cars Comprehensive Claim Record' => ['rate' => 0.04, 'min_premium' => 250000, 'type' => 'CC'],
                'Private Cars Third Party Fire Theft' => ['rate' => 0.02, 'min_premium' => 200000, 'type' => 'TPFT'],
                'Private Cars Third Party Only' => ['rate' => 0, 'min_premium' => 100000, 'type' => 'TPO']
            ]
        ],
        'Motorcycle Wheelers 2' => [
            'Free Vehicle' => [
                'Motorcycle Wheelers 2 Comprehensive Free Vehicle' => ['rate' => 0.05, 'min_premium' => 0, 'additional' => 15000, 'type' => 'CC'],
                'Motorcycle Wheelers 2 Third Party Fire Theft' => ['rate' => 0.035, 'min_premium' => 100000, 'additional' => 15000, 'tpp' => 50000, 'type' => 'TPFT'],
                'Motorcycle Wheelers 2 Third Party Only' => ['rate' => 0, 'min_premium' => 50000, 'additional' => 15000, 'type' => 'TPO']
            ],
            'Claim Record' => [
                'Motorcycle Wheelers 2 Comprehensive Claim Record' => ['rate' => 0.06, 'min_premium' => 0, 'additional' => 15000, 'type' => 'CC'],
                'Motorcycle Wheelers 2 Third Party Fire Theft' => ['rate' => 0.035, 'min_premium' => 100000, 'additional' => 15000, 'tpp' => 50000, 'type' => 'TPFT'],
                'Motorcycle Wheelers 2 Third Party Only' => ['rate' => 0, 'min_premium' => 50000, 'additional' => 15000, 'type' => 'TPO']
            ]
        ],
        'Motorcycle Wheelers 3' => [
            'Free Vehicle' => [
                'Motorcycle Wheelers 3 Comprehensive Free Vehicle' => ['rate' => 0.06, 'min_premium' => 125000, 'additional' => 45000, 'type' => 'CC'],
                'Motorcycle Wheelers 3 Third Party Fire Theft' => ['rate' => 0.035, 'min_premium' => 100000, 'additional' => 45000, 'tpp' => 75000, 'type' => 'TPFT'],
                'Motorcycle Wheelers 3 Third Party Only' => ['rate' => 0, 'min_premium' => 75000, 'additional' => 45000, 'type' => 'TPO']
            ],
            'Claim Record' => [
                'Motorcycle Wheelers 3 Comprehensive Claim Record' => ['rate' => 0.07, 'min_premium' => 125000, 'additional' => 45000, 'type' => 'CC'],
                'Motorcycle Wheelers 3 Third Party Fire Theft' => ['rate' => 0.035, 'min_premium' => 100000, 'additional' => 45000, 'tpp' => 75000, 'type' => 'TPFT'],
                'Motorcycle Wheelers 3 Third Party Only' => ['rate' => 0, 'min_premium' => 75000, 'additional' => 45000, 'type' => 'TPO']
            ]
        ],
        'Commercial Vehicles' => [
            'Free Vehicle' => [
                'Commercial Vehicles Own Goods Comprehensive Free Vehicle' => ['rate' => 0.0425, 'min_premium' => 500000, 'type' => 'CC'],
                'Commercial Vehicles Own Goods Third Party Fire Theft' => ['rate' => 0.025, 'min_premium' => 350000, 'tpp' => 300000, 'type' => 'TPFT'],
                'Commercial Vehicles General Cartage Comprehensive Free Vehicle' => ['rate' => 0.05, 'min_premium' => 500000, 'type' => 'CC'],
                'Commercial Vehicles General Cartage Third Party Fire Theft' => ['rate' => 0.03, 'min_premium' => 350000, 'tpp' => 300000, 'type' => 'TPFT'],
                'Commercial Vehicles Third Party Only (up to 2 tonnes)' => ['rate' => 0, 'min_premium' => 150000, 'type' => 'TPO'],
                'Commercial Vehicles Third Party Only (5-10 tonnes)' => ['rate' => 0, 'min_premium' => 250000, 'type' => 'TPO'],
                'Commercial Vehicles Third Party Only (above 10 tonnes)' => ['rate' => 0, 'min_premium' => 300000, 'type' => 'TPO']
            ],
            'Claim Record' => [
                'Commercial Vehicles Own Goods Comprehensive Claim Record' => ['rate' => 0.0475, 'min_premium' => 500000, 'type' => 'CC'],
                'Commercial Vehicles Own Goods Third Party Fire Theft' => ['rate' => 0.025, 'min_premium' => 350000, 'tpp' => 300000, 'type' => 'TPFT'],
                'Commercial Vehicles General Cartage Comprehensive Claim Record' => ['rate' => 0.0575, 'min_premium' => 500000, 'type' => 'CC'],
                'Commercial Vehicles General Cartage Third Party Fire Theft' => ['rate' => 0.03, 'min_premium' => 350000, 'tpp' => 300000, 'type' => 'TPFT'],
                'Commercial Vehicles Third Party Only (up to 2 tonnes)' => ['rate' => 0, 'min_premium' => 150000, 'type' => 'TPO'],
                'Commercial Vehicles Third Party Only (5-10 tonnes)' => ['rate' => 0, 'min_premium' => 250000, 'type' => 'TPO'],
                'Commercial Vehicles Third Party Only (above 10 tonnes)' => ['rate' => 0, 'min_premium' => 300000, 'type' => 'TPO']
            ]
        ],
        'Special Type of Vehicle' => [
            'Free Vehicle' => [
                'Special Type of Vehicle Comprehensive (Farm Tractors, Forklifts, etc.)' => ['rate' => 0.02, 'min_premium' => 250000, 'type' => 'CC']
            ],
            'Claim Record' => [
                'Special Type of Vehicle Comprehensive (Farm Tractors, Forklifts, etc.)' => ['rate' => 0.02, 'min_premium' => 250000, 'type' => 'CC']
            ]
        ]
    ];

    protected function rules(): array
    {
        return [
            'insurableValue' => 'required|numeric|min:500000',
            'vehicleClass' => 'required',
            'typeOfCover' => 'required',
            'claimStatus' => 'required',
            'year' => 'required|integer|min:1980|max:' . (date('Y') + 1),
            'startDate' => 'required|date',
            'noPassengers' => 'required|integer|min:0|max:50',
        ];
    }

    protected $messages = [
        'insurableValue.required' => 'Please enter the insurable value of your vehicle.',
        'insurableValue.numeric' => 'Insurable value must be a number.',
        'insurableValue.min' => 'Minimum insurable value is TSh 500,000.',
        'vehicleClass.required' => 'Please select a vehicle class.',
        'typeOfCover.required' => 'Please select a type of cover.',
        'claimStatus.required' => 'Please select your claim status.',
        'year.required' => 'Please select the vehicle year.',
        'startDate.required' => 'Please select a start date for the policy.',
        'noPassengers.required' => 'Please enter the number of passengers.',
    ];

    public function mount($vehicleId = null)
    {
        // If vehicle ID is provided, load vehicle and pre-fill form
        if ($vehicleId) {
            $this->vehicleId = $vehicleId;
            $this->vehicle = Vehicle::with(['make', 'model'])->find($vehicleId);
            
            if ($this->vehicle) {
                // Pre-fill with vehicle data
                $this->insurableValue = $this->vehicle->price ?? 2500000;
                $this->year = $this->vehicle->year ?? date('Y');
                $this->startDate = date('Y-m-d');
                
                // Determine vehicle class based on body type or default to Private Cars
                if ($this->vehicle->body_type) {
                    $bodyType = strtolower($this->vehicle->body_type);
                    if (str_contains($bodyType, 'motorcycle') || str_contains($bodyType, 'bike')) {
                        $this->vehicleClass = 'Motorcycle Wheelers 2';
                        $this->noPassengers = 1;
                    } elseif (str_contains($bodyType, 'commercial') || str_contains($bodyType, 'van') || str_contains($bodyType, 'truck')) {
                        $this->vehicleClass = 'Commercial Vehicles';
                        $this->noPassengers = 4;
                    } else {
                        $this->vehicleClass = 'Private Cars';
                        $this->noPassengers = $this->vehicle->seats ?? 4;
                    }
                } else {
                    $this->vehicleClass = 'Private Cars';
                    $this->noPassengers = $this->vehicle->seats ?? 4;
                }
                
                $this->claimStatus = 'Free Vehicle';
                $this->carryingPassengers = 'No';
            } else {
                // Vehicle not found, use defaults
                $this->setDefaults();
            }
        } else {
            // No vehicle ID, use defaults
            $this->setDefaults();
        }
    }
    
    private function setDefaults()
    {
        $this->insurableValue = 2500000;
        $this->year = date('Y');
        $this->startDate = date('Y-m-d');
        $this->noPassengers = 4;
        $this->claimStatus = 'Free Vehicle';
        $this->carryingPassengers = 'No';
    }

    public function updatedVehicleClass()
    {
        $this->typeOfCover = '';
        $this->calculationResults = null;
        $this->showResults = false;
        
        // Reset passenger settings for non-motorcycle vehicles
        if (!in_array($this->vehicleClass, ['Motorcycle Wheelers 2', 'Motorcycle Wheelers 3'])) {
            $this->carryingPassengers = 'No';
            $this->noPassengers = 4;
        } else {
            $this->noPassengers = 1;
        }
    }

    public function updatedClaimStatus()
    {
        $this->typeOfCover = '';
        $this->calculationResults = null;
        $this->showResults = false;
    }

    public function updatedCarryingPassengers()
    {
        if ($this->carryingPassengers === 'No') {
            if (in_array($this->vehicleClass, ['Motorcycle Wheelers 2', 'Motorcycle Wheelers 3'])) {
                $this->noPassengers = 1;
            } else {
                $this->noPassengers = 1;
            }
        } else {
            if ($this->vehicleClass === 'Motorcycle Wheelers 2') {
                $this->noPassengers = 2;
            } elseif ($this->vehicleClass === 'Motorcycle Wheelers 3') {
                $this->noPassengers = 3;
            } else {
                $this->noPassengers = 4;
            }
        }
    }

    public function getAvailableCoverageOptions()
    {
        if (!$this->vehicleClass || !$this->claimStatus || 
            !isset($this->coverageOptions[$this->vehicleClass][$this->claimStatus])) {
            return [];
        }
        
        return $this->coverageOptions[$this->vehicleClass][$this->claimStatus];
    }

    public function calculateInsurance()
    {
        $this->validate();

        try {
            $this->calculationResults = $this->performCalculation();
            $this->showResults = true;
            
            session()->flash('message', 'Insurance premium calculated successfully!');

        } catch (\Exception $e) {
            Log::error('Insurance calculation error: ' . $e->getMessage());
            session()->flash('error', 'Error calculating insurance. Please check your inputs and try again.');
        }
    }

    private function performCalculation()
    {
        $insurableValue = (float) $this->insurableValue;
        $coverageData = $this->coverageOptions[$this->vehicleClass][$this->claimStatus][$this->typeOfCover];
        
        // Step 1: Calculate premium using rate (if any)
        $calculatedPremium = 0;
        if ($coverageData['rate'] > 0) {
            $calculatedPremium = $insurableValue * $coverageData['rate'];
        }
        
        // Step 2: Apply minimum premium
        $minPremium = $coverageData['min_premium'] ?? 0;
        $basePremium = max($calculatedPremium, $minPremium);
        
        // Step 3: Plus TPP (Third Party Property)
        $plusTPP = $coverageData['tpp'] ?? 0;
        
        // Step 4: Additional charges (for motorcycles with passengers)
        $additionalCharge = 0;
        if (isset($coverageData['additional']) && $this->carryingPassengers === 'Yes') {
            $additionalCharge = $coverageData['additional'];
        }
        
        // Step 5: Premium Excl. VAT
        $premiumExclVat = $basePremium + $plusTPP + $additionalCharge;
        
        // Step 6: VAT calculation (18%)
        $vatRate = 0.18;
        $vatAmount = $premiumExclVat * $vatRate;
        
        // Step 7: Total Premium Inc. VAT
        $totalPremium = $premiumExclVat + $vatAmount;
        
        // Step 8: Estimated Commission (12.5% of premium excl. VAT)
        $estimatedCommission = $premiumExclVat * 0.125;
        
        return [
            'insurable_value' => $insurableValue,
            'vehicle_class' => $this->vehicleClass,
            'carrying_passengers' => $this->carryingPassengers,
            'no_passengers' => $this->noPassengers,
            'type_of_cover' => $this->typeOfCover,
            'claim_status' => $this->claimStatus,
            'year' => $this->year,
            'start_date' => $this->startDate,
            'coverage_type' => $coverageData['type'],
            'premium_rate' => $coverageData['rate'],
            'calculated_premium' => round($calculatedPremium, 2),
            'minimum_premium' => round($minPremium, 2),
            'base_premium' => round($basePremium, 2),
            'plus_tpp' => round($plusTPP, 2),
            'additional_charge' => round($additionalCharge, 2),
            'premium_excl_vat' => round($premiumExclVat, 2),
            'vat_rate' => $vatRate,
            'vat_amount' => round($vatAmount, 2),
            'total_premium' => round($totalPremium, 2),
            'estimated_commission' => round($estimatedCommission, 2),
            'monthly_premium' => round($totalPremium / 12, 2),
            'excess_info' => $this->getExcessInfo($coverageData['type'], $insurableValue),
            'calculation_date' => now()->format('Y-m-d H:i:s')
        ];
    }

    private function getExcessInfo($coverageType, $insurableValue)
    {
        switch ($coverageType) {
            case 'CC': // Comprehensive Cover
                if ($this->vehicleClass === 'Private Cars') {
                    $excessAmount = max(350000, $insurableValue * 0.05);
                    $claimSettlement = $insurableValue - $excessAmount;
                    return "For this Cover Upon Claim logging, Customer required to Pay an Excess 5% of claim, minimum TZS 350,000 but double in case of total theft claim. This means that if Claim amount equivalent to TZS " . number_format($insurableValue) . ", Therefore, Customer will pay an excess equivalent to TZS " . number_format($excessAmount) . ". Subsequently, the Insurer (Jubilee) will pay approximately around TZS " . number_format($claimSettlement) . " as final claim settlement after less other mandatory and statutory fees/charges.";
                } elseif (in_array($this->vehicleClass, ['Motorcycle Wheelers 2', 'Motorcycle Wheelers 3'])) {
                    $excessAmount = max(100000, $insurableValue * 0.05);
                    return "5% of claim, minimum TZS 100,000 but double in case of total theft claim. For your vehicle value of TZS " . number_format($insurableValue) . ", excess amount will be TZS " . number_format($excessAmount) . ".";
                } elseif ($this->vehicleClass === 'Commercial Vehicles') {
                    $excessAmount = max(500000, $insurableValue * 0.075);
                    $theftExcess = max(750000, $insurableValue * 0.10);
                    return "7.5% of claim, minimum TZS 500,000 but 10% of claim min. TZS 750,000 in case of total theft claim. Normal excess: TZS " . number_format($excessAmount) . ", Theft excess: TZS " . number_format($theftExcess) . ".";
                } elseif ($this->vehicleClass === 'Special Type of Vehicle') {
                    $excessAmount = max(1000000, $insurableValue * 0.10);
                    return "10% of the claim amount Min. TZS 1,000,000. For your vehicle, excess will be TZS " . number_format($excessAmount) . ".";
                }
                break;
                
            case 'TPFT': // Third Party Fire & Theft
                return "NIL for Third Party Claims. No excess payable for third party property damage or injury claims.";
                
            case 'TPO': // Third Party Only
                return "NIL for Third Party Claims. No excess payable as this covers only third party liability.";
        }
        
        return "Please contact our office for detailed excess information specific to your coverage.";
    }

    public function resetCalculator()
    {
        $this->reset([
            'insurableValue', 'vehicleClass', 'typeOfCover', 'claimStatus',
            'carryingPassengers', 'noPassengers', 'year', 'startDate',
            'calculationResults', 'showResults'
        ]);
        
        $this->mount();
        session()->flash('message', 'Calculator has been reset to default values.');
    }

    public function render()
    {

        return view('livewire.customer.vehicle-insurance-calculator');
    }
}

