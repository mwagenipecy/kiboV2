<?php

namespace App\Livewire\Customer;

use App\Jobs\SendOtpSms;
use App\Jobs\SendSparePartOrderPlacedSms;
use App\Models\SparePartOrder;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Services\ImageCompressionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class SparePartSourcing extends Component
{
    use WithFileUploads;

    /**
     * Simple request model: we keep a single "order" with one item,
     * and allow the user to describe what they need in notes.
     */
    // NOTE: Livewire deep-binding into array-of-arrays can be fragile in some setups.
    // We keep part fields as parallel arrays to ensure reliable hydration.
    public array $orderItems = [];

    public array $orderItemIds = [];

    public array $partNames = [];

    public array $quantities = [];

    public array $partNumbers = [];

    public array $notes = [];

    public array $conditions = [];

    /**
     * Livewire file uploads behave best as top-level properties.
     * We keep per-item images here (indexed by the orderItems loop index).
     */
    public array $orderItemImages = [];

    /** Staging slot per line item: pick one file at a time, then append to orderItemImages (avoids broken nested + multiple). */
    public array $newPartImages = [];

    // Track which items are expanded (by index)
    public array $expandedItems = [];

    // Modal states
    public $showSuccessModal = false;

    public $showErrorModal = false;

    public $successMessage = '';

    public $errorMessage = '';

    public $createdOrderNumbers = [];

    /** @var list<array{number: string, url: string}> */
    public array $createdOrderTrackLinks = [];

    /** When true (spare-parts home only), guests may verify phone with OTP instead of logging in. */
    public bool $offerGuestPhoneOtp = false;

    // Guest one-time phone sign-in
    public bool $guestAccessVerified = false;

    public string $guestModalStep = 'choose';

    public string $guestOtpPhone = '';

    public string $guestOtpCode = '';

    // Customer information
    public $customerName = '';

    public $customerEmail = '';

    public $customerPhone = '';

    public $company = '';

    // Vehicle information (for single orders only)
    public $vehicleMakeId = '';

    public $vehicleModelId = '';

    public $vehicleYear = '';

    public $vehicleVin = '';

    public $preferredCondition = 'any'; // any|new|used

    // Delivery information
    public $deliveryAddress = '';

    public $deliveryCity = '';

    public $deliveryRegion = '';

    public $deliveryCountry = 'Tanzania';

    public $deliveryPostalCode = '';

    public array $tanzaniaRegions = [];

    public $vehicleMakes = [];

    public $vehicleModels = [];

    public $submitted = false;

    public $isLoggedIn = false;

    /** Optional images (global fallback; per-part images in orderItems.*.images) */
    public $images = [];

    public function addOrderItem()
    {
        $nextId = (int) (collect($this->orderItemIds)->max() ?? 0) + 1;
        $newIndex = count($this->orderItemIds);
        $this->orderItemIds[] = $nextId;
        $this->partNames[] = '';
        $this->quantities[] = 1;
        $this->partNumbers[] = '';
        $this->notes[] = '';
        $this->conditions[] = 'any';
        $this->orderItemImages[] = [];
        $this->expandedItems[] = $newIndex;
    }

    public function toggleItem($index)
    {
        if (in_array($index, $this->expandedItems)) {
            $this->expandedItems = array_values(array_filter($this->expandedItems, fn ($i) => $i !== $index));
        } else {
            $this->expandedItems[] = $index;
        }
    }

    public function removeOrderItem($index)
    {
        if (count($this->orderItemIds) <= 1) {
            return;
        }
        $i = (int) $index;
        foreach (['orderItemIds', 'partNames', 'quantities', 'partNumbers', 'notes', 'conditions', 'orderItemImages'] as $prop) {
            if (array_key_exists($i, $this->{$prop})) {
                unset($this->{$prop}[$i]);
                $this->{$prop} = array_values($this->{$prop});
            }
        }
        $this->newPartImages = [];
    }

    public function mount()
    {
        $this->isLoggedIn = Auth::check();

        if (Auth::check()) {
            session()->forget('spare_parts_guest_phone');
        }

        $this->guestAccessVerified = ! Auth::check() && session()->has('spare_parts_guest_phone');
        if ($this->guestAccessVerified) {
            $this->customerPhone = (string) session('spare_parts_guest_phone');
        }

        $this->tanzaniaRegions = [
            'Arusha',
            'Dar es Salaam',
            'Dodoma',
            'Geita',
            'Iringa',
            'Kagera',
            'Katavi',
            'Kigoma',
            'Kilimanjaro',
            'Lindi',
            'Manyara',
            'Mara',
            'Mbeya',
            'Morogoro',
            'Mtwara',
            'Mwanza',
            'Njombe',
            'Pemba North',
            'Pemba South',
            'Pwani (Coast)',
            'Rukwa',
            'Ruvuma',
            'Shinyanga',
            'Simiyu',
            'Singida',
            'Songwe',
            'Tabora',
            'Tanga',
            'Unguja North',
            'Unguja South',
            'Unguja West',
        ];

        $this->vehicleMakes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Initialize with one empty order item
        $this->orderItemIds = [1];
        $this->partNames = [''];
        $this->quantities = [1];
        $this->partNumbers = [''];
        $this->notes = [''];
        $this->conditions = ['any'];
        $this->orderItemImages = [[]];
        $this->expandedItems = [0];

        // Pre-fill user information if logged in
        if ($this->isLoggedIn) {
            $user = Auth::user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
        }
    }

    public function updated(string $fullPath, mixed $newValue): void
    {
        if (! str_starts_with($fullPath, 'newPartImages.')) {
            return;
        }

        $index = (int) Str::after($fullPath, 'newPartImages.');

        if (! $newValue) {
            return;
        }

        $propertyName = 'newPartImages.'.$index;

        try {
            $this->validateOnly($propertyName, [
                $propertyName => 'required|image|max:5120',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->newPartImages[$index] = null;

            throw $e;
        }

        if (! isset($this->orderItemImages[$index]) || ! is_array($this->orderItemImages[$index])) {
            $this->orderItemImages[$index] = [];
        }

        if (count($this->orderItemImages[$index]) >= 5) {
            $this->newPartImages[$index] = null;

            return;
        }

        $this->orderItemImages[$index][] = $newValue;
        $this->newPartImages[$index] = null;
    }

    public function removeOrderItemImage(int $itemIndex, int $imageIndex): void
    {
        if (! isset($this->orderItemImages[$itemIndex])) {
            return;
        }

        unset($this->orderItemImages[$itemIndex][$imageIndex]);
        $this->orderItemImages[$itemIndex] = array_values($this->orderItemImages[$itemIndex]);
    }

    public function updatedVehicleMakeId()
    {
        $this->vehicleModelId = '';
        if ($this->vehicleMakeId) {
            $this->vehicleModels = VehicleModel::where('vehicle_make_id', $this->vehicleMakeId)
                ->orderBy('name')
                ->get();
        } else {
            $this->vehicleModels = [];
        }
    }

    public function openAuthModal()
    {
        $this->dispatch('open-auth-modal');
    }

    public function startGuestPhoneFlow(): void
    {
        if (! $this->offerGuestPhoneOtp) {
            return;
        }
        $this->guestModalStep = 'phone';
        $this->resetValidation();
    }

    public function backToGuestChoose(): void
    {
        $this->guestModalStep = 'choose';
        $this->guestOtpCode = '';
        $this->resetValidation();
    }

    public function sendSparePartsGuestOtp(): void
    {
        if (! $this->offerGuestPhoneOtp) {
            return;
        }
        $this->validate([
            'guestOtpPhone' => ['required', 'regex:/^0\d{9}$/'],
        ], [
            'guestOtpPhone.regex' => 'Phone must start with 0 and be 10 digits (e.g. 0712345678).',
        ]);

        $normalized = $this->normalizeLocalPhone($this->guestOtpPhone);
        if (! $normalized) {
            $this->addError('guestOtpPhone', 'Invalid phone number.');

            return;
        }

        $sendKey = 'spare_parts_otp_sends:'.$normalized;
        $attempts = (int) Cache::get($sendKey, 0);
        if ($attempts >= 5) {
            $this->addError('guestOtpPhone', 'Too many code requests. Please try again in an hour.');

            return;
        }

        $otpCode = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        Cache::put($this->guestOtpCacheKey($normalized), $otpCode, now()->addMinutes(5));
        Cache::put($sendKey, $attempts + 1, now()->addHour());

        SendOtpSms::dispatchSync($normalized, $otpCode);

        $this->guestModalStep = 'otp';
        $this->guestOtpCode = '';
        $this->resetValidation();
    }

    public function verifySparePartsGuestOtp(): void
    {
        if (! $this->offerGuestPhoneOtp) {
            return;
        }
        $this->validate([
            'guestOtpCode' => ['required', 'digits:4'],
            'guestOtpPhone' => ['required', 'regex:/^0\d{9}$/'],
        ]);

        $normalized = $this->normalizeLocalPhone($this->guestOtpPhone);
        if (! $normalized) {
            $this->addError('guestOtpCode', 'Invalid phone.');

            return;
        }

        $verifyKey = 'spare_parts_otp_verify:'.$normalized;
        if ((int) Cache::get($verifyKey, 0) >= 20) {
            $this->addError('guestOtpCode', 'Too many attempts. Request a new code.');

            return;
        }
        Cache::put($verifyKey, (int) Cache::get($verifyKey, 0) + 1, now()->addHour());

        $expected = Cache::get($this->guestOtpCacheKey($normalized));
        if (! $expected || $expected !== $this->guestOtpCode) {
            $this->addError('guestOtpCode', 'That code is incorrect or expired.');

            return;
        }

        Cache::forget($this->guestOtpCacheKey($normalized));
        Cache::forget('spare_parts_otp_verify:'.$normalized);
        session()->put('spare_parts_guest_phone', $normalized);
        $this->guestAccessVerified = true;
        $this->customerPhone = $normalized;
        $this->guestModalStep = 'choose';
        $this->guestOtpCode = '';
    }

    protected function guestOtpCacheKey(string $normalizedLocalPhone): string
    {
        return 'spare_parts_guest_otp:'.hash('sha256', $normalizedLocalPhone);
    }

    protected function normalizeLocalPhone(?string $phone): ?string
    {
        if ($phone === null || $phone === '') {
            return null;
        }
        $digits = preg_replace('/\D/', '', $phone);

        return preg_match('/^0\d{9}$/', $digits) ? $digits : null;
    }

    public function canAccessSourcing(): bool
    {
        return Auth::check() || ($this->offerGuestPhoneOtp && $this->guestAccessVerified);
    }

    public function submitOrders()
    {
        if (! Auth::check()) {
            if (! $this->offerGuestPhoneOtp) {
                $this->errorMessage = 'Please sign in to submit a spare parts request.';
                $this->showErrorModal = true;

                return;
            }
            $sessionPhone = session('spare_parts_guest_phone');
            $normalizedFormPhone = $this->normalizeLocalPhone($this->customerPhone);
            if (! $sessionPhone || $normalizedFormPhone !== $sessionPhone) {
                $this->errorMessage = 'Please sign in with your account or verify your phone with a one-time code.';
                $this->showErrorModal = true;

                return;
            }
        } else {
            $user = Auth::user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
        }

        // Normalize into the structure used for validation + persistence
        $count = count($this->orderItemIds);
        $this->orderItems = [];
        for ($i = 0; $i < $count; $i++) {
            $this->orderItems[] = [
                'id' => $this->orderItemIds[$i] ?? null,
                'part_name' => trim((string) ($this->partNames[$i] ?? '')),
                'quantity' => (int) ($this->quantities[$i] ?? 1),
                'part_number' => trim((string) ($this->partNumbers[$i] ?? '')),
                'notes' => trim((string) ($this->notes[$i] ?? '')),
                'condition' => in_array($this->conditions[$i] ?? '', ['any', 'new', 'used'], true) ? $this->conditions[$i] : 'any',
                'images' => $this->orderItemImages[$i] ?? [],
            ];
        }

        // Validate: one vehicle, one or more parts
        $rules = [
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => ['required', 'regex:/^0\\d{9}$/'],
            'deliveryAddress' => 'required|string',
            'deliveryCity' => 'required|string|max:255',
            'orderItemIds' => 'required|array|min:1',
            'partNames' => 'required|array|min:1',
            'partNames.*' => 'required|string|max:255',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'partNumbers' => 'nullable|array',
            'partNumbers.*' => 'nullable|string|max:255',
            'notes' => 'required|array|min:1',
            'notes.*' => 'required|string|max:2000',
            'conditions' => 'required|array|min:1',
            'conditions.*' => 'required|in:any,new,used',
            'orderItemImages' => 'nullable|array',
            'orderItemImages.*' => 'nullable|array|max:5',
            'orderItemImages.*.*' => 'nullable|image|max:5120',
            'vehicleMakeId' => 'required|exists:vehicle_makes,id',
            'vehicleModelId' => 'required|exists:vehicle_models,id',
        ];

        $this->validate($rules, [
            'vehicleMakeId.required' => 'Please select a vehicle make.',
            'vehicleModelId.required' => 'Please select a vehicle model.',
            'partNames.*.required' => 'Part name is required.',
            'notes.*.required' => 'Please add details to help us source the part.',
            'customerPhone.regex' => 'Phone number must start with 0 and be 10 digits (e.g. 0712345678).',
        ]);

        $compress = app(ImageCompressionService::class);

        $createdOrders = [];
        foreach ($this->orderItems as $item) {
            $condition = ($item['condition'] ?? 'any') === 'any' ? 'new' : $item['condition'];
            $storedImages = [];
            if (! empty($item['images']) && is_array($item['images'])) {
                foreach ($item['images'] as $image) {
                    if ($image) {
                        $storedImages[] = $compress->storeCompressed($image, 'spare-part-orders', 1200);
                    }
                }
            }
            if (empty($storedImages) && is_array($this->images)) {
                foreach ($this->images as $image) {
                    if ($image) {
                        $storedImages[] = $compress->storeCompressed($image, 'spare-part-orders', 1200);
                    }
                }
            }

            $order = SparePartOrder::create([
                'order_number' => SparePartOrder::generateOrderNumber(),
                'order_channel' => Auth::check() ? 'portal' : 'guest_phone',
                'user_id' => Auth::id(),
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'vehicle_make_id' => $this->vehicleMakeId,
                'vehicle_model_id' => $this->vehicleModelId,
                'condition' => $condition,
                'part_name' => $item['part_name'],
                'description' => trim(
                    ($item['part_number'] ? ('Part number: '.$item['part_number']."\n") : '').
                    'Qty: '.($item['quantity'] ?? 1)."\n".
                    ($item['notes'] ?? '')
                ),
                'images' => $storedImages,
                'delivery_address' => $this->deliveryAddress,
                'delivery_city' => $this->deliveryCity,
                'delivery_region' => null,
                'delivery_country' => $this->deliveryCountry,
                'delivery_postal_code' => $this->deliveryPostalCode,
                'delivery_latitude' => null,
                'delivery_longitude' => null,
                'contact_name' => $this->customerName,
                'contact_phone' => $this->customerPhone,
                'contact_email' => $this->customerEmail,
                'status' => 'pending',
            ]);
            $createdOrders[] = $order;
        }

        $this->submitted = true;
        $this->createdOrderNumbers = array_column($createdOrders, 'order_number');
        $this->createdOrderTrackLinks = [];
        foreach ($createdOrders as $order) {
            $this->createdOrderTrackLinks[] = [
                'number' => $order->order_number,
                'url' => route('spare-parts.track', ['token' => $order->public_token], absolute: true),
            ];
            if (! Auth::check() && $this->offerGuestPhoneOtp) {
                SendSparePartOrderPlacedSms::dispatch($order->id);
            }
        }
        $this->successMessage = count($createdOrders) > 1
            ? 'Your spare part requests have been submitted successfully!'
            : 'Your spare part request has been submitted successfully!';
        if (! Auth::check() && $this->offerGuestPhoneOtp) {
            $this->successMessage .= ' We sent tracking link(s) to your phone by SMS.';
        }
        $this->showSuccessModal = true;
        $this->resetForm();
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->successMessage = '';
        $this->createdOrderNumbers = [];
        $this->createdOrderTrackLinks = [];
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function resetForm()
    {
        $this->orderItemIds = [1];
        $this->partNames = [''];
        $this->quantities = [1];
        $this->partNumbers = [''];
        $this->notes = [''];
        $this->conditions = ['any'];
        $this->orderItems = [];
        $this->expandedItems = [0];
        $this->reset([
            'vehicleMakeId',
            'vehicleModelId',
            'vehicleYear',
            'vehicleVin',
            'deliveryAddress',
            'deliveryCity',
            'deliveryPostalCode',
            'images',
            'orderItemImages',
            'newPartImages',
        ]);
        $this->orderItemImages = [[]];
        $this->newPartImages = [];
        $this->submitted = false;
        $this->vehicleModels = [];

        // Re-populate user info if logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
        } elseif ($this->guestAccessVerified && session()->has('spare_parts_guest_phone')) {
            $this->customerPhone = (string) session('spare_parts_guest_phone');
        }
    }

    public function render()
    {
        return view('livewire.customer.spare-part-sourcing');
    }
}
