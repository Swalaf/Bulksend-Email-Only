<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'is_banned',
        'onboarding_step',
        'business_name',
        'business_description',
        'business_website',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'is_banned' => 'boolean',
        'password' => 'hashed',
    ];

    // Onboarding steps
    const ONBOARDING_WELCOME = 'welcome';
    const ONBOARDING_BUSINESS = 'business';
    const ONBOARDING_SMTP = 'smtp';
    const ONBOARDING_CAMPAIGN = 'campaign';
    const ONBOARDING_COMPLETE = 'complete';

    const ONBOARDING_STEPS = [
        self::ONBOARDING_WELCOME,
        self::ONBOARDING_BUSINESS,
        self::ONBOARDING_SMTP,
        self::ONBOARDING_CAMPAIGN,
        self::ONBOARDING_COMPLETE,
    ];

    public function smtpAccounts()
    {
        return $this->hasMany(SmtpAccount::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function subscriberLists()
    {
        return $this->hasMany(SubscriberList::class);
    }

    public function subscribers()
    {
        return $this->hasMany(Subscriber::class);
    }

    // Role checks
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    // Onboarding methods
    public function hasCompletedOnboarding(): bool
    {
        return $this->onboarding_step === self::ONBOARDING_COMPLETE;
    }

    public function getCurrentOnboardingStep(): string
    {
        return $this->onboarding_step ?? self::ONBOARDING_WELCOME;
    }

    public function getNextOnboardingStep(): ?string
    {
        $currentStepIndex = array_search($this->getCurrentOnboardingStep(), self::ONBOARDING_STEPS);
        
        if ($currentStepIndex === false || $currentStepIndex >= count(self::ONBOARDING_STEPS) - 1) {
            return null;
        }
        
        return self::ONBOARDING_STEPS[$currentStepIndex + 1];
    }

    public function getOnboardingProgress(): int
    {
        $currentStepIndex = array_search($this->getCurrentOnboardingStep(), self::ONBOARDING_STEPS);
        
        if ($currentStepIndex === false) {
            return 0;
        }
        
        return round(($currentStepIndex / (count(self::ONBOARDING_STEPS) - 1)) * 100);
    }

    public function updateOnboardingStep(string $step): void
    {
        if (in_array($step, self::ONBOARDING_STEPS)) {
            $this->update(['onboarding_step' => $step]);
        }
    }

    public function completeOnboarding(): void
    {
        $this->update(['onboarding_step' => self::ONBOARDING_COMPLETE]);
    }

    public function skipOnboarding(): void
    {
        $this->update(['onboarding_step' => self::ONBOARDING_COMPLETE]);
    }
}
