<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DewormingForm extends Model
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
        'deworming_medication'
    ];


    protected $appends = [
        'name', 'description'
    ];

    public function getNameAttribute()
    {
        return 'Deworming Form';
    }

    public function getDescriptionAttribute()
    {
        return 'Deworming Form';
    }
}
