<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Installation lock
    |--------------------------------------------------------------------------
    |
    | The web installer writes APP_INSTALLED=true into .env and a lock file
    | under storage/app. Either marker means this shelter is already installed
    | and the wizard must not run again. One shelter per install.
    |
    */

    'installed' => filter_var(env('APP_INSTALLED', false), FILTER_VALIDATE_BOOLEAN),

    'env_path' => env('EASM_ENV_PATH', base_path('.env')),

    'installed_path' => env('EASM_INSTALLED_PATH', storage_path('app/installed')),

];
