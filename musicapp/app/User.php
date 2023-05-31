<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    const PENDING = 0;
    const ACTIVE = 1;
    const INACTIVE = 2;

    public static $status = [
        0 => 'Pending',
        1 => 'Active',
        2 => 'Deactive',        
    ];
    public static $role = [
        1 => 'Admin',
        2 => 'Dealer',
        3 => 'Customer',
    ];
    public static $roleType = [
        2 => 'Dealer',
        3 => 'Customer',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone',
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
        'email_verified_at',
        'remember_token',
        'referral_code',
        'referral_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
