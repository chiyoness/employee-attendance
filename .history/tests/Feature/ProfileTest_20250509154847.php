<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\Feature\AuthenticatableTestCase;

class ProfileTest extends AuthenticatableTestCase
{
    use RefreshDatabase;    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->createOne();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->createOne();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile/edit');

        $user->refresh();        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNotNull($user->email_verified_at);
    }    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->createOne();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile/edit');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->createOne();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->createOne();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }    /**
     * Test profile update.
     */
    public function testProfileUpdate()
    {
        $user = User::factory()->createOne();

        $this->actingAs($user);

        $this->put(route('profile.update'), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone' => '1234567890',
                'job' => 'Updated Job',            ])
            ->assertRedirect('/profile/edit')
            ->assertSessionHas('success', 'Profile updated successfully.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '1234567890',
            'job' => 'Updated Job',
        ]);
    }
}
