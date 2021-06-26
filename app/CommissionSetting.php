<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommissionSetting extends Model
{
    protected $fillable = ['factor', 'customer_id','sales_reps','employee_id','expire_date','comment','status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
