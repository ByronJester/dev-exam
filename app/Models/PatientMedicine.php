<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medicine_id',
        'quantity'
    ];

    protected $with = [
        'medicine',
        'patient'
    ];

    protected $appends = [
        'name'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function getNameAttribute()
    {
        return $this->medicine->name;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
