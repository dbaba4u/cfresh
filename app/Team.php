<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['name','unit','employee_id' ];

    public function employees()
    {
        $this->hasMany(Employee::class);
    }
}
