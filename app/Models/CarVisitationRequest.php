<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarVisitationRequest extends Model
{
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'name',
        'email',
        'phone',
        'visit_reason',
        'status',
        'scheduled_at',
        'location',
        'admin_notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public static function statuses(): array
    {
        return [
            'pending' => 'Pending',
            'scheduled' => 'Scheduled',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }
}
