<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashBalance extends Model
{
    protected $fillable = [
        'cash_at_hand',
        'daily_expense',
        'balance',
        'cash_date',
        'description',
        'status',
        'new_balance',
        'question'
    ];
}
