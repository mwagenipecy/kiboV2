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
            'password' => $this->passwordRules(),
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
            'user_id' => $user->id,
            'status' => 'active',
            'approval_status' => 'approved', // Auto-approve customer registrations
            'approved_at' => now(),
        ]);

        return $user;
    }
}
