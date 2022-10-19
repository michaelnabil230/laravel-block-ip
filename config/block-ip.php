<?php

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
