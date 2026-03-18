<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgizaImportRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'vehicle_condition',
        'vehicle_link',
        'source_country',
        'request_type',
        'dealer_contact_info',
        'estimated_price',
        'price_currency',
        'special_requirements',
        'customer_notes',
        'documents',
        'vehicle_images',
        'status',
        'assigned_to',
        'admin_notes',
        'quoted_import_cost',
        'quoted_total_cost',
        'quote_currency',
        'quoted_at',
        'accepted_at',
    ];

    protected $casts = [
        'documents' => 'array',
        'vehicle_images' => 'array',
        'estimated_price' => 'decimal:2',
        'quoted_import_cost' => 'decimal:2',
        'quoted_total_cost' => 'decimal:2',
        'quoted_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public static function generateRequestNumber(): string
    {
        $prefix = 'AGZ';
        $date = now()->format('Ymd');
        $lastRequest = static::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastRequest ? (int) substr($lastRequest->request_number, -4) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'under_review' => 'bg-blue-100 text-blue-800',
            'quote_provided' => 'bg-purple-100 text-purple-800',
            'accepted' => 'bg-green-100 text-green-800',
            'in_progress' => 'bg-indigo-100 text-indigo-800',
            'completed' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'under_review' => 'Under Review',
            'quote_provided' => 'Quote Provided',
            'accepted' => 'Accepted',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }
}
