<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'name', 'description', 'date_issued'
    ];

    public function getNameAttribute()
    {
        return 'Deworming Form';
    }

    public function getDescriptionAttribute()
    {
        return 'Deworming Form';
    }

    public function getDateIssuedAttribute()
    {
        $date = Carbon::parse($this->created_at);

        return $date->isoFormat('LL'); 
    }
}
