<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrameType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'filename',
        'type',
        'panels',
        'config',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'config' => 'array',
        'panels' => 'integer',
    ];

    /**
     * Get tender frames for this frame type.
     */
    public function tenderFrames()
    {
        return $this->hasMany(TenderFrame::class);
    }

    /**
     * Scope for filtering by frame type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for filtering by panel count.
     */
    public function scopeByPanels($query, $panels)
    {
        return $query->where('panels', $panels);
    }

    /**
     * Scope for filtering by configuration.
     */
    public function scopeWithConfig($query, $configValue)
    {
        return $query->whereJsonContains('config', $configValue);
    }

    /**
     * Get config as a formatted string.
     */
    public function getConfigStringAttribute(): string
    {
        if (!$this->config || !is_array($this->config)) {
            return 'No configuration';
        }
        
        return implode(', ', $this->config);
    }

    /**
     * Get image URL for the frame type.
     */
    public function getImageUrlAttribute(): string
    {
        return asset("frametypes/{$this->filename}.svg");
    }

    /**
     * Get available frame types.
     */
    public static function getFrameTypes()
    {
        return [
            'fixed' => 'Fixed Window',
            'casement' => 'Casement Window',
            'awning' => 'Awning Window',
            'hopper' => 'Hopper Window',
            'sliding' => 'Sliding Window',
            'doublehung' => 'Double Hung Window',
            'tilt-turn' => 'Tilt & Turn Window',
            'combo' => 'Combination Window',
        ];
    }

    /**
     * Get display name for the frame type.
     */
    public function getDisplayNameAttribute(): string
    {
        $types = self::getFrameTypes();
        $typeName = $types[$this->type] ?? ucfirst($this->type);
        
        return "{$typeName} ({$this->panels} panel" . ($this->panels > 1 ? 's' : '') . ")";
    }

    /**
     * Check if frame type has specific configuration.
     */
    public function hasConfig($configValue): bool
    {
        return in_array($configValue, $this->config ?? []);
    }

    /**
     * Get frame types by category.
     */
    public static function getByCategory()
    {
        return [
            'Basic' => ['fixed'],
            'Hinged' => ['casement', 'awning', 'hopper'],
            'Sliding' => ['sliding', 'doublehung'],
            'Advanced' => ['tilt-turn'],
            'Combination' => ['combo'],
        ];
    }

    /**
     * Get frame types grouped by type for API/JSON responses.
     */
    public static function getGroupedByType()
    {
        return static::orderBy('type')->orderBy('panels')->get()->groupBy('type');
    }
}