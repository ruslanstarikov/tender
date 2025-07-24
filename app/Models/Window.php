<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Window extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'template_id',
        'label',
        'width_mm',
        'height_mm',
        'notes',
    ];

    protected $casts = [
        'width_mm' => 'integer',
        'height_mm' => 'integer',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(WindowTemplate::class, 'template_id');
    }

    public function windowCells(): HasMany
    {
        return $this->hasMany(WindowCell::class);
    }

    public function getDimensionsAttribute(): string
    {
        return "{$this->width_mm} × {$this->height_mm} mm";
    }

    public function getAreaSqMetersAttribute(): float
    {
        return ($this->width_mm * $this->height_mm) / 1_000_000;
    }

    public function createCellsFromTemplate(): void
    {
        $this->windowCells()->delete();

        foreach ($this->template->templateCells as $templateCell) {
            $this->windowCells()->create([
                'template_cell_id' => $templateCell->id,
                'open_left' => false,
                'open_right' => false,
                'open_top' => false,
                'open_bottom' => false,
            ]);
        }
    }
}