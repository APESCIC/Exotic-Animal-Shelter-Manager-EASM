<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_seeder_does_not_create_a_known_credential_user(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
        $this->assertSame(0, User::query()->count());
    }
}
