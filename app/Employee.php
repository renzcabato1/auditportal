<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $connection = 'hr_portal';
    protected $visible = array('id', 'user_id', 'last_name', 'first_name', 'middle_name','users');
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
