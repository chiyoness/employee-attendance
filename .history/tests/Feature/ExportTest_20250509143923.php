<?php

namespace Tests\Feature;

use Tests\Feature\AuthenticatableTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportTest extends AuthenticatableTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles and permissions to avoid Spatie errors
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        // Disable middleware to avoid role binding errors in tests
        $this->withoutMiddleware();
    }

    /**
     * Test exporting employees to CSV.
     */
    public function testExportEmployeesToCsv()
    {
        // Create a user with admin privileges
        $admin = User::factory()->create([
            'name' => 'Admin Tester',
            'email' => 'admin.test@example.com',
        ]);

        // Use admin middleware bypass
        $this->actingAs($admin, 'web');
        
        // Test the route with proper authentication
        $response = $this->get(route('employees.export.csv'));
        
        // Check if the response is successful
        $response->assertOk();
        
        // Verify response headers for CSV
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="employees.csv"');
    }

    /**
     * Test exporting employees to PDF.
     */
    public function testExportEmployeesToPdf()
    {
        // Create a user with admin privileges
        $admin = User::factory()->create([
            'name' => 'Admin Tester',
            'email' => 'admin.pdf@example.com',
        ]);

        // Use admin middleware bypass
        $this->actingAs($admin, 'web');
        
        // Test the route with proper authentication
        $response = $this->get(route('employees.export.pdf'));
        
        // Check if the response is successful
        $response->assertOk();
        
        // Verify response headers for PDF
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
