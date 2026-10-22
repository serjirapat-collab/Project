<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('faculties')->insert([
            [
                'name' => 'CAMT',
                'description' => 'วิทยาลัยศิลปะ สื่อ และเทคโนโลยี',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Engineering',
                'description' => 'คณะวิศวกรรมศาสตร์',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Medicine',
                'description' => 'คณะแพทยศาสตร์',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
