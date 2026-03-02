<?php

namespace Database\Seeders;

use App\Models\PaymentLink;
use Illuminate\Database\Seeder;

class PaymentLinksTableSeeder extends Seeder
{
    protected static array $productRefs = [
        ['ref' => 'SHARES_01', 'name' => 'MANDATORY SHARES', 'allow_partial' => false],
        ['ref' => 'DEPOSITS_07', 'name' => 'DEPOSITS', 'allow_partial' => true],
        ['ref' => 'LOAN_01', 'name' => 'LOAN REPAYMENT', 'allow_partial' => true],
        ['ref' => 'FEE_01', 'name' => 'MEMBERSHIP FEE', 'allow_partial' => false],
        ['ref' => 'SAVINGS_01', 'name' => 'VOLUNTARY SAVINGS', 'allow_partial' => true],
        ['ref' => 'INSURANCE_01', 'name' => 'INSURANCE PREMIUM', 'allow_partial' => false],
    ];

    protected static array $firstNames = [
        'Simon', 'Jane', 'John', 'Mary', 'Joseph', 'Grace', 'Peter', 'Anna', 'David', 'Sarah',
        'James', 'Elizabeth', 'Michael', 'Ruth', 'Charles', 'Helen', 'Daniel', 'Fatuma', 'Paul', 'Amina',
        'Emmanuel', 'Zainab', 'George', 'Mariam', 'Thomas', 'Neema', 'Robert', 'Asha', 'William', 'Halima',
    ];

    protected static array $lastNames = [
        'Mpembee', 'Doe', 'Kamau', 'Hassan', 'Moshi', 'Kimaro', 'Mollel', 'Juma', 'Swai', 'Mbwana',
        'Ngowi', 'Kato', 'Okello', 'Odhiambo', 'Mutua', 'Wanjiku', 'Ochieng', 'Akinyi', 'Kipchoge', 'Chebet',
    ];

    /**
     * Seed 100 payment link records with 1–3 items each.
     */
    public function run(): void
    {
        $count = 100;
        $this->command->info("Creating {$count} payment links...");

        for ($i = 1; $i <= $count; $i++) {
            $numItems = rand(1, 3);
            $selected = $this->pickRandomProducts($numItems);
            $totalAmount = 0;
            $itemRows = [];

            foreach ($selected as $idx => $product) {
                $amount = rand(50, 500) * 1000; // 50,000 - 500,000 TZS
                $status = ['unpaid', 'partial', 'paid'][rand(0, 2)];
                $paidAmount = $status === 'unpaid' ? 0 : ($status === 'paid' ? $amount : (int) ($amount * (rand(20, 80) / 100)));
                $totalAmount += $amount;
                $itemRows[] = [
                    'item_code' => 'ITEM_' . strtoupper(substr(uniqid('', true), -10)),
                    'type' => 'service',
                    'product_service_reference' => $product['ref'],
                    'product_service_name' => $product['name'],
                    'description' => null,
                    'amount' => $amount,
                    'minimum_amount' => null,
                    'is_required' => true,
                    'allow_partial' => $product['allow_partial'],
                    'payment_status' => $status,
                    'paid_amount' => $paidAmount,
                ];
            }

            $linkId = $i <= 5 ? 'LINK_' . strtoupper(substr(uniqid('', true), -12)) : null;
            $shortCode = $linkId ? strtoupper(substr(uniqid('', true), -8)) : ('MANUAL-' . strtoupper(substr(uniqid('', true), -6)));
            $paymentUrl = $linkId ? "http://example.com/pay/{$shortCode}" : null;

            $firstName = self::$firstNames[array_rand(self::$firstNames)];
            $lastName = self::$lastNames[array_rand(self::$lastNames)];
            $name = $firstName . ' ' . $lastName;
            $ref = 'MEMBER' . str_pad((string) (1000 + $i), 4, '0', STR_PAD_LEFT);
            $phone = '2557' . str_pad((string) rand(10000000, 79999999), 8, '0');
            $email = strtolower($firstName) . '.' . strtolower($lastName) . '@example.com';

            $link = PaymentLink::create([
                'link_id' => $linkId,
                'short_code' => $shortCode,
                'payment_url' => $paymentUrl,
                'qr_code_data' => $paymentUrl,
                'target_type' => rand(0, 4) === 0 ? 'business' : 'individual',
                'is_public' => false,
                'total_amount' => $totalAmount,
                'currency' => 'TZS',
                'description' => 'Saccos services',
                'customer_reference' => $ref,
                'customer_name' => $name,
                'customer_phone' => $phone,
                'customer_email' => $email,
                'expires_at' => now()->addMonths(rand(3, 12)),
                'max_uses' => null,
                'is_reusable' => false,
                'allowed_networks' => ['TZ-AIRTEL-C2B', 'TZ-TIGO-C2B', 'TZ-MPESA-C2B', 'TZ-HALOPESA-C2B'],
                'api_request_id' => $linkId ? 'req_' . substr(uniqid('', true), -12) : null,
                'api_response_at' => $linkId ? now()->toIso8601String() : null,
            ]);

            foreach ($itemRows as $row) {
                $link->items()->create($row);
            }
        }

        $this->command->info("Created {$count} payment links with items.");
    }

    protected function pickRandomProducts(int $count): array
    {
        $keys = array_rand(self::$productRefs, min($count, count(self::$productRefs)));
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        shuffle($keys);
        return array_map(fn ($k) => self::$productRefs[$k], array_slice($keys, 0, $count));
    }
}
