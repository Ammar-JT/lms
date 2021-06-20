<?php

namespace App\Models;

use App\Entities\Learning;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Learning;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'confirm_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function isConfirmed(){
        return $this->confirm_token == null;
    }

    public function confirm(){
        $this->confirm_token = null;
        $this->save();
    }


    public function isAdmin(){
        /*
        if($this->email === config('lms.administrators')){
            return true;
        }
        return false;
        */

        return in_array($this->email, config('lms.administrators'));
    }

    public function getRouteKeyName(){
        return 'username';
    }


    
}
