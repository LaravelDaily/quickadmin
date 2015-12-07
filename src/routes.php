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
            'prefix'     => config('quickadmin.route'),
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
    Route::get(config('quickadmin.homeRoute'), 'QuickadminController@index');
    Route::group([
        'middleware' => 'role'
    ], function () {
        Route::get(config('quickadmin.route') . '/crud', [
            'as'   => 'crud',
            'uses' => 'QuickadminCrudController@create'
        ]);
        Route::post(config('quickadmin.route') . '/crud', [
            'as'   => 'crud',
            'uses' => 'QuickadminCrudController@insert'
        ]);
    });
});

// @todo move to default routes.php
Route::group(['namespace' => 'App\Http\Controllers'], function () {
    // Point to App\Http\Controllers\UsersController as a resource
    Route::group([
        'middleware' => 'role'
    ], function () {
        resource('users', 'UsersController');
    });
    // Authentication routes...
    Route::get('auth/login', 'Auth\AuthController@getLogin');
    Route::post('auth/login', 'Auth\AuthController@postLogin');
    Route::get('auth/logout', 'Auth\AuthController@getLogout');

    // Registration routes...
    Route::get('auth/register', 'Auth\AuthController@getRegister');
    Route::post('auth/register', 'Auth\AuthController@postRegister');

    // Password reset link request routes...
    Route::get('password/email', 'Auth\PasswordController@getEmail');
    Route::post('password/email', 'Auth\PasswordController@postEmail');

    // Password reset routes...
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');
});