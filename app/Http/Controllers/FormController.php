<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Forms fetched successfully',
            'data' => $forms
        ], 200);
    }

    public function show(Form $form)
    {
        $form->load([
            'fields.options' => fn($query) => $query->orderBy('id')
        ]);

        $formStructure = [
            'id' => $form->id,
            'name' => $form->name,
            'fields' => $form->fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'label' => $field->label,
                    'type' => $field->type,
                    'sub_type' => $field->sub_type,
                    'description' => $field->description,
                    'parent_id' => $field->parent_id,
                    'options' => $field->options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'label' => $option->label,
                            'value' => $option->value,
                            'parent_id' => $option->parent_id
                        ];
                    }),
                ];
            })
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Form fetched successfully',
            'data' => $formStructure
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $form = Form::create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Form created successfully',
            'data' => $form
        ], 201);
    }
}
