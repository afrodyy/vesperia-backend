<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    public function fields()
    {
        return $this->hasMany(FormField::class);
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
