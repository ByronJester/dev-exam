<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MedicineStock extends Model
{
    use HasFactory; 

    protected $fillable = [
        'medicine_id',
        'quantity',
        'medicine_category_id',
        'medicine_unit_id',
        'dosage'
    ];

    protected $appends = [
        'name',
        'category',
        'unit',
        'date',
        'deducted_quantity'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function getNameAttribute()
    {
        return $this->medicine->name;
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
    

    public function getDeductedQuantityAttribute()
    {
        return $this->quantity;
    }
}
