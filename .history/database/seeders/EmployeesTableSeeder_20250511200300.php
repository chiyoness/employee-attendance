<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class EmployeesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $batchSize = 1000;
        $totalEmployees = 100000;
        $domains = ['example.com', 'company.org', 'acme.net', 'business.io', 'work.co'];

        $existingUsersCount = User::count();
        $this->command->info("Starting with $existingUsersCount existing users");

        // Get role_id of 'employee' role once
        $employeeRoleId = DB::table('roles')->where('name', 'employee')->value('id');
        if (!$employeeRoleId) {
            $this->command->error("Employee role not found!");
            return;
        }

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

            // Bulk insert users
            DB::table('users')->insert($employees);

            // Get last inserted IDs (rely on auto-increment order)
            $lastInsertedIds = DB::table('users')
                ->orderBy('id', 'desc')
                ->limit($batchSize)
                ->pluck('id');

            $roleUserRows = $lastInsertedIds->map(function ($userId) use ($employeeRoleId) {
                return [
                    'role_id' => $employeeRoleId,
                    'model_type' => \App\Models\User::class,
                    'model_id' => $userId,
                ];
            })->toArray();

            DB::table('model_has_roles')->insert($roleUserRows);

            $createdCount = ($i + 1) * $batchSize;
            $this->command->info("✅ Batch " . ($i + 1) . ": Inserted $createdCount employees");
        }

        $this->command->info("🎉 Completed: Created $totalEmployees employee records");
    }
}
