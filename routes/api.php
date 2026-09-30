<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\VisitController;

Route::post('/login', [AuthController::class, 'login'])->middleware('decrypt.payload');


Route::get('/patients', [PatientController::class, 'index'])->middleware('auth:sanctum');
Route::post('/patients', [PatientController::class, 'store'])->middleware(['auth:sanctum', 'decrypt.payload']);
Route::get('/patients/{medicalRecordNumber}', [PatientController::class, 'show'])->middleware('auth:sanctum');
Route::get('/visits', [VisitController::class, 'index'])->middleware('auth:sanctum');
Route::post('/visits', [VisitController::class, 'store'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::get('/health', fn() => response()->json(['success' => true, 'message' => 'API is healthy']));


