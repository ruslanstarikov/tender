<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Supplier extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'abn',
        'website',
        'company_description',
        'logo_path',
        'established_date',
        'first_name',
        'last_name',
        'position',
        'email',
        'phone',
        'password',
        'street_number',
        'street_name',
        'street_type',
        'unit_number',
        'suburb',
        'state',
        'postcode',
        'specialty_areas',
        'certifications',
        'minimum_order_value',
        'lead_time_days',
        'is_active',
        'is_verified',
        'onboarding_status',
        'admin_notes',
        'contact_preferences',
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
        'established_date' => 'date',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'specialty_areas' => 'array',
        'contact_preferences' => 'array',
        'minimum_order_value' => 'decimal:2',
        'lead_time_days' => 'integer',
        'password' => 'hashed',
    ];

    /**
     * Get the contact person's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the supplier's display name (company name + contact person).
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->company_name} ({$this->full_name})";
    }

    /**
     * Get the supplier's full address.
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
     * Get the logo URL.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }
        
        return Storage::url($this->logo_path);
    }

    /**
     * Get formatted ABN.
     */
    public function getFormattedAbnAttribute(): ?string
    {
        if (!$this->abn) {
            return null;
        }
        
        // Format ABN as XX XXX XXX XXX
        return substr($this->abn, 0, 2) . ' ' . 
               substr($this->abn, 2, 3) . ' ' . 
               substr($this->abn, 5, 3) . ' ' . 
               substr($this->abn, 8, 3);
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->onboarding_status) {
            'pending' => 'warning',
            'documents_required' => 'info',
            'under_review' => 'warning',
            'approved' => 'success',
            'rejected' => 'error',
            default => 'neutral'
        };
    }

    /**
     * Scope for active suppliers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for verified suppliers.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for approved suppliers.
     */
    public function scopeApproved($query)
    {
        return $query->where('onboarding_status', 'approved');
    }

    /**
     * Scope for suppliers by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('onboarding_status', $status);
    }

    /**
     * Scope for suppliers by state.
     */
    public function scopeByState($query, $state)
    {
        return $query->where('state', $state);
    }

    /**
     * Update the supplier's last login timestamp.
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Generate a new password for the supplier.
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
     * Get specialty areas as a formatted string.
     */
    public function getSpecialtyAreasStringAttribute(): string
    {
        if (!$this->specialty_areas || !is_array($this->specialty_areas)) {
            return 'Not specified';
        }
        
        return implode(', ', $this->specialty_areas);
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

    /**
     * Common window frame specialty areas.
     */
    public static function getSpecialtyAreas()
    {
        return [
            'Aluminium Frames',
            'Timber Frames',
            'UPVC Frames',
            'Steel Frames',
            'Composite Frames',
            'Double Glazing',
            'Custom Windows',
            'Commercial Windows',
            'Residential Windows',
            'Bi-fold Doors',
            'Sliding Doors',
            'French Doors',
            'Security Screens',
            'Window Hardware',
            'Glass Supply',
        ];
    }

    /**
     * Onboarding status options.
     */
    public static function getOnboardingStatuses()
    {
        return [
            'pending' => 'Pending Review',
            'documents_required' => 'Documents Required',
            'under_review' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];
    }
}
