## New
Check out online adminpanel generator version - no packages required there, no syntax to learn, it generates Laravel project for you: [QuickAdminPanel.com](https://quickadminpanel.com)


## Package Requirements
* Laravel `^5.4`

### Laravel 5.3 users info!
To use Quickadmin with Laravel Laravel 5.3 use branch `2.0.x`

### Laravel 5.2 users info!
To use Quickadmin with Laravel Laravel 5.2 use branch `1.x.x`

### Laravel 5.1.11 users info!
To use Quickadmin with Laravel Laravel 5.1.11 use branch `0.4.x`

## QuickAdmin installation

### Please note: QuickAdmin requires fresh Laravel installation and is not suitable for use on already existing project.

1. Install the package via `composer require laraveldaily/quickadmin`.
2. Add `Laraveldaily\Quickadmin\QuickadminServiceProvider::class,` to your `\config\app.php` providers **after `App\Providers\RouteServiceProvider::class,`** otherwise you will not be able to add new ones to freshly generated controllers.
3. Configure your .env file with correct database information
4. Run `php artisan quickadmin:install` and fill the required information.
5. Register middleware `'role'       => \Laraveldaily\Quickadmin\Middleware\HasPermissions::class,` in your `App\Http\Kernel.php` at `$routeMiddleware`
6. Access QuickAdmin panel by visiting `http://yourdomain/admin`.

## More information and detailed description
[http://laraveldaily.com/packages/quickadmin/](http://laraveldaily.com/packages/quickadmin/)

## License
The MIT License (MIT). Please see [License File](license.md) for more information.