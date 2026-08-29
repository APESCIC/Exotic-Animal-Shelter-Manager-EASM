<?php

namespace Tests\Feature;

use App\Http\Controllers\HealthController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PDO;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HealthTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint_reports_ok_status_and_version(): void
    {
        $response = $this->getJson('/health');

        $response->assertOk()
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('version', config('app.version'))
            ->assertJsonPath('app', config('app.name'))
            ->assertJsonPath('checks.app', true)
            ->assertJsonPath('checks.database', true);
    }

    public function test_health_endpoint_does_not_expose_secrets(): void
    {
        $response = $this->getJson('/health');

        $response->assertOk();

        $payload = $response->json();
        $encoded = json_encode($payload);

        $this->assertIsArray($payload);
        $this->assertStringNotContainsString((string) config('app.key'), (string) $encoded);
        $this->assertArrayNotHasKey('env', $payload);
        $this->assertArrayNotHasKey('database', $payload);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function mysqlFamilyDrivers(): array
    {
        return [
            'mysql' => ['mysql'],
            'mariadb' => ['mariadb'],
        ];
    }

    #[DataProvider('mysqlFamilyDrivers')]
    public function test_mysql_family_health_check_applies_a_short_connect_timeout(string $driver): void
    {
        config([
            'database.default' => $driver,
            "database.connections.{$driver}.host" => '127.0.0.1',
            "database.connections.{$driver}.port" => 1,
            "database.connections.{$driver}.database" => 'easm',
            "database.connections.{$driver}.username" => 'easm',
            "database.connections.{$driver}.password" => 'secret',
        ]);
        DB::purge();

        $response = $this->getJson('/health');

        $response->assertStatus(503)
            ->assertJsonPath('status', 'degraded')
            ->assertJsonPath('checks.database', false);

        $this->assertSame(
            HealthController::DATABASE_CONNECT_TIMEOUT_SECONDS,
            config("database.connections.{$driver}.options")[PDO::ATTR_TIMEOUT]
        );
    }
}
