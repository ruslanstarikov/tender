<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderFrame extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tender_id',
        'frame_type_id',
        'width',
        'height',
        'quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Get the tender that owns the frame.
     */
    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    /**
     * Get the frame type.
     */
    public function frameType()
    {
        return $this->belongsTo(FrameType::class);
    }

    /**
     * Get the total area for this frame (width * height * quantity).
     */
    public function getTotalAreaAttribute(): float
    {
        return $this->width * $this->height * $this->quantity;
    }

    /**
     * Get the area for a single frame (width * height).
     */
    public function getSingleAreaAttribute(): float
    {
        return $this->width * $this->height;
    }

    /**
     * Get formatted dimensions.
     */
    public function getDimensionsAttribute(): string
    {
        return "{$this->width}mm x {$this->height}mm";
    }

    /**
     * Get display name with dimensions and quantity.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->frameType->display_name} - {$this->dimensions} (Qty: {$this->quantity})";
    }

    /**
     * Scope for filtering by tender.
     */
    public function scopeForTender($query, $tenderId)
    {
        return $query->where('tender_id', $tenderId);
    }

    /**
     * Scope for filtering by frame type.
     */
    public function scopeByFrameType($query, $frameTypeId)
    {
        return $query->where('frame_type_id', $frameTypeId);
    }

    /**
     * Get total area for multiple frames.
     */
    public static function getTotalAreaForTender($tenderId)
    {
        return static::where('tender_id', $tenderId)
            ->get()
            ->sum('total_area');
    }

    /**
     * Get frame count for tender.
     */
    public static function getTotalQuantityForTender($tenderId)
    {
        return static::where('tender_id', $tenderId)
            ->sum('quantity');
    }
}