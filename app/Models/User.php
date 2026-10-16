<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'locale', 'phone_verified_at'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'rules_accepted_at' => 'array',
            'password' => 'hashed',
        ];
    }

    public function booth(): HasOne
    {
        return $this->hasOne(Booth::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isLandowner(): bool
    {
        return $this->role === 'landowner';
    }

    /** حوزه‌هایی که هنوز قوانین‌شان پذیرفته نشده و پنل بدون آن‌ها باز نمی‌شود. */
    public function pendingRuleScopes(): array
    {
        if ($this->isVendor()) {
            if ($this->booth?->status !== 'approved') {
                return [];
            }

            $scopes = ['booth'];
            $this->loadMissing('booth.categories');
            $suppliesMaterials = $this->booth?->categories?->contains(
                fn ($category) => str_starts_with((string) $category->slug, 'building-')
            );
            if ($suppliesMaterials) {
                $scopes[] = 'materials';
            }

            return array_values(array_filter($scopes, fn ($scope) => ! $this->hasAcceptedRules($scope)));
        }

        if ($this->isLandowner() && ! $this->hasAcceptedRules('property')) {
            return ['property'];
        }

        return [];
    }

    public function hasAcceptedRules(string $scope): bool
    {
        return ! empty(($this->rules_accepted_at ?? [])[$scope]);
    }

    public function acceptRules(array $scopes): void
    {
        $current = $this->rules_accepted_at ?? [];
        foreach ($scopes as $scope) {
            $current[$scope] = now()->toIso8601String();
        }
        $this->forceFill(['rules_accepted_at' => $current])->save();
    }
}
