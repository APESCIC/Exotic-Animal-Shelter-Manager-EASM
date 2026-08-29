<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationBootTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_boots_and_serves_the_home_page(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/');

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
