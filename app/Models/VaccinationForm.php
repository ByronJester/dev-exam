<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'guardian_name',
        'dob',
        'age',
        'height',
        'weight',
        'bmi',
        'vaccination_id'
    ];

    protected $appends = [
        'name', 'description'
    ];

    public function getNameAttribute()
    {
        return 'Vaccination Form';
    }

    public function getDescriptionAttribute()
    {
        return 'Vaccination Form';
    }
}
