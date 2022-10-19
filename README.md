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
     */

    'notifications' => [

        'channels' => ['mail'],

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

    'cache' => [

        /*
         * By default all block ips are cached for 24 hours to speed up performance.
         * When block ips are updated the cache is flushed automatically.
         */

        'expiration_time' => \DateInterval::createFromDateString('24 hours'),

        /*
         * The cache key used to store all block ips.
         */

        'key' => 'block-ips.cache.',

        /*
         * You may optionally indicate a specific cache driver to use for block ip
         * caching using any of the `store` drivers listed in the cache.php config
         * file. Using 'default' here means to use the `default` set in cache.php.
         */

        'store' => 'default',
    ],

    'webhook_cloud_flare' => [

        /**
         * Enable the webhook cloud flare work when user blocked.
         */
        'enable' => false,

        /**
         * Global API Key on the "My Profile > Api Tokens > API Keys" page.
         */
        'key' => env('CLOUDFLARE_KEY'),

        /**
         * Email address associated with your account.
         */
        'email' => env('CLOUDFLARE_EMAIL'),
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

    \MichaelNabil230\BlockIp\BlockIpRegistrar::rateLimiter();
}
```

By default `maxAttempts` is `60` but if you want to change this number can make that.

```php
\MichaelNabil230\BlockIp\BlockIpRegistrar::rateLimiter(100);
```

## Package Middleware

This package comes with `BlockIpMiddleware` middleware. You can add them inside your `app/Http/Kernel.php` file.

```php
protected $routeMiddleware = [
    // ...
    'block-ip' => \MichaelNabil230\BlockIp\Middleware\BlockIpMiddleware::class,
];
```

Check if this line is uncommented in your `app/Http/Kernel.php` file.

```php
protected $routeMiddleware = [
    // ...
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
];
```

Then you can protect your routes using middleware rules:

```php
Route::middleware(['block-ip', 'throttle:block-ip'])->group(function () {
    //
});
```

If you want to unblock all blocks for IPs can make that when running this command:

```bash
php artisan block-ip:unblock --all
```

Or pluck for IPs

```bash
php artisan block-ip:unblock --ips=127.0.0.1,127.0.0.2
```

If you want to add new IPs for the block can make that when running this command:

```bash
php artisan block-ip:block 127.0.0.1,127.0.0.2
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
