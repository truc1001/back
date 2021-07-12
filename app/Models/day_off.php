<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class day_off extends Model
{
    use HasFactory;
    protected $table = 'day_off';
    // protected $primaryKey = 'id';
    // public function User(){
    //     return $this->belongsTo('App\Models\User','user_id','id');
    // }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'off_reason',
        'status',
        'admin_id',
        'start_off',
        'num_off',
        'approved_at'
    ];
}
