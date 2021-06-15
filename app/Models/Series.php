<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    //by this, you remove mass assignment protection, cuz it's not dangorous and has not cretical data: 
    protected $guarded = [];
    
}
