<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PostnatalForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'patient_id',
        'hospital_name',
        'clients_name',
        'address',
        'occupation',
        'type_of_delivery',
        'mode_of_delivery',
        'total_hours_of_labor',
        'postnatal_day',
        'register_no',
        'doa',
        'religion',
        'gestational_age',
        'lmp',
        'new_born_sex',
        'days_of_hospital_stay',
        'age',
        'education',
        'obstetrical_score',
        'chief_complain',
        'edd',
        'newborn_weight',
        'bad_habits',
        'elimination_pattern',
        'activity_and_exercise',
        'menarche_age',
        'period',
        'nutritional_pattern',
        'sleeping_pattern',
        'cycle',
        'amount_of_blood_loss',
        'duration_of_marriage',
        'family_planning_method_adopted',
        'diseases',
        'genitics_condition',
        'gravidity',
        'parity',
        'miscarriages',
        'terminations',
        'previous_pregnancy',
        'length_of_pregnancy',
        'induction'
    ];

    protected $appends = [
        'name', 'description', 'date_issued', 'display_year'
    ];

    public function getNameAttribute()
    {
        return 'Postnatal Registration Form';
    }

    public function getDescriptionAttribute()
    {
        return 'Postnatal Registration Form';
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
