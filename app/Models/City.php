<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['state_id', 'name'];

    // relasi data
    public function states()
    {
        return $this->belongsTo(State::class);
    }

    // relasi data ke table employees
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
