<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_number',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'category',
        'status',
        'assigned_to_user_id',
        'resolved_at',
        'resolution_notes',
        'user_id',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_CLOSED = 'closed';

    public const CATEGORIES = [
        'general' => 'General',
        'service' => 'Service',
        'product' => 'Product',
        'payment' => 'Payment',
        'other' => 'Other',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to_user_id', $userId);
    }

    /**
     * Generate a random, unguessable tracking number (e.g. KIBO-C-A7X9K2M4P1Qw).
     */
    public static function generateComplaintNumber(): string
    {
        $prefix = 'KIBO-C-';
        $length = 12; // 36^12 combinations – not guessable

        do {
            $random = strtoupper(Str::random($length));
            $number = $prefix . $random;
        } while (static::where('complaint_number', $number)->exists());

        return $number;
    }
}
