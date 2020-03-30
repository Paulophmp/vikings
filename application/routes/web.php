<?php

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


Route::get('cartorio', 'CartorioController@index');
Route::post('/cartorio/cadastrar', 'CartorioController@store');
Route::post('/cartorio/cadastrar-xml', 'CartorioController@storeXML');
Route::get('/cartorio/download', 'CartorioController@export');
Route::delete('/cartorio/delete/{id}', 'CartorioController@destroy');
Route::put('/cartorio/edit/{id}', 'CartorioController@edit');

#docker exec php-teste-vikings /bin/bash -c 'php artisan migrate:fresh --seed'
