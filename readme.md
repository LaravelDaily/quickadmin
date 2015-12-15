## Requirements
* Laravel `^5.1.11` - Because of ACL authorization engine: http://laravel.com/docs/5.1/authorization#introduction

## Quick Admin installation
1. Install the package via `composer require laraveldaily/quickadmin`.
2. Add `Laraveldaily\Quickadmin\QuickadminServiceProvider::class,` to your `\config\app.php` providers.
3. Run `php artisan quickadmin:install` and fill the required information.
4. Register middleware `'role'       => \Laraveldaily\Quickadmin\Middleware\HasPermissions::class,` in your `App\Http\Kernel.php` at `$routeMiddleware`
5. Access QuickAdmin panel by visiting `http://yourdomain/admin`.

## More information and detailed description
[http://laraveldaily.com/packages/quickadmin/](http://laraveldaily.com/packages/quickadmin/)

## License
The MIT License (MIT). Please see [License File](license.md) for more information.
