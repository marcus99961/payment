<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('{any?}', function() {
    return view('welcome');
})->middleware('auth');

Route::get('{any?}', function() {
    return view('welcome');
})->where('any', '.*')->middleware('auth');
//Route::view('/profile/edit', 'profile.edit')->middleware('auth');
//Route::view('/profile/password', 'profile.password')->middleware('auth');
