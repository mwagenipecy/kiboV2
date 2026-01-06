<?php

return [
    // Standard Laravel validation messages
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
        'numeric' => 'The :attribute must be at least :min.',
    ],
    'max' => [
        'string' => 'The :attribute must not exceed :max characters.',
        'numeric' => 'The :attribute must not exceed :max.',
    ],
    'confirmed' => 'The :attribute confirmation does not match.',
    'unique' => 'The :attribute has already been taken.',
    'exists' => 'The selected :attribute is invalid.',
    
    // Password validation messages
    'password' => [
        'min' => 'The password must be at least :min characters.',
        'mixed' => 'The password must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The password must contain at least one number.',
        'symbols' => 'The password must contain at least one special character.',
    ],
    
    // Custom attribute names
    'attributes' => [
        'email' => 'email address',
        'password' => 'password',
        'name' => 'name',
        'title' => 'title',
        'description' => 'description',
        'nida_number' => 'NIDA number',
        'phone_number' => 'phone number',
    ],
];

