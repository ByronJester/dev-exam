<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrenatalForm extends Model
{
    use HasFactory;

    protected $appends = [
        'name', 'description'
    ];

    public function getNameAttribute()
    {
        return 'Prenatal Registration Form';
    }

    public function getDescriptionAttribute()
    {
        return 'Prenatal Registration Form';
    }

    public function getBehavioralRisksAttribute($value)
    {
        if(!$value) return [];

        return json_decode($value);
    }

    public function getPyschologicalRisksAttribute($value)
    {
        if(!$value) return [];

        return json_decode($value);
    }

    public function getMedicalRisksAttribute($value)
    {
        if(!$value) return [];

        return json_decode($value);
    }

    public function getObstetricsRisksAttribute($value)
    {
        if(!$value) return [];

        return json_decode($value);
    }
}
