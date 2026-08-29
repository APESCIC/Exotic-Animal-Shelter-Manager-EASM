<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\User;
use App\Support\UkDate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_organisation_settings_and_uk_dates_show(): void
    {
        Setting::query()->create([
            'organisation_name' => 'Old Shelter',
            'locale' => 'en_GB',
            'timezone' => 'Europe/London',
        ]);

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'organisation_name' => 'APES CIC Rescue',
                'locale' => 'en_GB',
                'timezone' => 'Europe/London',
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $settings = Setting::current();
        $this->assertNotNull($settings);
        $this->assertSame('APES CIC Rescue', $settings->organisation_name);
        $this->assertSame('en_GB', $settings->locale);
        $this->assertSame('Europe/London', $settings->timezone);

        $this->actingAs($admin)
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('APES CIC Rescue', false)
            ->assertSee(UkDate::format(now()), false);

        $this->actingAs($admin)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('APES CIC Rescue', false)
            ->assertSee(UkDate::format(now()), false);
    }

    public function test_non_admin_cannot_open_or_update_settings(): void
    {
        Setting::query()->create([
            'organisation_name' => 'Shelter',
            'locale' => 'en_GB',
            'timezone' => 'Europe/London',
        ]);

        foreach ([UserRole::Staff, UserRole::Volunteer, UserRole::Readonly] as $role) {
            $user = User::factory()->state(['role' => $role])->create();

            $this->actingAs($user)
                ->get(route('admin.settings.edit'))
                ->assertForbidden();

            $this->actingAs($user)
                ->put(route('admin.settings.update'), [
                    'organisation_name' => 'Hacked',
                    'locale' => 'en_GB',
                    'timezone' => 'Europe/London',
                ])
                ->assertForbidden();
        }

        $this->assertSame('Shelter', Setting::current()?->organisation_name);
    }

    public function test_uk_date_helper_formats_dd_mm_yyyy(): void
    {
        $this->assertSame(
            '29/08/2026',
            UkDate::format('2026-08-29 15:00:00'),
        );
    }
}
