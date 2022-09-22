<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'place_id',
        'date'
    ];

    protected $with = [
        'place'
    ];

    protected $appends = [
        'barangay'
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function getBarangayAttribute()
    {
        return $this->place->name;
    }

    public function getDateAttribute($value)
    {
        $date = Carbon::parse($value);

        return $date->isoFormat('LL');
    }
}
