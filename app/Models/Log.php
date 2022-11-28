<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Log extends Model
{
    use HasFactory;

    protected $appends = [
        'date'
    ];

    public function getDateAttribute()
    {
        $date = Carbon::parse($this->created_at);

        return $date->isoFormat('LLL'); 
    }
}
