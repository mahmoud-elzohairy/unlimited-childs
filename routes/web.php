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

Route::get('/', 'CategoriesController@getParentCategories');
Route::get('/get-sub-categories', 'CategoriesController@getSubCategories');
Route::get('/create', 'CategoriesController@create');
Route::post('/store', 'CategoriesController@store');
Route::get('/edit/{id}', 'CategoriesController@edit');
Route::post('/update/{id}', 'CategoriesController@update');
Route::delete('/destroy/{id}', 'CategoriesController@destroy');