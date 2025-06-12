<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\FormSubmissionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormSubmissionController extends Controller
{
    public function store(Request $request, Form $form)
    {
        $request->validate([
            'user_identifier' => 'nullable|string|max:255',
            'answers' => 'required|array',
            'answers.*.form_field_id' => 'required|exists:form_fields,id',
            'answers.*.value' => 'nullable'
        ]);

        DB::beginTransaction();

        try {
            $submission = FormSubmission::create([
                'form_id' => $form->id,
                'user_identifier' => $request->user_identifier
            ]);

            foreach ($request->answers as $answer) {
                FormSubmissionAnswer::create([
                    'form_submission_id' => $submission->id,
                    'form_field_id' => $answer['form_field_id'],
                    'value' => is_array($answer['value']) ? json_encode($answer['value']) : $answer['value']
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Form submitted successfully',
                'data' => [
                    'submission_id' => $submission->id
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Submissio failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
