<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
