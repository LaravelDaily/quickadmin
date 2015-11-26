<?php

namespace Laraveldaily\Quickadmin;

use Illuminate\Support\ServiceProvider;
use Laraveldaily\Quickadmin\Commands\QuickAdminConfig;
use Laraveldaily\Quickadmin\Commands\QuickAdminInstall;

class QuickadminServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register vendor views
        $this->loadViewsFrom(__DIR__ . '/Views/qa', 'qa');
        $this->loadViewsFrom(__DIR__ . '/Views/templates', 'tpl');
        /* Publish master templates */
        $this->publishes([
            __DIR__ . '/Config/quickadmin.php'                  => config_path('quickadmin.php'),
            __DIR__ . '/Views/admin'                            => base_path('resources/views/admin'),
            __DIR__ . '/Views/auth'                             => base_path('resources/views/auth'),
            __DIR__ . '/Views/emails'                           => base_path('resources/views/emails'),
            __DIR__ . '/Public/quickadmin'                      => base_path('public/quickadmin'),
            __DIR__ . '/Controllers/publish/UsersController'    => app_path('Http/Controllers/UsersController.php'),
            __DIR__ . '/Controllers/publish/Controller'         => app_path('Http/Controllers/Controller.php'),
            __DIR__ . '/Controllers/publish/PasswordController' => app_path('Http/Controllers/Auth/PasswordController.php'),
            __DIR__ . '/Controllers/publish/FileUploadTrait'    => app_path('Http/Controllers/Traits/FileUploadTrait.php'),
            __DIR__ . '/Models/publish/Role'                    => app_path('Role.php'),
        ], 'quickadmin');

        // Register commands
        $this->app->bind('quickadmin:install', function ($app) {
            return new QuickAdminInstall();
        });
        $this->commands([
            'quickadmin:install'
        ]);
        // Routing
        include __DIR__ . '/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Register main classes
        $this->app->make('Laraveldaily\Quickadmin\Controllers\QuickadminController');
        $this->app->make('Laraveldaily\Quickadmin\Controllers\QuickadminCrudController');
        $this->app->make('Laraveldaily\Quickadmin\Cache\QuickCache');
        $this->app->make('Laraveldaily\Quickadmin\Builders\MigrationBuilder');
        $this->app->make('Laraveldaily\Quickadmin\Builders\ModelBuilder');
        $this->app->make('Laraveldaily\Quickadmin\Builders\RequestBuilder');
        $this->app->make('Laraveldaily\Quickadmin\Builders\ControllerBuilder');
        $this->app->make('Laraveldaily\Quickadmin\Builders\ViewsBuilder');
        // Register dependency packages
        $this->app->register('Illuminate\Html\HtmlServiceProvider');
        // Register dependancy aliases
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('HTML', 'Illuminate\Html\HtmlFacade');
        $loader->alias('Form', 'Illuminate\Html\FormFacade');
    }

}