<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    //
    public function status_report()
    {
        return $this->hasMany(StatusReport::class)->orderBy('id','desc');
    }
    public function attachments_info()
    {
        return $this->hasMany(Attachment::class);
    }
}
