<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PatientMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medicine_id',
        'quantity',
        'is_individual',
        'medicine_category_id',
        'medicine_unit_id',
        'dosage'
    ];

    protected $with = [
        'medicine',
        'patient'
    ];

    protected $appends = [
        'name',
        'barangay',
        'patient_name',
        'category',
        'unit',
        'date',
        'place_name'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'medicine_category_id');
    }


    public function unit()
    {
        return $this->belongsTo(MedicineUnit::class, 'medicine_unit_id');
    }



    public function getNameAttribute()
    {
        return $this->medicine->name;
    }

    public function getPatientNameAttribute()
    {
        return $this->patient->name;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getBarangayAttribute()
    {
        return $this->patient->place->name;
    }

    public function getPlaceNameAttribute()
    {
        return $this->patient->place->name;
    }

    public function getCategoryAttribute()
    {
        $category = MedicineCategory::where('id', $this->medicine_category_id)->first();

        return $category->name;
    }

    public function getUnitAttribute()
    {
        $unit = MedicineUnit::where('id', $this->medicine_unit_id)->first();

        return $unit->name;
    }

    public function getDateAttribute()
    {
        $date = Carbon::parse($this->created_at);

        return $date->isoFormat('LL'); 
    }
}
