## Quick Admin installation
1. Install the package via `composer require quickadmin/quickadmin`.
2. Add `Laraveldaily\Quickadmin\QuickadminServiceProvider::class,` to your `\config\app.php` providers.
3. Run `php artisan quickadmin:install` and fill the required information.
4. Access QuickAdmin panel by visiting `http://yourdomain/qa`.