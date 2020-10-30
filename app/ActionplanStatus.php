<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActionplanStatus extends Model
{
    //
    public function statusBy()
    {
        return $this->hasOne(User::class,'id','action_by');
    }
}
