<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

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
        $domains = ['example.com', 'company.org', 'acme.net', 'business.io', 'work.co', 'corp.com', 'enterprise.net', 'staff.org', 'team.io', 'workers.co'];
        
        for ($i = 0; $i < $totalEmployees / $batchSize; $i++) { // 100 batches of 1000 employees
            $employees = [];
            $startIndex = $i * $batchSize;

            for ($j = 0; $j < $batchSize; $j++) {
                $index = $startIndex + $j;
                // Generate unique email using index-based approach
                $emailPrefix = $faker->userName . $index;
                $emailDomain = $domains[array_rand($domains)];
                $email = $emailPrefix . '@' . $emailDomain;

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

            User::insert($employees);

            // Assign the employee role to each user in the batch
            $userIds = User::latest('id')->take($batchSize)->pluck('id');
            foreach ($userIds as $userId) {
                $user = User::find($userId);
                $user->assignRole('employee');
            }
            
            // Output progress
            $this->command->info("Inserted batch " . ($i + 1) . " of " . ($totalEmployees / $batchSize) . " (" . ($startIndex + $batchSize) . " employees processed)");
        }
    }
}
