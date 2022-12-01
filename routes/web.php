<?php

Route::get('/', function () {
    return view('welcome');
});


Route::get('/token', 'ApiBBController@token')->name('token');
Route::get('/registrar', 'ApiBBController@registrar')->name('registrar');
Route::get('/listar', 'ApiBBController@listar')->name('listar');
Route::get('/consultar', 'ApiBBController@consultar')->name('consultar');
Route::get('/baixar', 'ApiBBController@baixar')->name('baixar');
Route::get('/atualizar', 'ApiBBController@atualizar')->name('atualizar');
