<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuctionOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'offer_number',
        'auction_vehicle_id',
        'dealer_id',
        'entity_id',
        'offer_amount',
        'currency',
        'message',
        'terms',
        'status',
        'counter_amount',
        'counter_message',
        'countered_at',
        'responded_at',
        'response_message',
        'dealer_name',
        'dealer_phone',
        'dealer_email',
        'company_name',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'offer_amount' => 'decimal:2',
        'counter_amount' => 'decimal:2',
        'countered_at' => 'datetime',
        'responded_at' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Generate a unique offer number
     */
    public static function generateOfferNumber(): string
    {
        $prefix = 'OFR';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->offer_number)) {
                $model->offer_number = self::generateOfferNumber();
            }
        });

        static::created(function ($model) {
            $model->auctionVehicle->updateOfferStats();
        });
    }

    // Relationships
    public function auctionVehicle()
    {
        return $this->belongsTo(AuctionVehicle::class);
    }

    public function dealer()
    {
        return $this->belongsTo(User::class, 'dealer_id');
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'withdrawn' => 'Withdrawn',
            'countered' => 'Countered',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'accepted' => 'green',
            'rejected' => 'red',
            'withdrawn' => 'gray',
            'countered' => 'blue',
            default => 'gray',
        };
    }

    // Methods
    public function withdraw()
    {
        $this->status = 'withdrawn';
        $this->is_active = false;
        $this->save();
        $this->auctionVehicle->updateOfferStats();
    }

    public function counter($amount, $message = null)
    {
        $this->counter_amount = $amount;
        $this->counter_message = $message;
        $this->countered_at = now();
        $this->status = 'countered';
        $this->save();
    }
}

