<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\FormSubmissionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormSubmissionController extends Controller
{
    private function decodeValue($value)
    {
        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        if (json_last_error() === JSON_ERROR_NONE && is_string($decoded)) {
            return $decoded;
        }

        return $value;
    }

    public function show(FormSubmission $submission)
    {
        $submission->load([
            'form',
            'answers.field'
        ]);

        $data = [
            'id' => $submission->id,
            'form' => [
                'id' => $submission->form->id,
                'name' => $submission->form->name
            ],
            'user_identifier' => $submission->user_identifier,
            'submitted_at' => $submission->created_at,
            'answers' => $submission->answers->map(function ($answer) {
                return [
                    'field_id' => $answer->form_field_id,
                    'field_label' => $answer->field->label,
                    'field_type' => $answer->field->type,
                    'value' => $this->decodeValue($answer->value)
                ];
            })
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Submission fetched successfully',
            'data' => $data
        ], 200);
    }

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
