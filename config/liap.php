<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Routing configuration
     |--------------------------------------------------------------------------
     |
     | This configuration is used to determine the routing behavior of the
     | Server notifications handler endpoint.
     |
     | You can find more information on documentation.
     | @see https://imdhemy.com/laravel-iap-docs/docs/get-started/routing
     */

    'routing' => [
        'signed' => true,
        'middleware' => [],
        'prefix' => '',
    ],

    /*
     |--------------------------------------------------------------------------
     | Google Play Default Package name
     |--------------------------------------------------------------------------
     |
     | This value is the default package name used when the package name is not
     | provided while verifying the receipts.
     |
     */
    'google_play_package_name' => env('GOOGLE_PLAY_PACKAGE_NAME', 'com.some.thing'),

    /*
     |--------------------------------------------------------------------------
     | App Store Password
     |--------------------------------------------------------------------------
     |
     | This value is the app-specific share password generated by the app store.
     | @see https://imdhemy.com/laravel-iap-docs/docs/credentials/app-store
     |
     */
    'appstore_password' => env('APPSTORE_PASSWORD', ''),

    /*
     |--------------------------------------------------------------------------
     | Event Listeners
     |--------------------------------------------------------------------------
     |
     | This configuration is used to determine the event listeners that will be
     | registered with the application.
     | You can find a list of all available events of the documentation
     |
     | @see https://imdhemy.com/laravel-iap-docs/docs/server-notifications/event-list
     | @see https://imdhemy.com/laravel-iap-docs/docs/get-started/event-listeners
     |
    */
    'eventListeners' => [
        /*
         |--------------------------------------------------------------------------
         | App Store Events
         |--------------------------------------------------------------------------
         |
         | These event listeners are triggered when a new notification is received from App Store.
         | @see https://imdhemy.com/laravel-iap-docs/docs/server-notifications/event-list#app-store-events
         |
         */

        /*  \Imdhemy\Purchases\Events\AppStore\Cancel::class => [
              \App\Listeners\AppStore\Cancel::class,
          ],*/


        // SubscriptionRenewed::class => [\App\Listeners\AutoRenewAppStoreSubscription::class],

        /*
         |--------------------------------------------------------------------------
         | Google Play Events
         |--------------------------------------------------------------------------
         |
         | These event listeners are triggered when a new notification is received from Google Play
         | @see @see https://imdhemy.com/laravel-iap-docs/docs/server-notifications/event-list#google-play-events
         */

        /* \Imdhemy\Purchases\Events\GooglePlay\SubscriptionRecovered::class => [
             \App\Listeners\GooglePlay\SubscriptionRecovered::class,
         ],*/
    ],
];
