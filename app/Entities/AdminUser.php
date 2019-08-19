<?php

namespace App\Entities;

use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use DesignMyNight\Mongodb\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use SoftDeletes, Notifiable;

    protected $dates = ['deleted_at', 'updated_at', 'created_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function findForPassport($login)
    {
        return $this->orWhere('email', $login)->orWhere('phone', $login)->first();
    }

    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param  string $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->password);
    }
}
