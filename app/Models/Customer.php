<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'street_number',
        'street_name',
        'street_type',
        'suburb',
        'state',
        'postcode',
        'unit_number',
        'is_active',
        'date_of_birth',
        'notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get the customer's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the customer's full address.
     */
    public function getFullAddressAttribute(): string
    {
        $address = [];
        
        if ($this->unit_number) {
            $address[] = "Unit {$this->unit_number}";
        }
        
        if ($this->street_number && $this->street_name) {
            $street = trim("{$this->street_number} {$this->street_name} {$this->street_type}");
            $address[] = $street;
        }
        
        if ($this->suburb) {
            $address[] = $this->suburb;
        }
        
        if ($this->state && $this->postcode) {
            $address[] = "{$this->state} {$this->postcode}";
        }
        
        return implode(', ', array_filter($address));
    }

    /**
     * Scope for active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive customers.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Update the customer's last login timestamp.
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Generate a new password for the customer.
     */
    public function resetPassword($newPassword = null)
    {
        if (!$newPassword) {
            $newPassword = $this->generateRandomPassword();
        }
        
        $this->update(['password' => Hash::make($newPassword)]);
        
        return $newPassword;
    }

    /**
     * Generate a random password.
     */
    private function generateRandomPassword($length = 12)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        return substr(str_shuffle($characters), 0, $length);
    }

    /**
     * Get all tenders for this customer.
     */
    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }

    /**
     * Get recent tenders for this customer.
     */
    public function recentTenders($limit = 5)
    {
        return $this->tenders()->latest()->limit($limit);
    }

    /**
     * Get the count of tenders for this customer.
     */
    public function getTendersCountAttribute()
    {
        return $this->tenders()->count();
    }

    /**
     * Australian states for validation.
     */
    public static function getAustralianStates()
    {
        return [
            'NSW' => 'New South Wales',
            'VIC' => 'Victoria',
            'QLD' => 'Queensland',
            'WA' => 'Western Australia',
            'SA' => 'South Australia',
            'TAS' => 'Tasmania',
            'ACT' => 'Australian Capital Territory',
            'NT' => 'Northern Territory',
        ];
    }

    /**
     * Common Australian street types.
     */
    public static function getStreetTypes()
    {
        return [
            'Street', 'Road', 'Avenue', 'Drive', 'Lane', 'Court', 'Place', 
            'Crescent', 'Way', 'Close', 'Circuit', 'Parade', 'Boulevard', 
            'Terrace', 'Grove', 'Rise', 'Walk', 'Mews'
        ];
    }
}
