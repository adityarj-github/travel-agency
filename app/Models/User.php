<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /** Available roles, highest privilege last. */
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_ADMIN = 'admin';

    /** Roles that may access the admin panel. */
    public const STAFF_ROLES = [self::ROLE_EDITOR, self::ROLE_MANAGER, self::ROLE_ADMIN];

    /**
     * Permission => roles allowed. The 'admin' role implicitly has everything
     * (see App\Providers\AuthServiceProvider::Gate::before).
     */
    public const PERMISSIONS = [
        'manage_content'   => [self::ROLE_EDITOR, self::ROLE_MANAGER, self::ROLE_ADMIN],
        'manage_bookings'  => [self::ROLE_MANAGER, self::ROLE_ADMIN],
        'manage_coupons'   => [self::ROLE_MANAGER, self::ROLE_ADMIN],
        'manage_inquiries' => [self::ROLE_MANAGER, self::ROLE_ADMIN],
        'manage_settings'  => [self::ROLE_ADMIN],
        'manage_users'     => [self::ROLE_ADMIN],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
        'phone',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'is_admin' => 'boolean',
        ];
    }

    /* ----------------- Relationships ----------------- */

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'wishlists')->withTimestamps();
    }

    /* ----------------- Roles & permissions ----------------- */

    /** Staff (editor/manager/admin) may reach the admin panel. */
    public function isAdmin(): bool
    {
        return $this->isStaff();
    }

    public function isStaff(): bool
    {
        return in_array($this->role, self::STAFF_ROLES, true);
    }

    public function isCustomer(): bool
    {
        return ! $this->isStaff();
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /** Permission check used by the Gate (super-admins bypass via Gate::before). */
    public function hasPermission(string $permission): bool
    {
        $roles = self::PERMISSIONS[$permission] ?? [];

        return in_array($this->role, $roles, true);
    }

    public function getRoleLabelAttribute(): string
    {
        return ucfirst($this->role ?? 'customer');
    }
}
