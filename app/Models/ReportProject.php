<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportProject extends Model
{
    use HasFactory;
    protected $table = 'reports';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_project',
        'time_for_project',
        'rate_of_process',
        'status',
        'reason',
        'advantage',
        'disadvantage',
        'suggestion',
        'plan_for_next_day',
        'id_user' ,   
        'id_project',
    ];
}
