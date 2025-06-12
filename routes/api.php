<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\FormFieldController;
use App\Http\Controllers\FormFieldOptionController;
use App\Http\Controllers\FormSubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/forms/{form}', [FormController::class, 'show']);
Route::post('/forms', [FormController::class, 'store']);
Route::post('/forms/{form}/fields', [FormFieldController::class, 'store']);
Route::post('/fields/{field}/options', [FormFieldOptionController::class, 'store']);
Route::post('/forms/{form}/submit', [FormSubmissionController::class, 'store']);
