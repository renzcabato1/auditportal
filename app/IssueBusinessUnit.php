<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IssueBusinessUnit extends Model
{
    //
        
    public function buCodeInfo()
    {
        return $this->hasOne(Code::class,'id','code_id');
    }
    public function closedBy()
    {
        return $this->hasOne(User::class,'id','closed_by');
    }
    public function ownerInfo()
    {
        return $this->hasOne(User::class,'id','owner_id');
    }
    public function roleInfo()
    {
        return $this->hasOne(Role::class,'id','role_id');
    }
    public function ratingInfo()
    {
        return $this->hasOne(Rating::class,'id','rating_id');
    }
    public function buHeadInfo()
    {
        return $this->hasOne(Employee::class,'user_id','bu_head');
    }
    public function clusterHeadInfo()
    {
        return $this->hasOne(Employee::class,'user_id','cluster_id');
    }
    public function issueInfo()
    {
        return $this->belongsTo(Issue::class,'issue_id','id');
    }
    public function actiontakenInfo()
    {
        return $this->hasMany(Actionplan::class);
    }
    public function action_plan_not_due()
    {
        return $this->hasMany(Actionplan::class);
    }
    public function action_plan_due()
    {
        return $this->hasMany(Actionplan::class);
    }
    
}
