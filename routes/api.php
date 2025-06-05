<?php

use App\Http\Controllers\ApiController;

Route::middleware(['auth.jwt'])->post('/rooms/{id}/price', [ApiController::class, 'calculatePriceForecast']);

Route::middleware(['auth.jwt'])->get('/rooms', [ApiController::class, 'getRooms']);
