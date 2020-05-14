<?php

use Illuminate\Support\Facades\Route;
//cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pruebas/{nombre?}', function($nombre=null){
	
	$texto='<h2>Texto desde eurata </h2>';
	$texto .= 'Nombre '.$nombre;

	return view('pruebas', array(
		'texto' => $texto
	));
});


Route::get('animales', 'PruebasController@index');
Route::get('test-orm', 'PruebasController@testOrm');

//Rutas del api
	//Rutas de prubea
Route::get('/caja/pruebas', 'CajaController@pruebas');
Route::get('/dinero-diario/pruebas', 'DineroController@pruebas');
Route::get('/inventario/pruebas', 'InventarioController@pruebas');
Route::get('/usuario/pruebas', 'UserController@pruebas');
Route::get('/ventas/pruebas', 'VentasController@pruebas');



//Rutas del controlador de usuarios
Route::post('/api/register', 'UserController@register');
Route::post('/api/login', 'UserController@login');
Route::put('/api/user/update', 'UserController@update');
Route::post('/api/user/upload', 'UserController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('/api/user/avatar/{filename}', 'UserController@getImage');

