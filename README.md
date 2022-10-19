# This package is for controlling everything related to website visits using the Laravel framework

[![Latest Version on Packagist](https://img.shields.io/packagist/v/michaelnabil230/laravel-block-ip.svg?style=flat-square)](https://packagist.org/packages/michaelnabil230/laravel-block-ip)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/michaelnabil230/laravel-block-ip/run-tests?label=tests)](https://github.com/michaelnabil230/laravel-block-ip/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/michaelnabil230/laravel-block-ip/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/michaelnabil230/laravel-block-ip/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/michaelnabil230/laravel-block-ip.svg?style=flat-square)](https://packagist.org/packages/michaelnabil230/laravel-block-ip)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require michaelnabil230/laravel-block-ip
```

You can publish all files and run the migrations with:

```bash
php artisan block-ip:install
php artisan migrate
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-block-ip-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-block-ip-config"
```

This is the contents of the published config file:

```php
return [
    /*
     * Truest ips for you
     */
    'truest_ips' => [
        '127.0.0.1',
    ],

    /*
     * You can get notified when specific events occur. Out of the box you can use 'mail' and 'slack'.
     * For Slack you need to install laravel/slack-notification-channel.
     *
     * You can also use your own notification classes, just make sure the class is named after one of
     * the `MichaelNabil230\BlockIp\Notifications\Notifications` classes.
     */
    'notifications' => [

        'notifications' => [
            \MichaelNabil230\BlockIp\Notifications\Notifications\BlockIpNotification::class => ['mail'],
        ],

        /*
         * Here you can specify the notifiable to which the notifications should be sent. The default
         * notifiable will use the variables specified in this config file.
         */
        'notifiable' => \MichaelNabil230\BlockIp\Notifications\Notifiable::class,

        'mail' => [
            'to' => 'your@example.com',

            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Example'),
            ],
        ],

        'slack' => [
            'webhook_url' => '',

            /*
             * If this is set to null the default channel of the webhook will be used.
             */
            'channel' => null,

            'username' => null,

            'icon' => null,

        ],

        'discord' => [
            'webhook_url' => '',

            /*
             * If this is an empty string, the name field on the webhook will be used.
             */
            'username' => '',

            /*
             * If this is an empty string, the avatar on the webhook will be used.
             */
            'avatar_url' => '',
        ],
    ],
];
```

## Usage

You can add them inside your `app/Providers/RouteServiceProvider.php` file.

```php
protected function configureRateLimiting()
{
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });

    \MichaelNabil230\BlockIp\BlockIp::rateLimiter();
}
```

## Package Middleware

This package comes with `BlockIpMiddleware` middleware. You can add them inside your `app/Http/Kernel.php` file.

```php
protected $routeMiddleware = [
    // ...
    'block_ip' => \MichaelNabil230\BlockIp\Middlewares\BlockIpMiddleware::class,
];
```

Then you can protect your routes using middleware rules:

```php
Route::middleware(['block_ip'])->group(function () {
    //
});
```

If you want to unlock all blocks for IPs can make that when running this command:

```bash
php artisan block-ip:unlock
```

If you want to add new IPs for the block can make that when running this command:

```bash
php artisan block-ip:add-ips 127.0.0.1,127.0.0.2
```

## Support

[![](.assets/ko-fi.png)](https://ko-fi.com/michaelnabil230)[![](.assets/buymeacoffee.png)](https://www.buymeacoffee.com/michaelnabil230)[![](.assets/paypal.png)](https://www.paypal.com/paypalme/MichaelNabil23)

## Testing

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
