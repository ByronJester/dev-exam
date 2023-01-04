<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'image',
        'phone',
        'email',
        'username',
        'password',
        'user_type',
        'role',
        'is_active',
        'work_address'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'place'
    ];

    public function getPlaceAttribute()
    {
        $place = Place::where('id', $this->work_address)->first();

        if($place) {
            return $place->name;
        }

        return null;
    }

    public function getMiddleNameAttribute($value)
    {
        if($value == 'null' || $value == 'Null' || $value == 'undefined'){
            return '';
        }

        return $value;
    }

}
