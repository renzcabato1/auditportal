<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Employee;
use App\Account;

use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    protected $connection = 'hr_portal';
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function employee_info()
    {
        return Employee::where('user_id', Auth::user()->id)->get()->first();
    }
    public function team_id()
    {
        return Account::where('user_id',Auth::user()->id)->first();
    }
}
