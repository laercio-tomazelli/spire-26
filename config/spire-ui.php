<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Component Prefix
    |--------------------------------------------------------------------------
    |
    | This value determines the prefix used for Spire UI components.
    | By default, components are available as <x-spire::component-name>.
    |
    */
    'prefix' => 'spire',

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | The default theme for Spire UI components.
    | Options: 'light', 'dark', 'system'
    |
    */
    'theme' => 'system',

    /*
    |--------------------------------------------------------------------------
    | Toast Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for toast notifications.
    |
    */
    'toast' => [
        'position' => 'top-right',
        'duration' => 5000,
        'max_visible' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal Configuration
    |--------------------------------------------------------------------------
    |
    | Default configuration for modal dialogs.
    |
    */
    'modal' => [
        'close_on_escape' => true,
        'close_on_backdrop' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Date Format
    |--------------------------------------------------------------------------
    |
    | Default date format for date pickers.
    |
    */
    'date_format' => 'dd/mm/yyyy',

    /*
    |--------------------------------------------------------------------------
    | Locale
    |--------------------------------------------------------------------------
    |
    | Default locale for formatting (currency, dates, etc).
    |
    */
    'locale' => 'pt-BR',

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | Default currency for formatting.
    |
    */
    'currency' => 'BRL',
];
