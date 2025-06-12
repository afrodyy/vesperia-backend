<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmissionAnswer extends Model
{
    public function field()
    {
        return $this->belongsTo(FormField::class);
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
