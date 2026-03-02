<?php

namespace App\Livewire\Admin;

use App\Models\PaymentLink;
use App\Services\UniversalPaymentLinkService;
use Livewire\Component;

class PaymentLinkGenerate extends Component
{
    public string $description = 'Saccos services';
    public string $target = 'individual';
    public string $customer_reference = '';
    public string $customer_name = '';
    public string $customer_phone = '';
    public string $customer_email = '';
    public string $expires_at = '';

    /** @var array<int, array{ref: string, name: string, amount: string, allow_partial: bool}> */
    public array $items = [];

    public $message = null;
    public bool $success = false;

    public function mount(): void
    {
        if ($this->expires_at === '') {
            $this->expires_at = now()->endOfYear()->format('Y-m-d\T12:00:00');
        }
        if (empty($this->items)) {
            $this->items = [
                ['ref' => 'SHARES_01', 'name' => 'MANDATORY SHARES', 'amount' => '200000', 'allow_partial' => false],
                ['ref' => 'DEPOSITS_07', 'name' => 'DEPOSITS', 'amount' => '500000', 'allow_partial' => true],
            ];
        }
    }

    public function addItem(): void
    {
        $this->items[] = ['ref' => '', 'name' => '', 'amount' => '0', 'allow_partial' => false];
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) <= 1) {
            return;
        }
        array_splice($this->items, $index, 1);
    }

    protected function normalizeExpiresAt(): string
    {
        $expiresAt = $this->expires_at;
        if (strlen($expiresAt) <= 16 && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $expiresAt)) {
            $expiresAt .= ':00Z';
        } elseif (strpos($expiresAt, 'Z') === false && strpos($expiresAt, '+') === false) {
            $expiresAt = rtrim($expiresAt, ':') . 'Z';
        }
        return $expiresAt;
    }

    protected function getPayload(): array
    {
        $apiItems = [];
        foreach ($this->items as $item) {
            $apiItems[] = [
                'type' => 'service',
                'product_service_reference' => $item['ref'] ?? '',
                'product_service_name' => $item['name'] ?? '',
                'amount' => (int) ($item['amount'] ?? 0),
                'is_required' => true,
                'allow_partial' => (bool) ($item['allow_partial'] ?? false),
            ];
        }
        return [
            'description' => $this->description,
            'target' => $this->target,
            'customer_reference' => $this->customer_reference,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'expires_at' => $this->normalizeExpiresAt(),
            'items' => $apiItems,
        ];
    }

    protected function validateForm(): void
    {
        $rules = [
            'description' => 'required|string|max:500',
            'target' => 'required|in:individual,business',
            'customer_reference' => 'required|string|max:100',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'customer_email' => 'required|email',
            'expires_at' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.ref' => 'required|string|max:100',
            'items.*.name' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
        ];
        $this->validate($rules, [], [
            'customer_reference' => 'customer reference',
            'customer_name' => 'customer name',
            'customer_phone' => 'customer phone',
            'customer_email' => 'customer email',
            'expires_at' => 'expires at',
        ]);
    }

    public function generateViaApi(UniversalPaymentLinkService $service): void
    {
        $this->message = null;
        $this->success = false;
        $this->validateForm();
        $payload = $this->getPayload();

        $result = $service->generateUniversalLink($payload, true);

        if ($result['success']) {
            $this->success = true;
            $data = $result['data'];
            $url = $data['payment_url'] ?? null;
            $this->message = $url
                ? 'Link generated. Payment URL: ' . $url . (isset($result['payment_link']) ? ' (saved as #' . $result['payment_link']->id . ')' : '')
                : 'Link generated successfully.' . (isset($result['payment_link']) ? ' View link #' . $result['payment_link']->id . '.' : '');
        } else {
            $this->message = $result['error'] ?? $result['message'] ?? 'Failed to generate payment link.';
        }
    }

    public function saveManually(): void
    {
        $this->message = null;
        $this->success = false;
        $this->validateForm();
        $payload = $this->getPayload();

        $total = 0;
        foreach ($this->items as $item) {
            $total += (int) ($item['amount'] ?? 0);
        }

        $link = PaymentLink::create([
            'link_id' => null,
            'short_code' => 'MANUAL-' . strtoupper(substr(uniqid(), -6)),
            'payment_url' => null,
            'qr_code_data' => null,
            'target_type' => $this->target,
            'is_public' => false,
            'total_amount' => $total,
            'currency' => 'TZS',
            'description' => $this->description,
            'customer_reference' => $this->customer_reference,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'expires_at' => $this->expires_at,
            'max_uses' => null,
            'is_reusable' => false,
            'allowed_networks' => [],
            'api_request_id' => null,
            'api_response_at' => null,
        ]);

        foreach ($this->items as $item) {
            $link->items()->create([
                'item_code' => 'ITEM_' . strtoupper(substr(uniqid(), -8)),
                'type' => 'service',
                'product_service_reference' => $item['ref'] ?? '',
                'product_service_name' => $item['name'] ?? '',
                'description' => null,
                'amount' => (float) ($item['amount'] ?? 0),
                'minimum_amount' => null,
                'is_required' => true,
                'allow_partial' => (bool) ($item['allow_partial'] ?? false),
                'payment_status' => 'unpaid',
                'paid_amount' => 0,
            ]);
        }

        $this->success = true;
        $this->message = 'Bill saved manually as link #' . $link->id . ' (short code: ' . $link->short_code . '). It will appear in Overview, Transactions, and Links.';
    }

    public function render()
    {
        return view('livewire.admin.payment-link-generate');
    }
}
