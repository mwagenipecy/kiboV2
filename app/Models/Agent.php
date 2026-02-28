<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'agent_type',
        'vehicle_makes',
        'services',
        'spare_part_details',
        'support_logistics',
        'license_number',
        'address',
        'latitude',
        'longitude',
        'company_name',
        'status',
        'approval_status',
        'user_id',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'vehicle_makes' => 'array',
        'services' => 'array',
        'support_logistics' => 'boolean',
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
