<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    
}
