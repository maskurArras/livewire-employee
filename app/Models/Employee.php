<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'address',
        'department_id',
        'country_id',
        'state_id',
        'city_id',
        'zip_code',
        'birthdate',
        'date_hired'

    ];

    // relasi data dari country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // relasi data dari department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // relasi data dari state
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    // relasi data dari cities
    public function cities()
    {
        return $this->belongsTo(City::class);
    }
}
