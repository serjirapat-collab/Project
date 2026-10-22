<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Academic',
                'description' => 'กิจกรรมด้านวิชาการ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Volunteer',
                'description' => 'กิจกรรมจิตอาสา',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sports',
                'description' => 'กิจกรรมกีฬา',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Entertainment',
                'description' => 'กิจกรรมสันทนาการ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Workshop',
                'description' => 'กิจกรรมอบรมเชิงปฏิบัติการ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
