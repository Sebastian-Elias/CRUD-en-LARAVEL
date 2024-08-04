<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

//Instrucción para automatizar el acceso a todas las url
Route::resource('empleado',EmpleadoController::class)->middleware('auth');

//Quitar opciones de registro y recuperar contraseña por seguridad
Auth::routes(['register'=>false,'reset'=>false]);

//
Route::get('/home', [EmpleadoController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [EmpleadoController::class, 'index'])->name('home');
});