<?php

namespace App\Livewire\Admin\VehicleRegistration;

use App\Models\Country;
use App\Models\Entity;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Services\ImageCompressionService;
use App\Support\VehicleSpecificationCatalog;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class VehicleForm extends Component
{
    use WithFileUploads;

    // Basic Information
    public $description;

    public $origin = 'local';

    /** City name (user input). Local = Tanzania; international = city in selected country. */
    public $location_city = '';

    /** Set when origin is international; chosen from seeded countries. */
    public $country_id = null;

    /** Shown after a country is chosen (label only). */
    public $countrySearch = '';

    /** Separate from label — only what the user types to filter the list (avoids wire/model conflicts). */
    public $countryQuery = '';

    /** @var array<int, array{id:int, name:string, code:string}> */
    public array $countryMatchResults = [];

    public $registration_number;

    public $condition = 'used';

    // Make and Model
    public $vehicle_make_id;

    public $vehicle_model_id;

    public $variant;

    public $year;

    // Specifications
    public $body_type;

    public $fuel_type;

    public $transmission;

    public $engine_capacity;

    public $engine_cc;

    public $drive_type;

    public $color_exterior;

    public $color_interior;

    public $doors;

    public $seats;

    public $mileage;

    public $vin;

    // Pricing
    public $price;

    public $currency = 'TZS';

    public $original_price;

    public $negotiable = true;

    // Features
    public $features = [];

    public $safety_features = [];

    // Images
    public $image_front;

    public $image_side;

    public $image_back;

    public $other_images = [];

    public $new_other_images = [];

    /** Stored paths when editing (for preview + gallery merge on save). */
    public ?string $existingImageFront = null;

    public ?string $existingImageSide = null;

    public ?string $existingImageBack = null;

    /** @var list<string> */
    public array $existingOtherImages = [];

    // Ownership
    public $entity_id;

    // Status
    public $status = 'pending';

    public $notes;

    // Edit mode
    public $vehicleId;

    public $editMode = false;

    // Data for dropdowns
    public $makes = [];

    public $models = [];

    public $dealers = [];

    // Temporary images for preview
    public $tempImageFront;

    public $tempImageSide;

    public $tempImageBack;

    public $tempOtherImages = [];

    public bool $showErrorModal = false;

    public string $errorModalTitle = 'Something went wrong';

    /** @var list<string> */
    public array $errorModalMessages = [];

    /**
     * Get the validation rules
     */
    protected function rules()
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;

        $rules = [
            'description' => 'nullable|string',
            'origin' => 'required|in:local,international',
            'location_city' => 'required|string|max:255',
            'country_id' => [
                Rule::requiredIf($this->origin === 'international'),
                'nullable',
                'exists:countries,id',
            ],
            'registration_number' => 'nullable|string|max:255',
            'condition' => 'required|in:new,used,certified_pre_owned',
            'vehicle_make_id' => 'required|exists:vehicle_makes,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'variant' => 'nullable|string|max:255',
            'year' => 'required|integer|min:1900|max:'.(date('Y') + 2),
            'body_type' => 'nullable|string|max:255',
            'fuel_type' => 'nullable|string|max:255',
            'transmission' => 'nullable|string|max:255',
            'engine_capacity' => 'nullable|string|max:255',
            'engine_cc' => 'nullable|integer',
            'drive_type' => 'nullable|string|max:255',
            'color_exterior' => 'nullable|string|max:255',
            'color_interior' => 'nullable|string|max:255',
            'doors' => 'nullable|integer|min:2|max:6',
            'seats' => 'nullable|integer|min:1|max:50',
            'mileage' => 'nullable|integer|min:0',
            'vin' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'original_price' => 'nullable|numeric|min:0',
            'negotiable' => 'boolean',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'image_front' => 'nullable|image|max:5120',
            'image_side' => 'nullable|image|max:5120',
            'image_back' => 'nullable|image|max:5120',
            'other_images.*' => 'nullable|image|max:5120',
            'new_other_images.*' => 'nullable|image|max:5120',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
            'safety_features' => 'nullable|array',
            'safety_features.*' => 'nullable|string|max:255',
        ];

        // Entity validation: required for non-admin users, optional for admin
        if ($userRole === 'admin') {
            $rules['entity_id'] = 'nullable|exists:entities,id';
        } else {
            $rules['entity_id'] = 'required|exists:entities,id';
        }

        return $rules;
    }

    public function mount(?string $vehiclePublicId = null)
    {
        $this->loadMakes();
        $this->loadDealers();

        if ($vehiclePublicId) {
            $vehicle = Vehicle::where('public_id', $vehiclePublicId)->firstOrFail();
            $this->editMode = true;
            $this->vehicleId = $vehicle->id;
            $this->loadVehicle();
        } else {
            $this->existingImageFront = null;
            $this->existingImageSide = null;
            $this->existingImageBack = null;
            $this->existingOtherImages = [];
            $this->year = date('Y');
            // Set default entity for non-admin users
            $user = Auth::user();
            if ($user->role !== 'admin') {
                // For non-admin users, always set entity_id from user
                if ($user->entity_id) {
                    $this->entity_id = $user->entity_id;
                } else {
                    // If no entity_id, set to null (validation will prevent saving)
                    $this->entity_id = null;
                }
            }
        }
    }

    public function loadVehicle()
    {
        $vehicle = Vehicle::findOrFail($this->vehicleId);

        $this->description = $vehicle->description;
        $this->origin = $vehicle->origin;
        $this->location_city = $vehicle->location_city ?? '';
        $this->country_id = $vehicle->country_id;
        $vehicle->loadMissing('country');
        $this->countrySearch = $vehicle->country?->name ?? '';
        $this->countryQuery = '';
        $this->countryMatchResults = [];
        if ($this->origin === 'international' && ! $this->country_id) {
            $this->loadCountryMatches();
        }
        $this->registration_number = $vehicle->registration_number ?? '';
        $this->condition = $vehicle->condition;
        $this->vehicle_make_id = $vehicle->vehicle_make_id;
        $this->vehicle_model_id = $vehicle->vehicle_model_id;
        $this->variant = $vehicle->variant ?? '';
        $this->year = $vehicle->year;
        $this->body_type = $vehicle->body_type;
        $this->fuel_type = $vehicle->fuel_type;
        $this->transmission = $vehicle->transmission;
        $this->engine_capacity = $vehicle->engine_capacity;
        $this->drive_type = $vehicle->drive_type;
        $this->color_exterior = $vehicle->color_exterior;
        $this->color_interior = $vehicle->color_interior;
        $this->doors = $vehicle->doors ?? '';
        $this->seats = $vehicle->seats ?? '';
        $this->mileage = $vehicle->mileage ?? '';
        $this->engine_cc = $vehicle->engine_cc ?? '';
        $this->vin = $vehicle->vin;
        $this->price = $vehicle->price;
        $this->currency = $vehicle->currency;
        $this->original_price = $vehicle->original_price !== null ? (string) $vehicle->original_price : '';
        $this->negotiable = $vehicle->negotiable;
        $this->features = array_values($vehicle->features ?? []);
        $this->safety_features = array_values($vehicle->safety_features ?? []);
        $this->entity_id = $vehicle->entity_id;
        $this->status = $vehicle->status->value;
        $this->notes = $vehicle->notes;

        $this->existingImageFront = $vehicle->image_front;
        $this->existingImageSide = $vehicle->image_side;
        $this->existingImageBack = $vehicle->image_back;
        $this->existingOtherImages = array_values($vehicle->other_images ?? []);

        $this->loadModels();
    }

    public function loadMakes()
    {
        $this->makes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    public function loadModels()
    {
        if ($this->vehicle_make_id) {
            $this->models = VehicleModel::where('vehicle_make_id', $this->vehicle_make_id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        } else {
            $this->models = [];
            $this->vehicle_model_id = null;
        }
    }

    public function loadDealers()
    {
        $this->dealers = Entity::where('type', 'dealer')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();
    }

    public function updatedVehicleMakeId()
    {
        $this->loadModels();
    }

    public function updatedOrigin(mixed $value): void
    {
        if ($value === 'local') {
            $this->country_id = null;
            $this->countrySearch = '';
            $this->countryQuery = '';
            $this->countryMatchResults = [];
        } else {
            $this->loadCountryMatches();
        }
    }

    public function updatedCountryQuery(): void
    {
        $this->loadCountryMatches();
    }

    public function selectCountry(int $id): void
    {
        $country = Country::query()->find($id);
        if ($country) {
            $this->country_id = $country->id;
            $this->countrySearch = $country->name;
            $this->countryQuery = '';
            $this->countryMatchResults = [];
        }
    }

    public function clearCountry(): void
    {
        $this->country_id = null;
        $this->countrySearch = '';
        $this->countryQuery = '';
        $this->loadCountryMatches();
    }

    /**
     * Populate {@see $countryMatchResults} from DB (pure Livewire state — no computed magic).
     */
    protected function loadCountryMatches(): void
    {
        if ($this->origin !== 'international' || $this->country_id) {
            $this->countryMatchResults = [];

            return;
        }

        $q = trim($this->countryQuery ?? '');
        $query = Country::query();

        if ($q === '') {
            $countries = (clone $query)->orderBy('name')->limit(50)->get();
        } else {
            $escaped = addcslashes($q, '%_\\');
            $like = '%'.$escaped.'%';
            $prefixCode = strtoupper(substr($q, 0, 2));

            $countries = $query
                ->where(function ($w) use ($like, $prefixCode, $q) {
                    $w->where('name', 'like', $like);
                    if (strlen($q) >= 1) {
                        $w->orWhere('code', 'like', $prefixCode.'%');
                    }
                })
                ->orderBy('name')
                ->limit(80)
                ->get();
        }

        $this->countryMatchResults = $countries
            ->map(fn (Country $c) => [
                'id' => $c->id,
                'name' => $c->name,
                'code' => $c->code,
            ])
            ->values()
            ->all();
    }

    public function updatedNewOtherImages()
    {
        if (! empty($this->new_other_images)) {
            // Merge new images with existing ones
            $this->other_images = array_merge($this->other_images, $this->new_other_images);
            // Clear the new images input
            $this->new_other_images = [];
        }
    }

    public function removeOtherImage($index)
    {
        if (isset($this->other_images[$index])) {
            unset($this->other_images[$index]);
            $this->other_images = array_values($this->other_images); // Re-index array
        }
    }

    public function removeExistingOtherImage(int $index): void
    {
        if (isset($this->existingOtherImages[$index])) {
            unset($this->existingOtherImages[$index]);
            $this->existingOtherImages = array_values($this->existingOtherImages);
        }
    }

    public function closeErrorModal(): void
    {
        $this->showErrorModal = false;
        $this->errorModalMessages = [];
    }

    /**
     * @param  list<string|\Stringable>  $messages
     */
    protected function openErrorModal(string $title, array $messages): void
    {
        $this->errorModalTitle = $title;
        $this->errorModalMessages = array_values(array_filter(array_map(
            static fn ($m) => trim((string) $m),
            $messages
        ), static fn (string $m) => $m !== ''));
        $this->showErrorModal = true;
    }

    protected function nullableDecimal(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? (string) $value : null;
    }

    protected function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? (int) $value : null;
    }

    public function save()
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;

        // For non-admin users, ensure entity_id is set from user
        if ($userRole !== 'admin') {
            if (! $user->entity_id) {
                $this->openErrorModal('Cannot register vehicle', [
                    'You cannot register a vehicle without an associated entity. Please contact an administrator.',
                ]);

                return;
            }
            // Force entity_id to user's entity_id (prevent tampering)
            $this->entity_id = $user->entity_id;
        }

        $entity = $this->entity_id ? Entity::with('pricingPlan')->find($this->entity_id) : null;
        if ($entity && ! $this->editMode) {
            if (! $entity->canAddVehicle()) {
                $max = $entity->max_allowed_cars;
                $current = $entity->vehiclesCountExcludingSold();
                $this->openErrorModal('Listing limit reached', [
                    "Your package allows up to {$max} car listing(s) (excluding sold). You currently have {$current}. Please upgrade your plan to add more.",
                ]);

                return;
            }
        }

        try {
            $this->validate();
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $this->openErrorModal('Please fix the following', $e->validator->errors()->all());

            return;
        }

        // Auto-generate title from make, model, and year
        $make = VehicleMake::find($this->vehicle_make_id);
        $model = VehicleModel::find($this->vehicle_model_id);
        $title = ($make ? $make->name : '').' '.($model ? $model->name : '').' '.$this->year;
        $title = trim($title);
        if (empty($title)) {
            $title = 'Vehicle '.date('Y');
        }

        $comfortCatalog = VehicleSpecificationCatalog::comfort();
        $safetyCatalog = VehicleSpecificationCatalog::safety();

        $data = [
            'title' => $title,
            'description' => $this->description,
            'origin' => $this->origin,
            'country_id' => $this->origin === 'international' ? $this->country_id : null,
            'location_city' => $this->location_city,
            'registration_number' => filled($this->registration_number) ? $this->registration_number : null,
            'condition' => $this->condition,
            'vehicle_make_id' => $this->vehicle_make_id,
            'vehicle_model_id' => $this->vehicle_model_id,
            'variant' => $this->variant,
            'year' => $this->year,
            'body_type' => $this->body_type,
            'fuel_type' => $this->fuel_type,
            'transmission' => $this->transmission,
            'engine_capacity' => $this->engine_capacity,
            'drive_type' => $this->drive_type,
            'color_exterior' => $this->color_exterior,
            'color_interior' => $this->color_interior,
            'doors' => $this->nullableInt($this->doors),
            'seats' => $this->nullableInt($this->seats),
            'mileage' => $this->nullableInt($this->mileage),
            'engine_cc' => $this->nullableInt($this->engine_cc),
            'vin' => $this->vin,
            'price' => $this->price,
            'currency' => $this->currency,
            'original_price' => $this->nullableDecimal($this->original_price),
            'negotiable' => $this->negotiable,
            'features' => array_values(array_unique(array_merge(
                VehicleSpecificationCatalog::filterToCatalog($this->features, $comfortCatalog),
                VehicleSpecificationCatalog::extrasNotInCatalog($this->features, $comfortCatalog),
            ))),
            'safety_features' => array_values(array_unique(array_merge(
                VehicleSpecificationCatalog::filterToCatalog($this->safety_features, $safetyCatalog),
                VehicleSpecificationCatalog::extrasNotInCatalog($this->safety_features, $safetyCatalog),
            ))),
            'entity_id' => filled($this->entity_id) ? $this->entity_id : null,
            'status' => $this->status,
            'notes' => $this->notes,
        ];

        $compress = app(ImageCompressionService::class);

        // Handle image uploads
        if ($this->image_front) {
            $data['image_front'] = $compress->storeCompressed($this->image_front, 'vehicles', 1200);
        }

        if ($this->image_side) {
            $data['image_side'] = $compress->storeCompressed($this->image_side, 'vehicles', 1200);
        }

        if ($this->image_back) {
            $data['image_back'] = $compress->storeCompressed($this->image_back, 'vehicles', 1200);
        }

        $finalOtherImages = $this->editMode ? array_values($this->existingOtherImages) : [];
        if (! empty($this->other_images)) {
            foreach ($this->other_images as $image) {
                if ($image) {
                    $finalOtherImages[] = $compress->storeCompressed($image, 'vehicles', 1200);
                }
            }
        }
        if ($this->editMode) {
            $data['other_images'] = count($finalOtherImages) > 0 ? $finalOtherImages : null;
        } elseif (count($finalOtherImages) > 0) {
            $data['other_images'] = $finalOtherImages;
        }

        try {
            if ($this->editMode) {
                $vehicle = Vehicle::findOrFail($this->vehicleId);
                $vehicle->update($data);

                session()->flash('success', 'Vehicle updated successfully!');
            } else {
                $data['registered_by'] = Auth::id();
                Vehicle::create($data);

                session()->flash('success', 'Vehicle registered successfully!');
            }
        } catch (QueryException $e) {
            Log::warning('Vehicle save failed', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
            $this->openErrorModal('Could not save vehicle', [
                config('app.debug')
                    ? $e->getMessage()
                    : 'A database error occurred while saving. Check required fields (e.g. price, optional numbers left blank) and try again.',
            ]);

            return;
        }

        return redirect()->route('admin.vehicles.registration.index');
    }

    public function render()
    {
        return view('livewire.admin.vehicle-registration.vehicle-form');
    }
}
