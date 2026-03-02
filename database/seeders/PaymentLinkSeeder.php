<?php

namespace Database\Seeders;

use App\Models\PaymentLink;
use Illuminate\Database\Seeder;

class PaymentLinkSeeder extends Seeder
{
    /**
     * Seed one sample payment link (Saccos-style) so you can view the admin list and detail.
     */
    public function run(): void
    {
        $link = PaymentLink::create([
            'link_id' => 'LINK_eSnD5wUvLoVufxpD',
            'short_code' => '2Xf6dLQU',
            'payment_url' => 'http://172.240.241.188/pay/2Xf6dLQU',
            'qr_code_data' => 'http://172.240.241.188/pay/2Xf6dLQU',
            'target_type' => 'individual',
            'is_public' => false,
            'total_amount' => 700000,
            'currency' => 'TZS',
            'description' => 'Saccos services',
            'customer_reference' => 'MEMBER2001',
            'customer_name' => 'Simon Mpembee',
            'customer_phone' => '255742099713',
            'customer_email' => 'mpembeesimon@email.com',
            'expires_at' => now()->endOfYear(),
            'max_uses' => null,
            'is_reusable' => false,
            'allowed_networks' => ['TZ-AIRTEL-C2B', 'TZ-TIGO-C2B', 'TZ-MPESA-C2B', 'TZ-HALOPESA-C2B'],
            'api_request_id' => 'req_69a58a02d5a13',
            'api_response_at' => now()->toIso8601String(),
        ]);

        $link->items()->createMany([
            [
                'item_code' => 'ITEM_NtUaHKKBLXRZ',
                'type' => 'service',
                'product_service_reference' => 'SHARES_01',
                'product_service_name' => 'MANDATORY SHARES',
                'description' => null,
                'amount' => 200000,
                'minimum_amount' => null,
                'is_required' => true,
                'allow_partial' => false,
                'payment_status' => 'unpaid',
                'paid_amount' => 0,
            ],
            [
                'item_code' => 'ITEM_Vvu0CImGATZj',
                'type' => 'service',
                'product_service_reference' => 'DEPOSITS_07',
                'product_service_name' => 'DEPOSITS',
                'description' => null,
                'amount' => 500000,
                'minimum_amount' => null,
                'is_required' => true,
                'allow_partial' => true,
                'payment_status' => 'partial',
                'paid_amount' => 150000,
            ],
        ]);

        $this->command->info('Created sample payment link: ' . $link->short_code . ' (ID: ' . $link->id . ')');
        $this->command->info('View at: /admin/payment-links and /admin/payment-links/' . $link->id);
    }
}
