<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/send-emails', [EmailController::class, 'sendEmails']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::post('/register', [RegistrationController::class, 'register']);



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
