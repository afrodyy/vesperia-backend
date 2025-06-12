<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormFieldOption extends Model
{
    protected $fillable = [
        'label',
        'value'
    ];

    public function field()
    {
        return $this->belongsTo(FormField::class);
    }
}
