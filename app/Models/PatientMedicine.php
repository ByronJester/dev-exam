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
        'medicine'
    ];

    protected $appends = [
        'medicine_name'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function getMedicineNameAttribute()
    {
        return $this->medicine->name;
    }
}
