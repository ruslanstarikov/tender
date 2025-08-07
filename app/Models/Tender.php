<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function media()
    {
        return $this->hasMany(TenderMedia::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function frames()
    {
        return $this->hasMany(TenderFrame::class);
    }

    public function windows()
    {
        return $this->hasMany(Window::class);
    }
}
