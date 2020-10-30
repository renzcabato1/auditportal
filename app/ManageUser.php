<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManageUser extends Model
{
    //
    public function info_of_employee()
    {
        return $this->hasOne(Employee::class,'user_id','user_id');
    }
    public function approver_info()
    {
        return $this->hasOne(Employee::class,'user_id','approver_id');
    }
    public function account_id()
    {
        return $this->hasOne(Account::class,'user_id','user_id');
    }
    
}
