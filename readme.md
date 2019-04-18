## Current state

This package is not actively maintained and no new features should be expected, extept for bugfixes (mostly by accepting PR) and compatibility with newer Laravel versions as they are released.

Alternatively, check out premium online adminpanel generator version - no packages required there, no syntax to learn, it generates Laravel project for you: [QuickAdminPanel.com](https://quickadminpanel.com)

We've also recently released Vue.js+Laravel version of generator: [Vue.QuickAdminPanel.com](https://vue.quickadminpanel.com)

Finally, see free alternatives in our article on Laravel News: [13 Laravel Admin Panel Generators](https://laravel-news.com/13-laravel-admin-panel-generators)


## Package Requirements
* Laravel `^5.8`

### Laravel 5.7 users info!
To use Quickadmin with Laravel Laravel 5.6 use branch `5.0.0`

### Laravel 5.6 users info!
To use Quickadmin with Laravel Laravel 5.6 use branch `4.0.1`

### Laravel 5.5 users info!
To use Quickadmin with Laravel Laravel 5.5 use branch `3.0.2`

### Laravel 5.4 users info!
To use Quickadmin with Laravel Laravel 5.4 use branch `2.1.1`

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

---

## More from our LaravelDaily Team

- Check out our adminpanel generator QuickAdminPanel: [Laravel version](https://quickadminpanel.com) and [Vue.js version](https://vue.quickadminpanel.com)
- Follow our [Twitter](https://twitter.com/dailylaravel) and [Blog](http://laraveldaily.com/blog)
- Subscribe to our [newsletter with 20+ Laravel links every Thursday](http://laraveldaily.com/weekly-laravel-newsletter/)
- Subscribe to our [YouTube channel Laravel Business](https://www.youtube.com/channel/UCTuplgOBi6tJIlesIboymGA)
