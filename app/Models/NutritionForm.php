<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionForm extends Model
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
        'vitamins'
    ];

    protected $appends = [
        'name', 'description'
    ];

    public function getNameAttribute()
    {
        return 'Nutrition Form';
    }

    public function getDescriptionAttribute()
    {
        return 'Nutrition Form';
    }
}
