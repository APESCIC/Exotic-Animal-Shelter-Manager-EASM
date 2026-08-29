<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Settings · {{ config('app.name') }}</title>
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
            label { display: block; margin: 0.75rem 0 0.25rem; font-weight: 500; }
            input, select {
                width: 100%;
                box-sizing: border-box;
                padding: 0.45rem 0.5rem;
                font: inherit;
            }
            button {
                margin-top: 0.75rem;
                padding: 0.55rem 1rem;
                font: inherit;
                cursor: pointer;
            }
            .errors {
                background: #fde8e8;
                color: #7f1d1d;
                padding: 0.75rem 1rem;
                margin: 1rem 0;
            }
            .status {
                background: #e8f5e9;
                color: #1b4332;
                padding: 0.75rem 1rem;
                margin: 1rem 0;
            }
            .hint { margin: 0 0 1rem; color: #444; }
        </style>
    </head>
    <body>
        <h1>Organisation settings</h1>
        <p class="hint">Shelter name, UK locale, and timezone. Dates display as dd/mm/yyyy. One shelter per install.</p>
        <p>Today: <strong>{{ \App\Support\UkDate::format(now()) }}</strong></p>

        @if (session('status'))
            <div class="status" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            <label for="organisation_name">Organisation name</label>
            <input id="organisation_name" name="organisation_name" value="{{ old('organisation_name', $settings->organisation_name) }}" required>

            <label for="locale">Locale</label>
            <select id="locale" name="locale" required>
                <option value="en_GB" @selected(old('locale', $settings->locale) === 'en_GB')>United Kingdom (en_GB)</option>
                <option value="en_US" @selected(old('locale', $settings->locale) === 'en_US')>United States (en_US)</option>
            </select>

            <label for="timezone">Timezone</label>
            <select id="timezone" name="timezone" required>
                @foreach ($timezones as $timezone)
                    <option value="{{ $timezone }}" @selected(old('timezone', $settings->timezone) === $timezone)>{{ $timezone }}</option>
                @endforeach
            </select>

            <button type="submit">Save settings</button>
        </form>

        <p><a href="{{ route('admin.dashboard') }}">Administration</a> · <a href="{{ route('home') }}">Home</a></p>
    </body>
</html>
