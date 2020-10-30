<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadProof extends Model
{
    //
    public function attachment()
    {
        return $this->hasMany(Attachment::class);
    }
}
