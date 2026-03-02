<?php

namespace App\Services;

use App\Models\PaymentLink;
use App\Models\PaymentLinkGenerationLog;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UniversalPaymentLinkService
{
    protected string $baseUrl;

    protected string $apiKey;

    protected string $apiSecret;

    protected string $generatePath;

    public function __construct()
    {
        $config = config('services.universal_payment_link');
        $this->baseUrl = rtrim($config['base_url'], '/');
        $this->apiKey = $config['api_key'] ?? '';
        $this->apiSecret = $config['api_secret'] ?? '';
        $this->generatePath = $config['generate_universal_path'] ?? '/api/payment-links/generate-universal';
    }

    /**
     * Generate a universal payment link via the external API.
     *
     * @param array $payload Must include: description, target, customer_reference, customer_name,
     *                       customer_phone, customer_email, expires_at, items (array of item objects)
     * @param bool $track If true, persist the created link and items for tracking (paid/partial/unpaid).
     * @return array{ success: bool, message?: string, data?: array, payment_link?: PaymentLink, error?: string }
     */
    public function generateUniversalLink(array $payload, bool $track = true): array
    {
        $url = $this->baseUrl . $this->generatePath;

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-API-Key' => $this->apiKey,
            'X-API-Secret' => $this->apiSecret,
        ];

        try {
            $response = Http::timeout(30)
                ->withHeaders($headers)
                ->post($url, $payload);

            $body = $response->json() ?? [];

            if ($response->failed()) {
                Log::warning('Universal payment link API error', [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $body,
                ]);
                $error = $body['message'] ?? $response->reason();
                $this->logGeneration(null, false, $payload, $body, null, $error);
                return [
                    'success' => false,
                    'error' => $error,
                    'data' => $body,
                ];
            }

            $isSuccess = ($body['status'] ?? '') === 'success' && isset($body['data']);
            if (!$isSuccess) {
                $message = $body['message'] ?? 'Unknown error';
                $this->logGeneration(null, false, $payload, $body, $body['request_id'] ?? null, $message);
                return [
                    'success' => false,
                    'message' => $message,
                    'data' => $body,
                ];
            }

            $data = $body['data'];
            $paymentLink = null;

            if ($track && !empty($data)) {
                $paymentLink = $this->persistPaymentLink($body);
            }

            $requestId = $body['request_id'] ?? null;
            $this->logGeneration($paymentLink?->id, true, $payload, $body, $requestId, null);

            return [
                'success' => true,
                'message' => $body['message'] ?? 'Universal payment link generated successfully',
                'data' => $data,
                'payment_link' => $paymentLink,
                'timestamp' => $body['timestamp'] ?? null,
                'request_id' => $requestId,
            ];
        } catch (RequestException $e) {
            Log::error('Universal payment link request failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
            $this->logGeneration(null, false, $payload, [], null, $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Persist API response to payment_links and payment_link_items for tracking.
     */
    protected function persistPaymentLink(array $body): PaymentLink
    {
        $data = $body['data'];
        $link = PaymentLink::create([
            'link_id' => $data['link_id'] ?? null,
            'short_code' => $data['short_code'] ?? null,
            'payment_url' => $data['payment_url'] ?? null,
            'qr_code_data' => $data['qr_code_data'] ?? null,
            'target_type' => $data['target_type'] ?? 'individual',
            'is_public' => $data['is_public'] ?? false,
            'total_amount' => (int) ($data['total_amount'] ?? 0),
            'currency' => $data['currency'] ?? 'TZS',
            'description' => $data['description'] ?? null,
            'customer_reference' => $data['customer_reference'] ?? null,
            'customer_name' => $data['customer_name'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
            'customer_email' => $data['customer_email'] ?? null,
            'expires_at' => isset($data['expires_at']) ? $data['expires_at'] : null,
            'max_uses' => $data['max_uses'] ?? null,
            'is_reusable' => $data['is_reusable'] ?? false,
            'allowed_networks' => $data['allowed_networks'] ?? [],
            'api_request_id' => $body['request_id'] ?? null,
            'api_response_at' => $body['timestamp'] ?? null,
        ]);

        foreach ($data['items'] ?? [] as $item) {
            $link->items()->create([
                'item_code' => $item['item_code'] ?? null,
                'type' => $item['type'] ?? 'service',
                'product_service_reference' => $item['product_service_reference'] ?? null,
                'product_service_name' => $item['product_service_name'] ?? null,
                'description' => $item['description'] ?? null,
                'amount' => (float) ($item['amount'] ?? 0),
                'minimum_amount' => isset($item['minimum_amount']) ? (float) $item['minimum_amount'] : null,
                'is_required' => $item['is_required'] ?? true,
                'allow_partial' => $item['allow_partial'] ?? false,
                'payment_status' => 'unpaid', // updated by webhook later
                'paid_amount' => 0,
            ]);
        }

        return $link->load('items');
    }

    /**
     * Find a PaymentLink by external link_id or short_code (for webhook handling).
     */
    public function findLinkByExternalId(string $linkIdOrShortCode): ?PaymentLink
    {
        return PaymentLink::where('link_id', $linkIdOrShortCode)
            ->orWhere('short_code', $linkIdOrShortCode)
            ->first();
    }

    protected function logGeneration(?int $paymentLinkId, bool $success, array $requestPayload, $responsePayload, ?string $requestId, ?string $errorMessage): void
    {
        PaymentLinkGenerationLog::create([
            'payment_link_id' => $paymentLinkId,
            'success' => $success,
            'error_message' => $errorMessage,
            'request_payload' => $requestPayload,
            'response_payload' => is_array($responsePayload) ? $responsePayload : null,
            'request_id' => $requestId,
        ]);
    }
}
