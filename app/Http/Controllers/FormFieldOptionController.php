<?php

namespace App\Http\Controllers;

use App\Models\FormField;
use Illuminate\Http\Request;

class FormFieldOptionController extends Controller
{
    public function store(Request $request, FormField $field)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'nullable|string',
            'parent_id' => 'nullable|uuid'
        ]);

        $option = $field->options()->create([
            'label' => $request->label,
            'value' => $request->value,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Form field option created successfully',
            'data' => $option
        ], 201);
    }
}
