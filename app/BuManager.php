<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuManager extends Model
{
    //
    public function employee_info()
    {
        return $this->hasOne(Employee::class,'user_id','manager_id');
    }
}
