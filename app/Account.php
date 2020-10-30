<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //
    public function employee_info()
    {
        return $this->hasOne(Employee::class,'user_id','user_id')->orderBy('first_name','asc');
    }
    public function audit_info()
    {
        return $this->hasOne(AuditTeam::class,'id','team_id');
    }
    public function manage_user()
    {
        return $this->hasMany(ManageUser::class,'approver_id','user_id');
    }
    public function issues_info()
    {
        return $this->hasMany(IssueBusinessUnit::class,'owner_id','user_id');
    }
    
    
}
