<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    protected $fillable = [
        'section',
        'reportable_id',
        'reportable_type',
        'reporter_id',
        'reporter_email',
        'reporter_name',
        'reason',
        'description',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the parent reportable model (vehicle, user, etc.)
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who made the report
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Get the admin who reviewed the report
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope for pending reports
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for a specific section
     */
    public function scopeForSection($query, $section)
    {
        return $query->where('section', $section);
    }
}
