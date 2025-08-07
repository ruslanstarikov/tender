<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WindowCell extends Model
{
    use HasFactory;

    protected $fillable = [
        'window_id',
        'template_cell_id',
        'open_left',
        'open_right',
        'open_top',
        'open_bottom',
        'slide_left',
        'slide_right',
        'slide_top',
        'slide_bottom',
        'folding_left',
        'folding_right',
        'folding_top',
        'folding_bottom',
        'notes',
    ];

    protected $casts = [
        'open_left' => 'boolean',
        'open_right' => 'boolean',
        'open_top' => 'boolean',
        'open_bottom' => 'boolean',
        'slide_left' => 'boolean',
        'slide_right' => 'boolean',
        'slide_top' => 'boolean',
        'slide_bottom' => 'boolean',
        'folding_left' => 'boolean',
        'folding_right' => 'boolean',
        'folding_top' => 'boolean',
        'folding_bottom' => 'boolean',
    ];

    public function window(): BelongsTo
    {
        return $this->belongsTo(Window::class);
    }

    public function templateCell(): BelongsTo
    {
        return $this->belongsTo(TemplateCell::class);
    }

    public function getOpenDirectionsAttribute(): array
    {
        return [
            'left' => $this->open_left,
            'right' => $this->open_right,
            'top' => $this->open_top,
            'bottom' => $this->open_bottom,
        ];
    }

    public function getSlideDirectionsAttribute(): array
    {
        return [
            'left' => $this->slide_left,
            'right' => $this->slide_right,
            'top' => $this->slide_top,
            'bottom' => $this->slide_bottom,
        ];
    }

    public function getFoldingDirectionsAttribute(): array
    {
        return [
            'left' => $this->folding_left,
            'right' => $this->folding_right,
            'top' => $this->folding_top,
            'bottom' => $this->folding_bottom,
        ];
    }

    public function hasAnyOpening(): bool
    {
        return $this->open_left || $this->open_right || $this->open_top || $this->open_bottom;
    }

    public function hasAnySliding(): bool
    {
        return $this->slide_left || $this->slide_right || $this->slide_top || $this->slide_bottom;
    }

    public function hasAnyFolding(): bool
    {
        return $this->folding_left || $this->folding_right || $this->folding_top || $this->folding_bottom;
    }

    public function hasAnyMovement(): bool
    {
        return $this->hasAnyOpening() || $this->hasAnySliding() || $this->hasAnyFolding();
    }

    public function getOpenDirectionsList(): array
    {
        $directions = [];
        if ($this->open_left) $directions[] = 'left';
        if ($this->open_right) $directions[] = 'right';
        if ($this->open_top) $directions[] = 'top';
        if ($this->open_bottom) $directions[] = 'bottom';
        
        return $directions;
    }

    public function getSlideDirectionsList(): array
    {
        $directions = [];
        if ($this->slide_left) $directions[] = 'left';
        if ($this->slide_right) $directions[] = 'right';
        if ($this->slide_top) $directions[] = 'top';
        if ($this->slide_bottom) $directions[] = 'bottom';
        
        return $directions;
    }

    public function getFoldingDirectionsList(): array
    {
        $directions = [];
        if ($this->folding_left) $directions[] = 'left';
        if ($this->folding_right) $directions[] = 'right';
        if ($this->folding_top) $directions[] = 'top';
        if ($this->folding_bottom) $directions[] = 'bottom';
        
        return $directions;
    }

    public function getAllMovementTypes(): array
    {
        $movements = [];
        
        if ($this->hasAnyOpening()) {
            $movements['opening'] = $this->getOpenDirectionsList();
        }
        
        if ($this->hasAnySliding()) {
            $movements['sliding'] = $this->getSlideDirectionsList();
        }
        
        if ($this->hasAnyFolding()) {
            $movements['folding'] = $this->getFoldingDirectionsList();
        }
        
        return $movements;
    }
}