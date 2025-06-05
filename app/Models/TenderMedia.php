<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderMedia extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
}
