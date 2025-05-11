<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class EmployeesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $batchSize = 1000;
        $totalEmployees = 100000;
        $domains = ['example.com', 'company.org', 'acme.net', 'business.io', 'work.co'];

        $existingUsersCount = DB::table('users')->count();
        $this->command->info("Starting seeder with $existingUsersCount existing users.");

        // Get 'employee' role ID from roles table
        $employeeRoleId = DB::table('roles')->where('name', 'employee')->value('id');
        if (!$employeeRoleId) {
            $this->command->error("❌ 'employee' role not found. Seeder aborted.");
            return;
        }

        // Start progress bar
        $this->command->getOutput()->progressStart($totalEmployees);

        for ($i = 0; $i < $totalEmployees / $batchSize; $i++) {
            $employees = [];
            $now = now();

            for ($j = 0; $j < $batchSize; $j++) {
                $uniqueId = $existingUsersCount + ($i * $batchSize) + $j;
                $username = 'employee' . $uniqueId;
                $domain = $domains[$uniqueId % count($domains)];
                $email = $username . '@' . $domain;

                $employees[] = [
                    'name' => $faker->name,
                    'email' => $email,
                    'phone' => $faker->phoneNumber,
                    'job' => $faker->jobTitle,
                    'password' => Hash::make('password'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Insert batch of users
            DB::table('users')->insert($employees);

            // Get the inserted user IDs (approximate by latest IDs)
            $userIds = DB::table('users')
                ->orderBy('id', 'desc')
                ->limit($batchSize)
                ->pluck('id');

            $roleAssignments = $userIds->map(function ($userId) use ($employeeRoleId) {
                return [
                    'role_id' => $employeeRoleId,
                    'model_type' => \App\Models\User::class,
                    'model_id' => $userId,
                ];
            })->toArray();

            DB::table('model_has_roles')->insert($roleAssignments);

            // Update progress bar
            $this->command->getOutput()->progressAdvance($batchSize);
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info("🎉 Seeder completed. $totalEmployees employees created.");
    }
}
