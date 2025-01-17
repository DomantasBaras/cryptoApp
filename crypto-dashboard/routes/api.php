<?php
use App\Http\Controllers\API\CryptoController;

Route::get('/crypto', [CryptoController::class, 'index']);