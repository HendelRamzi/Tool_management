<?php

namespace App\Models;

use App\Observers\PersonalObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Str;


#[ObservedBy([PersonalObserver::class])]
class Personal extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'birthday',
    ];


    public function fullName(): Attribute
    {
        return Attribute::get(function () {
            return $this->first_name . ' ' . $this->last_name;
        });
    }



    public function getLastNameAttribute()
    {
        return Str::ucfirst($this->attributes['last_name']);
    }
    public function getFirstNameAttribute()
    {
        return Str::ucfirst($this->attributes['first_name']);
    }


    public function personal()
    {
        return $this->morphTo();
    }
}
