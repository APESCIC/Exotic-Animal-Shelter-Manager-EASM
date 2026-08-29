<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApplicationBootTest extends TestCase
{
    public function test_the_application_boots_and_serves_the_home_page(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Exotic Animal Shelter Manager', false)
            ->assertSee(config('app.version'), false);
    }

    public function test_uk_defaults_are_used_at_boot(): void
    {
        $this->assertSame('Europe/London', config('app.timezone'));
        $this->assertSame('en_GB', config('app.locale'));
        $this->assertSame('en_GB', config('app.fallback_locale'));
        $this->assertSame('en_GB', config('app.faker_locale'));
    }
}
