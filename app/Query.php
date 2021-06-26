<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $fillable = [
        'employee_id', 'query','admin_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

}
