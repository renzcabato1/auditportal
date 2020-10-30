<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actionplan extends Model
{
    //
    public function actionStatus()
    {
        return $this->hasOne(ActionplanStatus::class)->orderBy('id','desc');
    }
    public function uploadProofDetails()
    {
        return $this->hasOne(UploadProof::class);
    }
    public function issueBusinessUnit()
    {
        return $this->belongsTo(IssueBusinessUnit::class,'issue_business_unit_id','id');
    }
    public function auditBy()
    {
        return $this->hasOne(User::class,'id','audit_by');
    }
}
