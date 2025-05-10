<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\AuthenticatableTestCase;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class ExampleTest extends AuthenticatableTestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test user check-in.
     */
    public function testUserCheckIn()
    {
        // Create a user - it already implements Authenticatable
        $user = User::factory()->createOne();

        // Use Laravel's built-in authentication for testing
        $this->actingAs($user);

        $this->post(route('attendance.check-in'))
            ->assertStatus(200)
            ->assertJson(['message' => 'Check-in successful.']);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'check_out_time' => null,
        ]);
    }

    /**
     * Test user check-out.
     */
    public function testUserCheckOut()
    {
        // Create a user - it already implements Authenticatable
        $user = User::factory()->createOne();

        $this->actingAs($user);

        // Simulate check-in
        $this->post(route('attendance.check-in'));

        // Perform check-out
        $this->post(route('attendance.check-out'))
            ->assertStatus(200)
            ->assertJson(['message' => 'Check-out successful.']);

        $this->assertDatabaseMissing('attendances', [
            'user_id' => $user->id,
            'check_out_time' => null,
        ]);
    }

    /**
     * Test exporting employees to CSV.
     */
    public function testExportEmployeesToCsv()
    {
        // Create a user without trying to assign the admin role
        $admin = User::factory()->create();

        // No need for casting - User already implements Authenticatable
        $this->actingAs($admin);

        // Call the controller method directly
        $controller = new \App\Http\Controllers\ExportController();
        $response = $controller->exportCsv();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/csv', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="employees.csv"', $response->headers->get('Content-Disposition'));
    }
}
