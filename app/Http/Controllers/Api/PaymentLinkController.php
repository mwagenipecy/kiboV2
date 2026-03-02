<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentLink;
use App\Services\UniversalPaymentLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentLinkController extends Controller
{
    public function __construct(
        protected UniversalPaymentLinkService $paymentLinkService
    ) {}

    /**
     * Generate a universal payment link (Saccos/bills).
     * POST body should match the external API payload.
     */
    public function generate(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'description' => 'required|string|max:500',
            'target' => 'required|string|in:individual,business',
            'customer_reference' => 'required|string|max:100',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'customer_email' => 'required|email',
            'expires_at' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|string|in:service,product',
            'items.*.product_service_reference' => 'required|string|max:100',
            'items.*.product_service_name' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.is_required' => 'sometimes|boolean',
            'items.*.allow_partial' => 'sometimes|boolean',
        ]);

        $result = $this->paymentLinkService->generateUniversalLink($payload, true);

        if (!$result['success']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'] ?? $result['error'] ?? 'Failed to generate payment link',
            ], 422);
        }

        $response = [
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data'],
            'timestamp' => $result['timestamp'] ?? null,
            'request_id' => $result['request_id'] ?? null,
        ];

        if (isset($result['payment_link'])) {
            $response['tracked'] = [
                'id' => $result['payment_link']->id,
                'link_id' => $result['payment_link']->link_id,
                'short_code' => $result['payment_link']->short_code,
                'items_count' => $result['payment_link']->items->count(),
            ];
        }

        return response()->json($response);
    }

    /**
     * List created bills (payment links) with overall and per-item status.
     */
    public function index(Request $request): JsonResponse
    {
        $query = PaymentLink::with('items')->latest();

        if ($request->has('customer_reference')) {
            $query->where('customer_reference', $request->customer_reference);
        }
        if ($request->has('status')) {
            $status = $request->status;
            if ($status === 'unpaid') {
                $query->unpaidItems();
            } elseif ($status === 'partial') {
                $query->withPartialItems();
            } elseif ($status === 'paid') {
                $query->fullyPaid();
            }
        }

        $links = $query->paginate($request->integer('per_page', 15));

        $data = $links->through(function (PaymentLink $link) {
            return [
                'id' => $link->id,
                'link_id' => $link->link_id,
                'short_code' => $link->short_code,
                'payment_url' => $link->payment_url,
                'description' => $link->description,
                'customer_reference' => $link->customer_reference,
                'customer_name' => $link->customer_name,
                'total_amount' => $link->total_amount,
                'currency' => $link->currency,
                'total_paid' => $link->total_paid_amount,
                'overall_status' => $link->overall_payment_status,
                'expires_at' => $link->expires_at?->toIso8601String(),
                'items' => $link->items->map(fn ($item) => [
                    'id' => $item->id,
                    'item_code' => $item->item_code,
                    'product_service_reference' => $item->product_service_reference,
                    'product_service_name' => $item->product_service_name,
                    'amount' => (float) $item->amount,
                    'paid_amount' => (float) $item->paid_amount,
                    'payment_status' => $item->payment_status,
                ]),
                'created_at' => $link->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $links->currentPage(),
                'last_page' => $links->lastPage(),
                'per_page' => $links->perPage(),
                'total' => $links->total(),
            ],
        ]);
    }

    /**
     * Show one bill with all items and payment status (paid/partial/unpaid).
     */
    public function show(int $id): JsonResponse
    {
        $link = PaymentLink::with('items')->findOrFail($id);

        return response()->json([
            'id' => $link->id,
            'link_id' => $link->link_id,
            'short_code' => $link->short_code,
            'payment_url' => $link->payment_url,
            'qr_code_data' => $link->qr_code_data,
            'description' => $link->description,
            'customer_reference' => $link->customer_reference,
            'customer_name' => $link->customer_name,
            'customer_phone' => $link->customer_phone,
            'customer_email' => $link->customer_email,
            'total_amount' => $link->total_amount,
            'currency' => $link->currency,
            'total_paid' => $link->total_paid_amount,
            'overall_status' => $link->overall_payment_status,
            'expires_at' => $link->expires_at?->toIso8601String(),
            'items' => $link->items->map(fn ($item) => [
                'id' => $item->id,
                'item_code' => $item->item_code,
                'type' => $item->type,
                'product_service_reference' => $item->product_service_reference,
                'product_service_name' => $item->product_service_name,
                'amount' => (float) $item->amount,
                'paid_amount' => (float) $item->paid_amount,
                'payment_status' => $item->payment_status,
                'is_required' => $item->is_required,
                'allow_partial' => $item->allow_partial,
            ]),
            'created_at' => $link->created_at->toIso8601String(),
        ]);
    }
}
