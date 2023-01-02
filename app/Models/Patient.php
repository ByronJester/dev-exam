<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'consultation_form',
        'display_year',
        'display_date'
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

    public function getDisplayYearAttribute()
    {
        $date = Carbon::parse($this->created_at);

        return $date->year;
    }

    public function getDisplayDateAttribute()
    {
        $date = Carbon::parse($this->created_at);

        return $date->isoFormat('LL'); 
    }

    public function getConsultationFormAttribute()
    {
        $forms = [];
        
        $tbdots = PatientForm::where('patient_id', $this->id)->where('lmp', null)->first();
        $pregnancy = PatientForm::where('patient_id', $this->id)->where('lmp', '!=', null)->first();
        $prenatal = PrenatalForm::where('patient_id', $this->id)->first();
        $postnatal = PostnatalForm::where('patient_id', $this->id)->first();
        $nutrition = NutritionForm::where('patient_id', $this->id)->first();
        $vaccination = VaccinationForm::where('patient_id', $this->id)->first();
        $deworming = DewormingForm::where('patient_id', $this->id)->first();

        if($tbdots) {
            array_push($forms, "TB-Dots Form");
        }

        if($pregnancy) {
            array_push($forms, "Pregnancy Form");
        }

        if($prenatal) {
            array_push($forms, "Prenatal Form");
        }

        if($postnatal) {
            array_push($forms, "Postnatal Form");
        }

        if($nutrition) {
            array_push($forms, "Nutrition Form");
        }

        if($vaccination) {
            array_push($forms, "Vaccination Form");
        }

        if($deworming) {
            array_push($forms, "Deworming Form");
        }

        return implode(", ", $forms);
    }
}
