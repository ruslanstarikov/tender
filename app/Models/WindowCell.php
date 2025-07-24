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
        'notes',
    ];

    protected $casts = [
        'open_left' => 'boolean',
        'open_right' => 'boolean',
        'open_top' => 'boolean',
        'open_bottom' => 'boolean',
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

    public function hasAnyOpening(): bool
    {
        return $this->open_left || $this->open_right || $this->open_top || $this->open_bottom;
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
}