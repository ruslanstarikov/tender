<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateCell extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'cell_index',
        'x',
        'y',
        'width_ratio',
        'height_ratio',
    ];

    protected $casts = [
        'x' => 'decimal:4',
        'y' => 'decimal:4',
        'width_ratio' => 'decimal:4',
        'height_ratio' => 'decimal:4',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(WindowTemplate::class, 'template_id');
    }

    public function windowCells(): HasMany
    {
        return $this->hasMany(WindowCell::class);
    }

    public function getAbsoluteX(int $windowWidth): float
    {
        return $this->x * $windowWidth;
    }

    public function getAbsoluteY(int $windowHeight): float
    {
        return $this->y * $windowHeight;
    }

    public function getAbsoluteWidth(int $windowWidth): float
    {
        return $this->width_ratio * $windowWidth;
    }

    public function getAbsoluteHeight(int $windowHeight): float
    {
        return $this->height_ratio * $windowHeight;
    }
}