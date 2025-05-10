<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Mockery;

class ExportTest2 extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test exporting employees to CSV.
     */
    public function test_export_employees_to_csv(): void
    {
        // Create a user
        $admin = User::factory()->create([
            'name' => 'Admin Tester',
            'email' => 'admin.test@example.com',
        ]);
        
        // Use admin middleware bypass
        $this->actingAs($admin);
        
        // Test the route
        $response = $this->get(route('employees.export.csv'));
        
        // Check if the response is successful
        $response->assertOk();
        
        // Verify response headers for CSV
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="employees.csv"');
    }
    
    /**
     * Test exporting employees to PDF.
     */
    public function test_export_employees_to_pdf(): void
    {
        // Create a user
        $admin = User::factory()->create([
            'name' => 'Admin Tester',
            'email' => 'admin.pdf@example.com',
        ]);
        
        // Use admin middleware bypass
        $this->actingAs($admin);
        
        // Test the route
        $response = $this->get(route('employees.export.pdf'));
        
        // Check if the response is successful
        $response->assertOk();
        
        // Verify response headers for PDF
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
