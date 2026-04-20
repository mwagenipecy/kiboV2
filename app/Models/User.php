<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Customer;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Services\SelcomSmsService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'role',
        'status',
        'entity_id',
        'otp_code',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get the entity that the user belongs to
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * Get the customer profile for this user
     */
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Get the agent profile for this user
     */
    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    /**
     * Get the CFC profile for this user
     */
    public function cfc()
    {
        return $this->hasOne(Cfc::class);
    }

    /**
     * Get login activity entries for this user.
     */
    public function loginActivities(): HasMany
    {
        return $this->hasMany(LoginActivity::class);
    }

    /**
     * Resolve the user's phone number — prefers users.phone_number,
     * falls back to the role-specific profile table.
     */
    public function getPhoneNumber(): ?string
    {
        if (!empty($this->phone_number)) {
            return $this->phone_number;
        }

        return match ($this->role) {
            'customer' => optional($this->customer)->phone_number,
            'dealer', 'lender', 'manufacturer', 'insurance', 'service_center'
                       => optional($this->entity)->phone,
            'agent'    => optional($this->agent)->phone_number,
            'cfc'      => optional($this->cfc)->phone_number,
            default    => null,
        };
    }

    /**
     * Check if user belongs to a dealer entity
     */
    public function isDealer(): bool
    {
        return $this->entity && $this->entity->type->value === 'dealer';
    }

    /**
     * Check if user belongs to a lender entity
     */
    public function isLender(): bool
    {
        return $this->entity && $this->entity->type->value === 'lender';
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        try {
            $this->notify(new ResetPassword($token));
        } catch (\Exception $e) {
            \Log::error('Failed to queue password reset email: ' . $e->getMessage());
        }

        $this->loadMissing(['customer', 'entity', 'agent', 'cfc']);

        $phone = $this->resolvePhoneForPasswordResetSms();
        if (empty($phone)) {
            \Log::warning('Password reset SMS skipped: no phone resolved for user', [
                'user_id' => $this->id,
                'email' => $this->email,
                'role' => $this->role,
            ]);

            return;
        }

        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));

        try {
            $sent = app(SelcomSmsService::class)->send(
                $phone,
                "Kibo Auto: Reset password {$resetUrl}"
            );

            if (!$sent) {
                \Log::error('Password reset SMS failed (Selcom did not accept message)', [
                    'user_id' => $this->id,
                    'phone_raw' => $phone,
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error('Password reset SMS exception: ' . $e->getMessage(), [
                'user_id' => $this->id,
                'phone_raw' => $phone,
            ]);
        }
    }

    /**
     * Best-effort phone for forgot-password SMS (user column, role profile, then customer by link/email).
     */
    private function resolvePhoneForPasswordResetSms(): ?string
    {
        $phone = $this->getPhoneNumber();
        if (!empty($phone)) {
            return $phone;
        }

        $fromCustomer = Customer::query()
            ->where('user_id', $this->id)
            ->value('phone_number');

        if (!empty($fromCustomer)) {
            return $fromCustomer;
        }

        return Customer::query()
            ->where('email', $this->email)
            ->value('phone_number');
    }
}
