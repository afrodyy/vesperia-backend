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

    public function index()
    {
        $submissions = FormSubmission::with('form')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Form Submissions fecthed successfully',
            'data' => $submissions
        ]);
    }

    public function show(FormSubmission $submission)
    {
        $submission->load([
            'form.fields.options',
            'answers.field'
        ]);

        $data = [
            'id' => $submission->id,
            'form' => [
                'id' => $submission->form->id,
                'name' => $submission->form->name,
                'fields' => $submission->form->fields->map(function ($field) {
                    return [
                        'id' => $field->id,
                        'label' => $field->label,
                        'type' => $field->type,
                        'sub_type' => $field->sub_type,
                        'description' => $field->description,
                        'parent_id' => $field->parent_id,
                        'options' => $field->options->map(function ($opt) {
                            return [
                                'id' => $opt->id,
                                'label' => $opt->label,
                                'value' => $opt->value,
                                'parent_id' => $opt->parent_id,
                            ];
                        })
                    ];
                })
            ],
            'user_identifier' => $submission->user_identifier,
            'created_at' => $submission->created_at,
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
        $validated = $request->validate([
            'user_identifier' => 'nullable|string|max:255',
            'answers' => 'required|array',
            'answers.*.form_field_id' => 'required',
            'answers.*.value' => 'nullable'
        ]);

        $fieldIds = collect($validated['answers'])->pluck('form_field_id')->unique();
        $validFieldIds = $form->fields()->whereIn('id', $fieldIds)->pluck('id')->toArray();

        foreach ($fieldIds as $id) {
            if (!in_array($id, $validFieldIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Form invalid'
                ], 422);
            }
        }

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
