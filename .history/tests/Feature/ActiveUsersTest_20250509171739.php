<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActiveUsersTest extends AuthenticatableTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_active_users_in_the_table()
    {
        // Seed the database with test data
        $user1 = User::factory()->create(['name' => 'John Doe']);
        $user2 = User::factory()->create(['name' => 'Jane Smith']);
        $user3 = User::factory()->create(['name' => 'Inactive User']);        // Create attendance records for active users
        Attendance::factory()->create(['user_id' => $user1->id, 'check_out_time' => null]);
        Attendance::factory()->create(['user_id' => $user2->id, 'check_out_time' => null]);

        // Simulate a request to the active users route - User already implements Authenticatable
        $response = $this->actingAs($user)->get(route('attendance.active-users'));

        // Assert the response contains the active users
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Jane Smith');
        $response->assertDontSee('Inactive User');
    }
}
