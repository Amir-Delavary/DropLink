<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::apiResource('files', FileController::class);
Route::get('download/{file}', [FileController::class, 'download'])->middleware('signed')
->name('download');
