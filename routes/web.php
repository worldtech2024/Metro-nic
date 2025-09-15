<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function () {
    Artisan::call('storage:link');
    Artisan::call('migrate');
});


