<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PatientForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'description',
        'reference',
        'tb',
        'lmp',
        'edc',
        'edd',
    ];

    protected $appends = [
        'date_issued',
        'display_year'
    ];

    public function getTbAttribute($value)
    {
        if(!$value) return $value;

        return json_decode($value);
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

    public function getNameAttribute($value)
    {
        if(!!$this->lmp) {
            return $value;
        } else {
            return 'TB-Dots Form';
        }
    }
}
