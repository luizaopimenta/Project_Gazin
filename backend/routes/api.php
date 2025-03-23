<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\DesenvolvedorController;

Route::get('niveis', [NivelController::class, 'index']);
Route::post('niveis', [NivelController::class, 'store']);
Route::put('niveis/{nivel}', [NivelController::class, 'update']);
Route::delete('niveis/{nivel}', [NivelController::class, 'destroy']);

Route::get('desenvolvedores', [DesenvolvedorController::class, 'index']);
Route::post('desenvolvedores', [DesenvolvedorController::class, 'store']);
Route::put('desenvolvedores/{desenvolvedor}', [DesenvolvedorController::class, 'update']);
Route::delete('desenvolvedores/{desenvolvedor}', [DesenvolvedorController::class, 'destroy']);
