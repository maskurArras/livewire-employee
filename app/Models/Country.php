<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['country_code', 'name'];

    // relasi data ke table state
    public function states()
    {
        return $this->hasMany(State::class);
    }

    // relasi data ke table employees
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
