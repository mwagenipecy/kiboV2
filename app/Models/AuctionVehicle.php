<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuctionVehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'auction_number',
        'user_id',
        'title',
        'description',
        'vehicle_make_id',
        'vehicle_model_id',
        'variant',
        'year',
        'condition',
        'registration_number',
        'vin',
        'body_type',
        'fuel_type',
        'transmission',
        'engine_capacity',
        'color_exterior',
        'doors',
        'seats',
        'mileage',
        'asking_price',
        'minimum_price',
        'currency',
        'image_front',
        'other_images',
        'location',
        'city',
        'region',
        'latitude',
        'longitude',
        'contact_name',
        'contact_phone',
        'contact_email',
        'status',
        'is_visible',
        'admin_approved',
        'approved_at',
        'approved_by',
        'auction_start',
        'auction_end',
        'offer_count',
        'highest_offer',
        'accepted_offer_id',
        'deal_closed_at',
        'closure_notes',
        'admin_notes',
    ];

    protected $casts = [
        'other_images' => 'array',
        'asking_price' => 'decimal:2',
        'minimum_price' => 'decimal:2',
        'highest_offer' => 'decimal:2',
        'is_visible' => 'boolean',
        'admin_approved' => 'boolean',
        'approved_at' => 'datetime',
        'auction_start' => 'datetime',
        'auction_end' => 'datetime',
        'deal_closed_at' => 'datetime',
    ];

    /**
     * Generate a unique auction number
     */
    public static function generateAuctionNumber(): string
    {
        $prefix = 'AUC';
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
            if (empty($model->auction_number)) {
                $model->auction_number = self::generateAuctionNumber();
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function make()
    {
        return $this->belongsTo(VehicleMake::class, 'vehicle_make_id');
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function offers()
    {
        return $this->hasMany(AuctionOffer::class);
    }

    public function acceptedOffer()
    {
        return $this->belongsTo(AuctionOffer::class, 'accepted_offer_id');
    }

    public function activeOffers()
    {
        return $this->offers()->where('status', 'pending')->where('is_active', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('is_visible', true)
                     ->where('admin_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForDealer($query)
    {
        return $query->active()
                     ->where(function ($q) {
                         $q->whereNull('auction_end')
                           ->orWhere('auction_end', '>', now());
                     });
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending Approval',
            'active' => 'Active',
            'closed' => 'Closed',
            'sold' => 'Sold',
            'cancelled' => 'Cancelled',
            'expired' => 'Expired',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'active' => 'green',
            'closed' => 'blue',
            'sold' => 'purple',
            'cancelled' => 'red',
            'expired' => 'gray',
            default => 'gray',
        };
    }

    // Methods
    public function updateOfferStats()
    {
        $this->offer_count = $this->offers()->count();
        $this->highest_offer = $this->offers()->max('offer_amount');
        $this->save();
    }

    public function acceptOffer(AuctionOffer $offer)
    {
        $this->accepted_offer_id = $offer->id;
        $this->status = 'sold';
        $this->deal_closed_at = now();
        $this->save();

        $offer->update(['status' => 'accepted', 'responded_at' => now()]);

        // Reject all other pending offers
        $this->offers()
             ->where('id', '!=', $offer->id)
             ->where('status', 'pending')
             ->update(['status' => 'rejected', 'responded_at' => now()]);
    }

    public function closeAuction($notes = null)
    {
        $this->status = 'closed';
        $this->deal_closed_at = now();
        $this->closure_notes = $notes;
        $this->save();
    }
}

