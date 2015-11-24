<?php

/**
 * Package routing file specifies all of this package routes.
 */

use Illuminate\Support\Facades\View;
use Laraveldaily\Quickadmin\Models\Crud;

if (Schema::hasTable('cruds')) {
    $cruds = Crud::where('is_crud', 1)->orderBy('position')->get();
    View::share('cruds', $cruds);
    if (!empty($cruds)) {
        Route::group([
            'middleware' => ['auth', 'role'],
            'prefix'     => 'admin',
            'namespace'  => 'App\Http\Controllers',
        ], function () use ($cruds) {
            foreach ($cruds as $crud) {
                resource(strtolower($crud->name), 'Admin\\' . ucfirst(camel_case($crud->name)) . 'Controller');
            }
        });
    }
}

Route::group([
    'namespace'  => 'Laraveldaily\Quickadmin\Controllers',
    'middleware' => 'auth'
], function () {
    // Dashboard home page route
    Route::get('qa', 'QuickadminController@index');
    Route::get('crud', 'QuickadminCrudController@create');
    Route::post('crud', 'QuickadminCrudController@insert');
});

// @todo rethink in v2
Route::group(['namespace' => 'App\Http\Controllers'], function () {
    // Point to App\Http\Controllers\UsersController as a resource
    resource('users', 'UsersController');
    // Authentication routes...
    Route::get('auth/login', 'Auth\AuthController@getLogin');
    Route::post('auth/login', 'Auth\AuthController@postLogin');
    Route::get('auth/logout', 'Auth\AuthController@getLogout');

    // Registration routes...
    Route::get('auth/register', 'Auth\AuthController@getRegister');
    Route::post('auth/register', 'Auth\AuthController@postRegister');
});