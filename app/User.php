<?php

namespace App;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    
    public function orders()
    {
        return $this->hasMany('App\Order', 'user_id', 'id');
    }


    public function userType()
    {
        return $this->belongsTo('App\UserType', 'user_type_id', 'id');
    }

    public function isAdmin()
    {
        dd($this->userType->name);
        return $this->userType->name == 'admin';
    }

    public function isSupplier()
    {
        return $this->userType->name == 'supplier';
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
