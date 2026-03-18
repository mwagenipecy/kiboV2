<?php

namespace Tests\Feature;

use App\Livewire\Admin\Users\UsersList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create and authenticate an admin user
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
        ]);
        
        $this->actingAs($admin);
    }

    public function test_admin_can_create_new_admin_user()
    {
        Mail::fake();

        Livewire::test(UsersList::class)
            ->call('openAddAdminModal')
            ->set('adminName', 'New Admin')
            ->set('adminEmail', 'newadmin@test.com')
            ->call('createAdmin')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'New Admin',
            'email' => 'newadmin@test.com',
            'role' => 'admin',
        ]);
    }

    public function test_admin_email_must_be_unique()
    {
        User::factory()->create(['email' => 'existing@test.com']);

        Livewire::test(UsersList::class)
            ->call('openAddAdminModal')
            ->set('adminName', 'New Admin')
            ->set('adminEmail', 'existing@test.com')
            ->call('createAdmin')
            ->assertHasErrors(['adminEmail']);
    }

    public function test_admin_can_reset_user_password()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'user@test.com',
            'password' => Hash::make('oldpassword'),
        ]);

        Livewire::test(UsersList::class)
            ->call('openResetPasswordModal', $user->id)
            ->set('resetPassword', 'newpassword123')
            ->set('resetPasswordConfirmation', 'newpassword123')
            ->call('resetUserPassword')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_password_confirmation_must_match()
    {
        $user = User::factory()->create();

        Livewire::test(UsersList::class)
            ->call('openResetPasswordModal', $user->id)
            ->set('resetPassword', 'newpassword123')
            ->set('resetPasswordConfirmation', 'differentpassword')
            ->call('resetUserPassword')
            ->assertHasErrors(['resetPasswordConfirmation']);
    }

    public function test_password_must_be_at_least_8_characters()
    {
        $user = User::factory()->create();

        Livewire::test(UsersList::class)
            ->call('openResetPasswordModal', $user->id)
            ->set('resetPassword', 'short')
            ->set('resetPasswordConfirmation', 'short')
            ->call('resetUserPassword')
            ->assertHasErrors(['resetPassword']);
    }

    public function test_users_list_can_be_filtered_by_role()
    {
        User::factory()->create(['role' => 'dealer']);
        User::factory()->create(['role' => 'lender']);

        $component = Livewire::test(UsersList::class)
            ->set('roleFilter', 'dealer')
            ->assertSee('dealer');
    }
}
