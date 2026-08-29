<?php

namespace Tests\Feature;

use App\Enums\PersonCategory;
use App\Enums\UserRole;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_create_contact_with_banned_flag(): void
    {
        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($staff)->post(route('people.store'), [
            'name' => 'Alex Owner',
            'category' => PersonCategory::Adopter->value,
            'email' => 'alex@example.org',
            'phone' => '07123456789',
            'postcode' => 'SW1A 1AA',
            'banned' => '1',
            'homechecked' => '0',
            'notes' => 'Do not rehome to this person.',
        ]);

        $person = Person::query()->where('email', 'alex@example.org')->first();
        $this->assertNotNull($person);
        $response->assertRedirect(route('people.show', $person));

        $this->assertSame('Alex Owner', $person->name);
        $this->assertTrue($person->banned);
        $this->assertFalse($person->homechecked);
        $this->assertSame(PersonCategory::Adopter, $person->category);
    }

    public function test_staff_can_search_and_filter_banned_contacts(): void
    {
        $staff = User::factory()->staff()->create();
        Person::factory()->create(['name' => 'Safe Foster', 'category' => PersonCategory::Foster, 'banned' => false]);
        Person::factory()->banned()->create(['name' => 'Banned Vet', 'category' => PersonCategory::Vet, 'email' => 'vet@example.org']);

        $this->actingAs($staff)
            ->get(route('people.index', ['q' => 'Foster']))
            ->assertOk()
            ->assertSee('Safe Foster', false)
            ->assertDontSee('Banned Vet', false);

        $this->actingAs($staff)
            ->get(route('people.index', ['banned' => '1']))
            ->assertOk()
            ->assertSee('Banned Vet', false)
            ->assertDontSee('Safe Foster', false);

        $this->actingAs($staff)
            ->get(route('people.index', ['category' => PersonCategory::Vet->value]))
            ->assertOk()
            ->assertSee('Banned Vet', false)
            ->assertDontSee('Safe Foster', false);
    }

    public function test_custom_category_requires_label(): void
    {
        $staff = User::factory()->staff()->create();

        $this->actingAs($staff)
            ->post(route('people.store'), [
                'name' => 'Custom Contact',
                'category' => PersonCategory::Custom->value,
            ])
            ->assertSessionHasErrors('category_custom');

        $this->actingAs($staff)
            ->post(route('people.store'), [
                'name' => 'Custom Contact',
                'category' => PersonCategory::Custom->value,
                'category_custom' => 'Wildlife rehabilitator',
            ])
            ->assertRedirect();

        $person = Person::query()->where('name', 'Custom Contact')->first();
        $this->assertNotNull($person);
        $this->assertSame('Wildlife rehabilitator', $person->categoryLabel());
    }

    public function test_readonly_cannot_create_people_but_can_view(): void
    {
        $readonly = User::factory()->readonly()->create();
        $person = Person::factory()->create(['name' => 'Visible Contact']);

        $this->actingAs($readonly)
            ->get(route('people.create'))
            ->assertForbidden();

        $this->actingAs($readonly)
            ->post(route('people.store'), [
                'name' => 'Blocked',
                'category' => PersonCategory::Donor->value,
            ])
            ->assertForbidden();

        $this->actingAs($readonly)
            ->get(route('people.show', $person))
            ->assertOk()
            ->assertSee('Visible Contact', false);
    }

    public function test_volunteer_cannot_edit_people(): void
    {
        $volunteer = User::factory()->state(['role' => UserRole::Volunteer])->create();
        $person = Person::factory()->create(['name' => 'Locked Contact']);

        $this->actingAs($volunteer)
            ->get(route('people.edit', $person))
            ->assertForbidden();
    }
}
