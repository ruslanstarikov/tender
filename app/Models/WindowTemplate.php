<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WindowTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function templateCells(): HasMany
    {
        return $this->hasMany(TemplateCell::class, 'template_id');
    }

    public function windows(): HasMany
    {
        return $this->hasMany(Window::class, 'template_id');
    }
}