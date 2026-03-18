<?php

namespace Database\Seeders;

use App\Models\AgizaImportRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class AgizaImportRequestSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->take(3)->get();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Creating sample customers...');
            $customers = User::factory()->count(3)->create(['role' => 'customer']);
        }

        foreach ($customers as $customer) {
            AgizaImportRequest::factory()->count(2)->create([
                'user_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
            ]);
        }

        $this->command->info('Created sample Agiza/Import requests for demonstration.');
    }
}
