<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'name',
        'phone',
        'medicine'
    ];

    protected $with = [
        'place'
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
