<?php

/**
 * After `composer install`, make sure a .env exists and has an APP_KEY.
 * Does not migrate or mark the app installed — that is the web wizard.
 */
$root = dirname(__DIR__);
$env = $root.DIRECTORY_SEPARATOR.'.env';
$example = $root.DIRECTORY_SEPARATOR.'.env.example';

if (! is_file($env)) {
    if (! is_file($example)) {
        fwrite(STDERR, "Missing .env.example; cannot create .env.\n");
        exit(1);
    }

    if (! copy($example, $env)) {
        fwrite(STDERR, "Unable to copy .env.example to .env.\n");
        exit(1);
    }
}

$contents = (string) file_get_contents($env);

if (preg_match('/^APP_KEY=\s*$/m', $contents) === 1) {
    $key = 'base64:'.base64_encode(random_bytes(32));
    $contents = (string) preg_replace('/^APP_KEY=.*$/m', 'APP_KEY='.$key, $contents, 1);
    file_put_contents($env, $contents);
}

exit(0);
