<?php

return [
    // Standard Laravel validation messages
    'required' => 'Sehemu ya :attribute inahitajika.',
    'email' => ':attribute lazima iwe anwani halali ya barua pepe.',
    'min' => [
        'string' => ':attribute lazima iwe angalau herufi :min.',
        'numeric' => ':attribute lazima iwe angalau :min.',
    ],
    'max' => [
        'string' => ':attribute haipaswi kuzidi herufi :max.',
        'numeric' => ':attribute haipaswi kuzidi :max.',
    ],
    'confirmed' => 'Uthibitishaji wa :attribute haufanani.',
    'unique' => ':attribute tayari imetumika.',
    'exists' => ':attribute iliyochaguliwa si halali.',
    
    // Custom attribute names
    'attributes' => [
        'email' => 'anwani ya barua pepe',
        'password' => 'nenosiri',
        'name' => 'jina',
        'title' => 'kichwa',
        'description' => 'maelezo',
    ],
];

