<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class skillOfStaff extends Model
{
    use HasFactory;

    protected $table = 'skillOfStaff';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user',
        'programming_skills',
        'language_skills',
        'degree',

    ];

    
}
