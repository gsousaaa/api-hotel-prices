<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/signIn', [AuthController::class, 'signIn']);
Route::post('/signUp', [AuthController::class, 'signUp']);
