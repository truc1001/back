<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workingtime extends Model
{
    use HasFactory;
    protected $table = 'workingtime';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'check_ in',
        'check_out',
        'work',
        'note',
        'id_user',  
        'id_project',   
    ];
}
