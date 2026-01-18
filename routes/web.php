<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    dd(config('dau'));


    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
