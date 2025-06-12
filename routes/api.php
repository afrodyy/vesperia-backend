<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\FormFieldController;
use App\Http\Controllers\FormFieldOptionController;
use App\Http\Controllers\FormSubmissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('forms')->group(function () {
    Route::post('/', [FormController::class, 'store']);
    Route::get('{form}', [FormController::class, 'show']);
    Route::post('{form}/fields', [FormFieldController::class, 'store']);
    Route::post('{form}/submit', [FormSubmissionController::class, 'store']);
});

Route::post('/fields/{field}/options', [FormFieldOptionController::class, 'store']);
Route::get('/submissions', [FormSubmissionController::class, 'index']);
Route::get('/submissions/{submission}', [FormSubmissionController::class, 'show']);
