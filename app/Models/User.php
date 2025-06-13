<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'gender',
        'role',
        'address',
        'status',
        'salary',
        'hire_date',
        'is_active',
        'last_activity'
    ];

    const User_Roles = [
        '1' => 'Director',
        '2' => 'CEO',
        '3' => 'Manager',
        '4' => 'Salesman',
        '5' => 'Admin',
        '6' => 'Stock Manager',
        '7' => 'Cashier',
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
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'hire_date' => 'date',
            'last_activity' => 'datetime',
            'salary' => 'decimal:2'
        ];
    }



    /**
     * Check if phone is verified
     *
     * @return bool
     */
    public function hasVerifiedPhone()
    {
        return ! is_null($this->phone_verified_at);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }


    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    public function isManager()
    {
        return in_array($this->role, ['Admin', 'Manager']);
    }

    public function canManageStock()
    {
        return in_array($this->role, ['Admin', 'Manager', 'Stock Manager']);
    }

    public function isCashier()
    {
        return $this->role === 'Cashier';
    }
}
