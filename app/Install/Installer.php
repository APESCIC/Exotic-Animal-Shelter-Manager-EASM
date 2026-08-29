<?php

namespace App\Install;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class Installer
{
    public function __construct(
        private InstallationState $state,
        private EnvFile $envFile,
        private DatabaseConnector $database,
    ) {}

    /**
     * Persist hosting config, migrate, create the first admin, and lock the wizard.
     *
     * @param  array<string, mixed>  $input
     */
    public function install(array $input): void
    {
        $credentials = $this->credentials($input);

        if (! app()->runningUnitTests()) {
            $this->database->test($credentials);
            $this->database->apply($credentials);
        }

        $exitCode = Artisan::call('migrate', [
            '--force' => true,
        ]);

        if ($exitCode !== 0) {
            throw new \RuntimeException(trim(Artisan::output()) ?: 'Database migrations failed.');
        }

        User::query()->updateOrCreate(
            ['email' => $input['admin_email']],
            [
                'name' => $input['admin_name'],
                'password' => $input['admin_password'],
            ],
        );

        // Write .env last. php artisan serve restarts when .env changes; doing
        // migrate + admin first avoids APP_INSTALLED=true with no admin user.
        $this->envFile->write($this->state->envPath(), $this->envValues($input));

        config([
            'app.name' => $input['organisation'],
            'app.timezone' => $input['timezone'],
            'easm.installed' => true,
        ]);

        $this->state->markInstalled();
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{host: string, port: string, database: string, username: string, password: string}
     */
    private function credentials(array $input): array
    {
        return [
            'host' => (string) $input['db_host'],
            'port' => (string) $input['db_port'],
            'database' => (string) $input['db_database'],
            'username' => (string) $input['db_username'],
            'password' => (string) ($input['db_password'] ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, string>
     */
    private function envValues(array $input): array
    {
        $values = [
            'APP_NAME' => (string) $input['organisation'],
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
            'APP_TIMEZONE' => (string) $input['timezone'],
            'APP_INSTALLED' => 'true',
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => (string) $input['db_host'],
            'DB_PORT' => (string) $input['db_port'],
            'DB_DATABASE' => (string) $input['db_database'],
            'DB_USERNAME' => (string) $input['db_username'],
            'DB_PASSWORD' => (string) ($input['db_password'] ?? ''),
        ];

        if (blank(config('app.key'))) {
            $key = 'base64:'.base64_encode(random_bytes(32));
            $values['APP_KEY'] = $key;
            config(['app.key' => $key]);
        }

        return $values;
    }
}
