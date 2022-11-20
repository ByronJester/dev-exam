<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostnatalForm extends Model
{
    use HasFactory;

    protected $appends = [
        'name', 'description'
    ];

    public function getNameAttribute()
    {
        return 'Postnatal Registration Form';
    }

    public function getDescriptionAttribute()
    {
        return 'Postnatal Registration Form';
    }
}
