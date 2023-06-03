# Laravel Block IP Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/michaelnabil230/laravel-block-ip.svg?style=flat-square)](https://packagist.org/packages/michaelnabil230/laravel-block-ip)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/michaelnabil230/laravel-block-ip/run-tests?label=tests)](https://github.com/michaelnabil230/laravel-block-ip/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/michaelnabil230/laravel-block-ip/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/michaelnabil230/laravel-block-ip/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/michaelnabil230/laravel-block-ip.svg?style=flat-square)](https://packagist.org/packages/michaelnabil230/laravel-block-ip)

The Laravel Block IP package provides a convenient way to control everything related to website visits using the Laravel framework. It allows you to block specific IP addresses, configure rate limiting, and receive notifications for certain events.

## Installation

You can install the package via Composer by running the following command:

```bash
composer require michaelnabil230/laravel-block-ip
```

After installing the package, you need to publish the package files and run the migrations:

```bash
php artisan block-ip:install
php artisan migrate
```

To publish and run the migrations separately, you can use the following commands:

```bash
php artisan vendor:publish --tag="laravel-block-ip-migrations"
php artisan migrate
```

You can also publish the package's configuration file using the following command:

```bash
php artisan vendor:publish --tag="laravel-block-ip-config"
```

The published configuration file allows you to customize various settings related to block IP functionality, notifications, caching, and more.

## Usage

To configure rate limiting and use the package's functionality, you can add code to your `app/Providers/RouteServiceProvider.php` file. The following code demonstrates how to configure rate limiting and enable the package:

```php
protected function configureRateLimiting()
{
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });

    \MichaelNabil230\BlockIp\BlockIpRegistrar::rateLimiter();
}
```

By default, the rate limiter is set to allow 60 requests per minute, but you can customize this value by passing it to the `rateLimiter()` method.

The package provides a `BlockIpMiddleware` middleware that you can add to your routes in the `app/Http/Kernel.php` file. Make sure to uncomment the necessary line for the `throttle` middleware as well. Here's an example:

```php
protected $middlewareAliases = [
    // ...
    'block-ip' => \MichaelNabil230\BlockIp\Middleware\BlockIpMiddleware::class,
];
```

To protect your routes using the block IP middleware and rate limiting, you can define them as follows:

```php
Route::middleware(['block-ip', 'throttle:block-ip'])->group(function () {
    // Your protected routes here
});
```

If you want to unblock all IP addresses, you can use the following command:

```bash
php artisan block-ip:unblock --all
```

You can also unblock multiple IP addresses by providing them as a comma-separated list:

```bash
php artisan block-ip:unblock --ips=127.0.0.1,127.0.0.2
```

To block new IP addresses, you can use the following command:

```bash
php artisan block-ip:block 127.0.0.1 127.0.0.2
```

## Support

If you find this package useful, you can show your support by contributing financially:

[![](.assets/ko-fi.png)](https://ko-fi.com/michaelnabil230)[![](.assets/buymeacoffee.png)](https://www.buymeacoffee.com/michaelnabil230)[![](.assets/paypal.png)](https://www.paypal.com/paypalme/MichaelNabil23)

## Testing

You can run the package's tests using the following command:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Michael Nabil](https://github.com/MichaelNabil230)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
