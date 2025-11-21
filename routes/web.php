<?php

use App\Http\Controllers\Dht22Controller;
use App\Http\Controllers\SmartHomeController;
use App\Models\Dht22;
use App\Models\SmartHome;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/update-data/{tmp}/{hmd}', [Dht22Controller::class, 'updateData']);
Route::get('/get-data', [Dht22Controller::class, 'getData']);
Route::get('/get-latest', [Dht22Controller::class, 'getLatest']);

Route::get('smart-home', [SmartHomeController::class, 'index']);
Route::get('/test', function () {
    SmartHome::where('id', 1)->update(['status' => 1]);
});
Route::post('/smart-home/update-status/{id}', [SmartHomeController::class, 'updateStatus'])
    ->name('smart-home.update-status');
Route::post('/smart-home/update', [SmartHomeController::class, 'update']);
