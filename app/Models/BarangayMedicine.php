<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BarangayMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'medicine_id',
        'quantity'
    ];

    protected $with = [
        'medicine',
        'place'
    ];

    protected $appends = [
        'name',
        'place_name',
        'dispensed'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function getNameAttribute()
    {
        return $this->medicine->name;
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function getPlaceNameAttribute()
    {
        return $this->place->name;
    }

    public function getDispensedAttribute()
    {
        $place = $this->place_id;

        $patientMedicine = PatientMedicine::where('medicine_id', $this->medicine_id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereHas('patient', function($q) use ($place){
                $q->where('place_id', $place);
            })->get();

        return $patientMedicine->sum('quantity');
    }
}
