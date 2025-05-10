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

        for ($i = 0; $i < 100; $i++) { // 100 batches of 1000 employees
            $employees = [];

            for ($j = 0; $j < $batchSize; $j++) {
                $employees[] = [
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
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
        }
    }
}
