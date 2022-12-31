<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'user_id',
        'name',
        'phone', 
        'age',
        'gender',
        'is_rhu'
    ];

    protected $with = [
        'place',
        'user'
    ];

    protected $appends = [
        'barangay',
        'consultation_form'
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBarangayAttribute()
    {
        return $this->place->name;
    }

    public function getConsultationFormAttribute()
    {
        $forms = '';
        
        $tbdots = PatientForm::where('patient_id', $this->id)->where('lmp', null)->first();
        $pregnancy = PatientForm::where('patient_id', $this->id)->where('lmp', '!=', null)->first();

        if($tbdots) {
            $forms = $forms . 'TB-Dots Form';
        }

        if($pregnancy) {
            $forms = $forms . 'Pregnancy Form';
        }

        return $forms;
    }
}
