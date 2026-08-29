<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <style>
            :root { color-scheme: light dark; }
            body {
                font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
                margin: 2rem auto;
                max-width: 40rem;
                line-height: 1.5;
                color: #1b1b18;
            }
            a { color: #1b4332; }
            code { font-size: 0.95em; }
        </style>
    </head>
    <body>
        <h1>{{ config('app.name') }}</h1>
        <p>Self-hosted shelter software for exotic species. One shelter per install. Species stay free-text — there is no dog/cat vocabulary in this product.</p>
        <p>Version {{ config('app.version') }} · locale {{ config('app.locale') }} · timezone {{ config('app.timezone') }}</p>
        <p><a href="{{ url('/health') }}">Health and version</a></p>
    </body>
</html>
