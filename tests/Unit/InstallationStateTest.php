<?php

namespace Tests\Unit;

use App\Install\InstallationState;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class InstallationStateTest extends TestCase
{
    private string $installedPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->installedPath = storage_path('framework/testing/installed-'.uniqid('', true));
        config([
            'easm.installed' => false,
            'easm.installed_path' => $this->installedPath,
        ]);
    }

    protected function tearDown(): void
    {
        if (is_file($this->installedPath)) {
            unlink($this->installedPath);
        }

        parent::tearDown();
    }

    public function test_install_lock_without_migrations_table_is_not_installed(): void
    {
        file_put_contents($this->installedPath, '{"installed_at":"2026-01-01T00:00:00+00:00"}'.PHP_EOL);
        config(['easm.installed' => true]);

        Schema::shouldReceive('hasTable')
            ->once()
            ->with('migrations')
            ->andReturn(false);

        $this->assertFalse($this->app->make(InstallationState::class)->isInstalled());
    }

    public function test_install_lock_with_migrations_table_is_installed(): void
    {
        file_put_contents($this->installedPath, '{"installed_at":"2026-01-01T00:00:00+00:00"}'.PHP_EOL);

        Schema::shouldReceive('hasTable')
            ->once()
            ->with('migrations')
            ->andReturn(true);

        $this->assertTrue($this->app->make(InstallationState::class)->isInstalled());
    }
}
