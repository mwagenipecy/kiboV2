<?php

namespace Tests\Feature;

use App\Livewire\Admin\Registration\CreateDealer;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DealerRegistrationEmailValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_entity_email_must_be_unique_in_entities_table()
    {
        Entity::factory()->create(['email' => 'dealer@example.com']);

        Livewire::test(CreateDealer::class)
            ->set('name', 'Test Dealer')
            ->set('email', 'dealer@example.com')
            ->set('country', 'Kenya')
            ->set('user_name', 'John Doe')
            ->set('user_email', 'john@example.com')
            ->call('save')
            ->assertHasErrors(['email']);
    }

    public function test_entity_email_must_be_unique_in_users_table()
    {
        User::factory()->create(['email' => 'dealer@example.com']);

        Livewire::test(CreateDealer::class)
            ->set('name', 'Test Dealer')
            ->set('email', 'dealer@example.com')
            ->set('country', 'Kenya')
            ->set('user_name', 'John Doe')
            ->set('user_email', 'john@example.com')
            ->call('save')
            ->assertHasErrors(['email']);
    }

    public function test_user_email_must_be_unique_in_users_table()
    {
        User::factory()->create(['email' => 'john@example.com']);

        Livewire::test(CreateDealer::class)
            ->set('name', 'Test Dealer')
            ->set('email', 'dealer@example.com')
            ->set('country', 'Kenya')
            ->set('user_name', 'John Doe')
            ->set('user_email', 'john@example.com')
            ->call('save')
            ->assertHasErrors(['user_email']);
    }

    public function test_user_email_must_be_unique_in_entities_table()
    {
        Entity::factory()->create(['email' => 'john@example.com']);

        Livewire::test(CreateDealer::class)
            ->set('name', 'Test Dealer')
            ->set('email', 'dealer@example.com')
            ->set('country', 'Kenya')
            ->set('user_name', 'John Doe')
            ->set('user_email', 'john@example.com')
            ->call('save')
            ->assertHasErrors(['user_email']);
    }

    public function test_dealer_can_be_created_with_unique_emails()
    {
        Livewire::test(CreateDealer::class)
            ->set('name', 'Test Dealer')
            ->set('email', 'dealer@example.com')
            ->set('country', 'Kenya')
            ->set('user_name', 'John Doe')
            ->set('user_email', 'john@example.com')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.registration.dealers'));

        $this->assertDatabaseHas('entities', [
            'email' => 'dealer@example.com',
            'name' => 'Test Dealer',
        ]);
    }
}
