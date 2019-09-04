<?php

namespace App;


use Laravel\Spark\User as SparkUser;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends SparkUser
{
    use Notifiable, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id',
        'country_code',
        'phone',
        'two_factor_reset_code',
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_zip',
        'billing_country',
        'extra_billing_information',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'uses_two_factor_auth' => 'boolean',
    ];

    public function tasks(){
        return $this->hasMany('App\Task','owner_id');
    }

    public function completedTasks() {
        return $this->hasMany('App\Task','owner_id')->completed();
    }

    public function openTasks(){
        return $this->hasMany('App\Task','owner_id')->open();
    }

    public function companies(){
        return $this->hasMany('App\Company');
    }

    public function favorites(){
        return $this->hasMany('App\Favorite');
    }
}
