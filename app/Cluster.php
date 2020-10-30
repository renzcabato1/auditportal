<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    //
    public function codes_info()
    {
        return $this->hasMany(Code::class);
    }
}
