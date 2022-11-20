<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DewormingForm extends Model
{
    use HasFactory;

    protected $appends = [
        'name', 'description'
    ];

    public function getNameAttribute()
    {
        return 'Deworming Form';
    }

    public function getDescriptionAttribute()
    {
        return 'Deworming Form';
    }
}
