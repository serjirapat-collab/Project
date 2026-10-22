<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Look up ids by name/email so the data doesn't depend on insert order.
        $faculties = DB::table('faculties')->pluck('id', 'name');
        $categories = DB::table('categories')->pluck('id', 'name');
        $smoId = DB::table('users')->where('email', 'smo@eventhub.test')->value('id');
        $adminId = DB::table('users')->where('email', 'admin@eventhub.test')->value('id');

        // [faculty, category, title, description, location, days from now, start hour, end hour, capacity]
        $events = [
            // CAMT
            ['CAMT', 'Academic', 'CAMT Freshmen Orientation', 'ปฐมนิเทศนักศึกษาใหม่ CAMT', 'หอประชุมมหาวิทยาลัยเชียงใหม่', 3, 8, 12, 300],
            ['CAMT', 'Workshop', '24-Hour Hackathon', 'แข่งขันพัฒนาโปรแกรมภายใน 24 ชั่วโมง', 'ห้องปฏิบัติการคอมพิวเตอร์ CAMT', 7, 9, 21, 60],
            ['CAMT', 'Workshop', 'Laravel Web Development Workshop', 'เวิร์กช็อปพัฒนาเว็บด้วย Laravel', 'ห้องปฏิบัติการคอมพิวเตอร์ CAMT', 10, 13, 16, 40],
            ['CAMT', 'Workshop', '48-Hour Game Jam', 'แข่งขันสร้างเกมภายใน 48 ชั่วโมง', 'CAMT', 14, 9, 18, 80],
            ['CAMT', 'Academic', 'AI in Creative Industries Seminar', 'สัมมนา AI กับอุตสาหกรรมสร้างสรรค์', 'ห้องประชุม CAMT', 18, 13, 16, 120],
            ['CAMT', 'Entertainment', 'Short Film Contest', 'ประกวดหนังสั้น', 'หอศิลปวัฒนธรรมแห่งมหาวิทยาลัยเชียงใหม่', 24, 17, 21, 150],
            ['CAMT', 'Entertainment', 'Animation & Digital Media Exhibition', 'นิทรรศการแอนิเมชันและสื่อดิจิทัล', 'หอศิลปวัฒนธรรมแห่งมหาวิทยาลัยเชียงใหม่', 30, 10, 18, 200],
            ['CAMT', 'Workshop', 'Photo & Video Editing Workshop', 'เวิร์กช็อปถ่ายภาพและตัดต่อวิดีโอ', 'อ่างแก้ว', 36, 7, 11, 30],
            ['CAMT', 'Sports', 'CAMT Sports Day', 'กีฬา CAMT สัมพันธ์', 'โรงยิมเนเซียม มช.', 42, 8, 17, 250],

            // Engineering
            ['Engineering', 'Academic', 'Engineering Freshmen Orientation', 'ปฐมนิเทศนักศึกษาใหม่คณะวิศวกรรมศาสตร์', 'หอประชุมมหาวิทยาลัยเชียงใหม่', 4, 8, 12, 500],
            ['Engineering', 'Entertainment', 'Line Following Robot Contest', 'แข่งขันหุ่นยนต์เดินตามเส้น', 'โรงยิมเนเซียม มช.', 8, 9, 16, 150],
            ['Engineering', 'Workshop', 'Arduino Basics Workshop', 'เวิร์กช็อป Arduino เบื้องต้น', 'ห้องปฏิบัติการ คณะวิศวกรรมศาสตร์', 11, 13, 16, 40],
            ['Engineering', 'Academic', 'Engineering Open House', 'เปิดบ้านคณะวิศวกรรมศาสตร์', 'คณะวิศวกรรมศาสตร์', 15, 9, 16, 400],
            ['Engineering', 'Academic', 'Renewable Energy Seminar', 'สัมมนาพลังงานทดแทน', 'ห้องประชุม คณะวิศวกรรมศาสตร์', 19, 13, 16, 120],
            ['Engineering', 'Entertainment', 'Popsicle Stick Bridge Contest', 'ประกวดสะพานไม้ไอศกรีม', 'คณะวิศวกรรมศาสตร์', 25, 9, 15, 100],
            ['Engineering', 'Sports', 'Engineering Sports Day', 'กีฬาวิศวะสัมพันธ์', 'สนามกีฬามหาวิทยาลัยเชียงใหม่', 31, 8, 17, 600],
            ['Engineering', 'Academic', 'Earthquake Engineering Seminar', 'สัมมนาวิศวกรรมโยธากับการรับมือแผ่นดินไหว', 'ห้องประชุม คณะวิศวกรรมศาสตร์', 37, 13, 16, 120],
            ['Engineering', 'Volunteer', 'Engineering Volunteer Camp', 'ค่ายวิศวะอาสาซ่อมแซมอาคาร (จุดนัดพบก่อนออกค่าย)', 'อ่างแก้ว', 45, 7, 17, 50],

            // Medicine
            ['Medicine', 'Academic', 'Medicine Freshmen Orientation', 'ปฐมนิเทศนักศึกษาใหม่คณะแพทยศาสตร์', 'หอประชุมมหาวิทยาลัยเชียงใหม่', 5, 8, 12, 300],
            ['Medicine', 'Workshop', 'CPR Training', 'อบรมการช่วยฟื้นคืนชีพ (CPR)', 'ห้องประชุม คณะแพทยศาสตร์', 9, 13, 16, 60],
            ['Medicine', 'Volunteer', 'Free Health Check-up', 'ตรวจสุขภาพฟรีสำหรับนักศึกษา', 'คณะแพทยศาสตร์', 12, 8, 15, 200],
            ['Medicine', 'Volunteer', 'Blood Donation', 'รับบริจาคโลหิต', 'โรงพยาบาลมหาราชนครเชียงใหม่', 16, 8, 15, 150],
            ['Medicine', 'Academic', 'Student Mental Health Seminar', 'สัมมนาสุขภาพจิตนักศึกษา', 'สำนักหอสมุด มช.', 20, 13, 16, 80],
            ['Medicine', 'Academic', 'Med Open House', 'เปิดบ้านคณะแพทยศาสตร์', 'คณะแพทยศาสตร์', 26, 9, 16, 400],
            ['Medicine', 'Workshop', 'First Aid Training', 'อบรมปฐมพยาบาลเบื้องต้น', 'ห้องประชุม คณะแพทยศาสตร์', 32, 13, 16, 60],
            ['Medicine', 'Sports', 'Health Run Around Ang Kaew', 'วิ่งเพื่อสุขภาพรอบอ่างแก้ว', 'อ่างแก้ว', 38, 6, 9, 300],
            ['Medicine', 'Sports', 'Medicine Sports Day', 'กีฬาหมอสัมพันธ์', 'สนามกีฬามหาวิทยาลัยเชียงใหม่', 46, 8, 17, 400],
        ];

        $rows = [];
        foreach ($events as [$faculty, $category, $title, $description, $location, $days, $startHour, $endHour, $capacity]) {
            $rows[] = [
                'faculty_id' => $faculties[$faculty],
                'category_id' => $categories[$category],
                // CAMT events are created by the CAMT SMO; the rest by the university admin.
                'created_by' => $faculty === 'CAMT' ? $smoId : $adminId,
                'title' => $title,
                'description' => $description,
                'image' => null,
                'location' => $location,
                'start_at' => now()->addDays($days)->setTime($startHour, 0),
                'end_at' => now()->addDays($days)->setTime($endHour, 0),
                'capacity' => $capacity,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('events')->insert($rows);
    }
}
