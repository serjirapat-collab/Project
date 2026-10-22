<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'faculty_id' => 1,
                'category_id' => 1,
                'created_by' => 2,
                'title' => 'AI Innovation Workshop',
                'description' => 'เวิร์กช็อปเรียนรู้การประยุกต์ใช้ AI',
                'image' => null,
                'location' => 'CAMT',
                'start_at' => now()->addDays(7)->setTime(9, 0),
                'end_at' => now()->addDays(7)->setTime(16, 0),
                'capacity' => 50,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'faculty_id' => null,
                'category_id' => 2,
                'created_by' => 3,
                'title' => 'University Volunteer Day',
                'description' => 'กิจกรรมจิตอาสาสำหรับนักศึกษาทุกคณะ',
                'image' => null,
                'location' => 'มหาวิทยาลัยเชียงใหม่',
                'start_at' => now()->addDays(14)->setTime(8, 0),
                'end_at' => now()->addDays(14)->setTime(12, 0),
                'capacity' => 100,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
