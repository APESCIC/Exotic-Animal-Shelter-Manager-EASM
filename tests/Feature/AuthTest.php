<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\LoginEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_login_for_home(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));
    }

    public function test_each_role_can_sign_in_and_see_home(): void
    {
        foreach (UserRole::cases() as $role) {
            $user = User::factory()->state(['role' => $role])->create([
                'email' => $role->value.'@example.org',
                'password' => 'password-secret',
            ]);

            $this->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'password-secret',
            ])->assertRedirect('/');

            $this->get('/')
                ->assertOk()
                ->assertSee($user->name, false)
                ->assertSee($role->label(), false);

            $this->post(route('logout'))->assertRedirect(route('login'));
        }
    }

    public function test_successful_login_creates_an_audit_row(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'admin@example.org',
            'password' => 'password-secret',
        ]);

        $this->post(route('login.store'), [
            'email' => 'admin@example.org',
            'password' => 'password-secret',
        ])->assertRedirect('/');

        $this->assertSame(1, LoginEvent::query()->count());
        $event = LoginEvent::query()->first();
        $this->assertNotNull($event);
        $this->assertSame($user->id, $event->user_id);
        $this->assertNotNull($event->created_at);
    }

    public function test_non_admin_roles_cannot_open_admin_screens(): void
    {
        foreach ([UserRole::Staff, UserRole::Volunteer, UserRole::Readonly] as $role) {
            $user = User::factory()->state(['role' => $role])->create();

            $this->actingAs($user)
                ->get(route('admin.dashboard'))
                ->assertForbidden();
        }
    }

    public function test_admin_can_open_admin_screens(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Administration', false);
    }

    public function test_invalid_credentials_do_not_sign_in(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@example.org',
            'password' => 'password-secret',
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'admin@example.org',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertSame(0, LoginEvent::query()->count());
    }
}
