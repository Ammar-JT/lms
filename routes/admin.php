<?php

use Illuminate\Support\Facades\Route;


Route::resource('series', App\Http\Controllers\Admin\SeriesController::class);
Route::resource('{series_by_id}/lessons',App\Http\Controllers\Admin\LessonsController::class);
