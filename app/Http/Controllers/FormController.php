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
