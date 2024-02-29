<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//aqui van las rutas de la api
//si una ruta no existe utilizo el metodo fallback
Route::fallback(function(){
   return response()->json(['mensaje'=>'ruta no encontrada'],404);
});
Route::apiResource("alumnos",\App\Http\Controllers\AlumnoController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
