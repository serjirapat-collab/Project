<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'faculty_id' => 1,
                'name' => 'Student Demo',
                'email' => 'student@eventhub.test',
                'password' => Hash::make('Student123!'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'faculty_id' => 1,
                'name' => 'SMO CAMT',
                'email' => 'smo@eventhub.test',
                'password' => Hash::make('Smo12345!'),
                'role' => 'smo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'faculty_id' => null,
                'name' => 'University Admin',
                'email' => 'admin@eventhub.test',
                'password' => Hash::make('Admin12345!'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
