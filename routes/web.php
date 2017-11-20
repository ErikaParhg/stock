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

Route::get('/', function () {
    return redirect('/home');
});

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/dashboard', 'AdministratorController@index');

Route::get('/user', 'UserController@index');
Route::post('/user/register', 'UserController@store');
Route::post('/user/update', 'UserController@update');
Route::post('/user/disable', 'UserController@disable');
Route::post('/user/reactivate', 'UserController@reactivate');

Route::get('/supplier', 'SupplierController@index');
Route::post('/supplier/insert', 'SupplierController@store');
Route::post('/supplier/edit', 'SupplierController@edit');
Route::get('/supplier/search', 'SupplierController@search');
Route::post('/supplier/delete', 'SupplierController@deleteIt');

Route::get('/product', 'ProductController@index');
Route::get('/product/supplier/{id}', 'ProductController@listSupplier');
Route::get('/product/search', 'ProductController@search');
Route::post('/product/insert', 'ProductController@store');
Route::post('/product/edit', 'ProductController@edit');
Route::post('/product/debit', 'ProductController@debit');