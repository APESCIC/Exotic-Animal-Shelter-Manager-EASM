<?php

namespace App\Install;

class InstallationState
{
    /**
     * Whether this shelter has already completed the web installer.
     */
    public function isInstalled(): bool
    {
        if (is_file($this->installedPath())) {
            return true;
        }

        return filter_var(config('easm.installed'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Path to the environment file the installer may write.
     */
    public function envPath(): string
    {
        return (string) config('easm.env_path', base_path('.env'));
    }

    /**
     * Path to the install lock file.
     */
    public function installedPath(): string
    {
        return (string) config('easm.installed_path', storage_path('app/installed'));
    }

    /**
     * Record that installation finished and must not be repeated.
     */
    public function markInstalled(): void
    {
        $path = $this->installedPath();
        $directory = dirname($path);

        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new \RuntimeException("Unable to create install lock directory [{$directory}].");
        }

        $payload = json_encode([
            'installed_at' => now()->timezone('UTC')->toIso8601String(),
            'version' => config('app.version'),
        ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

        file_put_contents($path, $payload.PHP_EOL);
    }
}
