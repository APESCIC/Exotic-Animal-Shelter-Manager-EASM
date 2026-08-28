<?php

namespace Tests\Feature;

use Tests\TestCase;

class EnvExampleTest extends TestCase
{
    public function test_env_example_defaults_to_production_without_debug(): void
    {
        $example = file_get_contents(base_path('.env.example'));

        $this->assertIsString($example);
        $this->assertMatchesRegularExpression('/^APP_ENV=production$/m', $example);
        $this->assertMatchesRegularExpression('/^APP_DEBUG=false$/m', $example);
    }
}
