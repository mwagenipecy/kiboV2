<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealerCarOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_request_id',
        'entity_id',
        'user_id',
        'price',
        'message',
        'image_path',
        'status',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(CarRequest::class, 'car_request_id');
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


