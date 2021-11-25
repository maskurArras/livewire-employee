<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['country_id', 'name'];
    // relasi data
    public function country()
    {
        // relasi data
        return $this->belongsTo(Country::class);
    }

    // relasi data ke table cities
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    // relasi data ke table employees
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
