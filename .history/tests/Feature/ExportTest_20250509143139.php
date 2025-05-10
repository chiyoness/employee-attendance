<?php

namespace Tests\Feature;

use Tests\Feature\AuthenticatableTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ExportTest extends AuthenticatableTestCase
{
    use RefreshDatabase;
      /**
     * Test exporting employees to CSV.

     */    public function testExportEmployeesToCsv()
    {
        // Create a user with admin privileges
        $admin = User::factory()->create([
            'name' => 'Admin Tester',
            'email' => 'admin.test@example.com',
        ]);

        // Mock the hasRole method to return true for 'admin'
        $admin = Mockery::mock($admin)->makePartial();
        $admin->shouldReceive('hasRole')->with('admin')->andReturn(true);
        
        // Mock authorization for this test
        $this->actingAs($this->castToAuthenticatable($admin));
        
        // Test the route with proper authentication
        $response = $this->get(route('employees.export.csv'));
        
        // Check if the response is successful
        $response->assertOk();
        
        // Verify response headers for CSV
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="employees.csv"');
    }
      /**
     * Test exporting employees to PDF.
     */    public function testExportEmployeesToPdf()
    {
        // Create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
        
        // Create a user with admin role
        $admin = User::factory()->create([
            'name' => 'Admin Tester',
            'email' => 'admin.pdf@example.com',
        ]);
        $admin->assignRole($adminRole);
        
        // Mock authorization for this test
        $this->actingAs($this->castToAuthenticatable($admin));
        
        // Test the route with proper authentication
        $response = $this->get(route('employees.export.pdf'));
        
        // Check if the response is successful
        $response->assertOk();
        
        // Verify response headers for PDF
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
