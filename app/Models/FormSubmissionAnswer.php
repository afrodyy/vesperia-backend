<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmissionAnswer extends Model
{
    protected $fillable = ['form_submission_id', 'form_field_id', 'value'];

    public function field()
    {
        return $this->belongsTo(FormField::class);
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
