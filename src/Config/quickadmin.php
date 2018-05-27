<?php
/**
 * Laraveldaily/Quickadmin package configuration file.
 */
return [

    /**
     * Datepicker configuration:
     */
    'date_format'        => 'Y-m-d',
    'date_format_jquery' => 'yy-mm-dd',
    'time_format'        => 'H:i:s',
    'time_format_jquery' => 'HH:mm:ss',

    /**
     * Quickadmin settings
     */
    // Default route
    'route'              => 'admin',
    // Default home route
    'homeRoute'          => 'admin',

    //Default home action
    // 'homeAction' => '\App\Http\Controllers\MyOwnController@index',
    // Default role to access users and CRUD
    'defaultRole'        => 1,

    // If set to true, you'll need to copy the routes
    // from vendor/laraveldaily/quickadmin/src/routes.php into your own /routes folder
    'standaloneRoutes'   => false,

    // Used to define relationship with UserLogs
    'userModel' => \App\User::class

];
