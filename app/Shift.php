<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'team_id', 'start_time', 'break_from','break_to', 'close_time'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
