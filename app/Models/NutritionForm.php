<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'name', 'description', 'date_issued', 'display_year'
    ];

    public function getNameAttribute()
    {
        return 'Nutrition Form';
    }

    public function getDescriptionAttribute()
    {
        return 'Nutrition Form';
    }

    public function getDateIssuedAttribute()
    {
        $date = Carbon::parse($this->created_at);

        return $date->isoFormat('LL'); 
    }

    public function getDisplayYearAttribute()
    {
        $date = Carbon::parse($this->created_at);

        return $date->year;
    }
}
