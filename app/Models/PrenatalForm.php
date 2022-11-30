<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrenatalForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'patient_id',
        'date_today',
        'provider_name',
        'npi',
        'billing_id',
        'tax_id',
        'provider_address',
        'city',
        'town',
        'zip',
        'provider_phone',
        'provider_fax',
        'member_name',
        'member_id',
        'dob',
        'member_address',
        'member_phone',
        'member_city',
        'member_town',
        'member_zip',
        'lmp',
        'g',
        'p',
        'edd',
        'first_prenatal',
        'recent_prenatal',
        'type_of_last_delivery',
        'last_delivery',
        'delivery_facility',
        'behavioral_risks',
        'pyschological_risks',
        'medical_risks',
        'obstetrics_risks',
        'ob_gyn',
        'date'
    ];

    protected $appends = [
        'name', 'description'
    ];

    public function getNameAttribute()
    {
        return 'Prenatal Registration Form';
    }

    public function getDescriptionAttribute()
    {
        return 'Prenatal Registration Form';
    }

    public function getBehavioralRisksAttribute($value)
    {
        if(!$value) return [];

        return json_decode($value);
    }

    public function getPyschologicalRisksAttribute($value)
    {
        if(!$value) return [];

        return json_decode($value);
    }

    public function getMedicalRisksAttribute($value)
    {
        if(!$value) return [];

        return json_decode($value);
    }

    public function getObstetricsRisksAttribute($value)
    {
        if(!$value) return [];

        return json_decode($value);
    }
}
