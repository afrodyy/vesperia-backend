<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormFieldController extends Controller
{
    public function store(Request $request, Form $form)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,long_text,radio_button,checkbox,select',
            'description' => 'nullable|string',
            'sub_type' => 'nullable|string',
            'parent_id' => 'nullable|exists:form_fields,id'
        ]);

        $field = $form->fields()->create([
            'label' => $request->label,
            'type' => $request->type,
            'description' => $request->description,
            'sub_type' => $request->sub_type,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Form field created successfully',
            'data' => $field
        ], 201);
    }
}
