<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PotentialIssue extends Model
{
    //
    public function bu_code_info()
    {
        return $this->hasOne(Code::class,'id','business_unit_id');
    }
    public function userInfo()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function IssueInfo()
    {
        return $this->hasOne(Issue::class,'id','issue_id');
    }
}
