<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $batchSize = 1000; // Number of records per batch
        $totalEmployees = 100000;
        $domains = ['example.com', 'company.org', 'acme.net', 'business.io', 'work.co'];
        
        // Get count of existing users to ensure we don't create duplicate emails
        $existingUsersCount = User::count();
        
        // Check if we already have users and might be running this seeder again
        $this->command->info("Starting with $existingUsersCount existing users");
        
        for ($i = 0; $i < $totalEmployees / $batchSize; $i++) {
            $employees = [];
            
            for ($j = 0; $j < $batchSize; $j++) {
                $uniqueId = $existingUsersCount + ($i * $batchSize) + $j;
                
                // Create deterministic but unique email
                $username = 'employee' . $uniqueId;
                $domain = $domains[$uniqueId % count($domains)];
                $email = $username . '@' . $domain;
                
                $employees[] = [
                    'name' => $faker->name,
                    'email' => $email,
                    'phone' => $faker->phoneNumber,
                    'job' => $faker->jobTitle,
                    'password' => Hash::make('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Insert batch of employees
            User::insert($employees);
            
            // Assign the employee role to each user in the batch
            $userIds = User::orderBy('id', 'desc')->take($batchSize)->pluck('id');
            foreach ($userIds as $userId) {
                $user = User::find($userId);
                $user->assignRole('employee');
            }
            
            // Output progress
            $this->command->info("Processed batch " . ($i + 1) . " of " . ($totalEmployees / $batchSize) . 
                " (" . (($i + 1) * $batchSize) . " employees created)");
        }
        
        $this->command->info("Completed: Created " . $totalEmployees . " employee records");
    }
}
