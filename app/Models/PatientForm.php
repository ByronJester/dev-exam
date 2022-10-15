<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getTbAttribute($value)
    {
        if(!$value) return $value;

        return json_decode($value);
    }
}
