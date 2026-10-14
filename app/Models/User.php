<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'designation',
        'avatar',
        'password',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_code',
        'two_factor_expires_at',
        'two_factor_confirmed_at',
        'last_login_at',
        'last_login_ip',
    ];

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            return asset('storage/'.$this->avatar);
        }

        return null;
    }

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_code',
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
            'two_factor_enabled' => 'boolean',
            'two_factor_recovery_codes' => 'array',
            'two_factor_expires_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function consumeRecoveryCode(string $code): bool
    {
        $codes = $this->two_factor_recovery_codes ?? [];
        $code = strtoupper(trim($code));

        foreach ($codes as $index => $c) {
            if (hash_equals($c, $code)) {
                unset($codes[$index]);
                $this->forceFill([
                    'two_factor_recovery_codes' => array_values($codes),
                ])->save();

                return true;
            }
        }

        return false;
    }

    public function generateTwoFactorCode(): string
    {
        $code = (string) random_int(100000, 999999);
        $this->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        return $code;
    }

    public function resetTwoFactorCode(): void
    {
        $this->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);
    }

    public function schools()
    {
        return $this->hasMany(School::class);
    }

    public function applications()
    {
        return $this->hasMany(FeasibilityApplication::class, 'applicant_user_id');
    }

    public function assignedApplications()
    {
        return $this->hasMany(FeasibilityApplication::class, 'assigned_to');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function fieldVisits()
    {
        return $this->hasMany(FieldVisit::class, 'officer_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    public function isInspector(): bool
    {
        return $this->hasRole('Inspector');
    }

    public function getRoleNameAttribute(): ?string
    {
        if ($this->hasRole('Super Admin')) {
            return 'Super Admin';
        }

        if ($this->hasRole('Inspector')) {
            return 'Inspector';
        }

        return $this->roles->first()?->name;
    }

    public function isAdmin(): bool
    {
        return $this->isSuperAdmin();
    }

    public function isManagement(): bool
    {
        return $this->isSuperAdmin();
    }

    public function isReviewer(): bool
    {
        return $this->isInspector();
    }

    public function isApplicant(): bool
    {
        return false;
    }
}
