<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TenderRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'property_address',
        'suburb',
        'state',
        'postcode',
        'number_of_windows',
        'appointment_datetime',
        'status',
        'customer_notes',
        'admin_notes',
        'preferred_contact_method',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_datetime' => 'datetime',
        'number_of_windows' => 'integer',
    ];

    /**
     * Get the customer that owns the tender request.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the full property address.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->property_address,
            $this->suburb,
            $this->state,
            $this->postcode
        ]);
        
        return implode(', ', $parts);
    }

    /**
     * Get formatted appointment date and time.
     */
    public function getFormattedAppointmentAttribute(): string
    {
        return $this->appointment_datetime->format('l, j F Y \a\t g:i A');
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'badge-warning',
            'confirmed' => 'badge-info',
            'completed' => 'badge-success',
            'cancelled' => 'badge-error',
            default => 'badge-neutral',
        };
    }

    /**
     * Get available appointment slots for a given date.
     */
    public static function getAvailableSlots(Carbon $date): array
    {
        // Business hours: 8 AM to 5 PM (3-hour slots: 8-11, 11-2, 2-5)
        $slots = [
            ['start' => '08:00', 'end' => '11:00', 'label' => '8:00 AM - 11:00 AM'],
            ['start' => '11:00', 'end' => '14:00', 'label' => '11:00 AM - 2:00 PM'],
            ['start' => '14:00', 'end' => '17:00', 'label' => '2:00 PM - 5:00 PM'],
        ];

        $availableSlots = [];
        
        foreach ($slots as $slot) {
            $slotDateTime = $date->copy()->setTimeFromTimeString($slot['start']);
            
            // Check if slot is not in the past
            if ($slotDateTime->isFuture()) {
                // Check if slot is not already booked
                $isBooked = self::where('appointment_datetime', $slotDateTime)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->exists();
                
                if (!$isBooked) {
                    $availableSlots[] = [
                        'datetime' => $slotDateTime,
                        'value' => $slotDateTime->format('Y-m-d H:i:s'),
                        'label' => $slot['label'],
                    ];
                }
            }
        }
        
        return $availableSlots;
    }

    /**
     * Get Australian states.
     */
    public static function getAustralianStates(): array
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
     * Scope for pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for confirmed requests.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}