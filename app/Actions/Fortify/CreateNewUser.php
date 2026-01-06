<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'nida_number' => ['required', 'string', 'size:20', 'regex:/^[0-9]{20}$/'],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => $this->passwordRules(),
        ], [
            'nida_number.required' => 'NIDA number is required.',
            'nida_number.size' => 'NIDA number must be exactly 20 digits.',
            'nida_number.regex' => 'NIDA number must contain only numbers.',
            'phone_number.required' => 'Phone number is required.',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'role' => 'customer', // Set role as customer by default
        ]);

        // Create customer record for the new user
        Customer::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'nida_number' => $input['nida_number'],
            'phone_number' => $input['phone_number'],
            'user_id' => $user->id,
            'status' => 'active',
            'approval_status' => 'approved', // Auto-approve customer registrations
            'approved_at' => now(),
        ]);

        return $user;
    }
}
