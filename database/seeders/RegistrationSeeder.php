<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('registrations')->insert([
            [
                'event_id' => 1,
                'user_id' => 1,
                'registered_at' => now(),
                'status' => 'registered',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
