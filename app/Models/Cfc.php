<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cfc extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'registration_number',
        'tin_number',
        'address',
        'contact_person',
        'status',
        'approval_status',
        'user_id',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
