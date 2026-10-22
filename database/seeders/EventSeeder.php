<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // [code, name, faculty, category code, location code, start at]
        $events = [
            // CAMT (created by smo@camt.com)
            ['EV001', 'ประกวดร้องเพลง CAMT Singer', 'CAMT', 'CT01', 'LC04', '2026-11-05 17:00'],
            ['EV002', 'ตลาดนัดงานดีไซน์ CAMT', 'CAMT', 'CT02', 'LC04', '2026-11-12 16:00'],
            ['EV003', 'CAMT E-Sport Tournament', 'CAMT', 'CT05', 'LC04', '2026-11-19 13:00'],
            ['EV004', 'คอนเสิร์ตริมอ่างแก้ว CAMT', 'CAMT', 'CT04', 'LC01', '2026-11-28 18:00'],
            ['EV005', 'Pet Photo Day ถ่ายรูปสัตว์เลี้ยง', 'CAMT', 'CT03', 'LC02', '2026-12-05 09:00'],
            ['EV006', 'CAMT Walk Rally', 'CAMT', 'CT06', 'LC02', '2026-12-12 08:00'],
            ['EV007', 'Board Game Battle', 'CAMT', 'CT05', 'LC05', '2026-12-19 13:00'],
            ['EV008', 'CAMT Freshy Night', 'CAMT', 'CT04', 'LC03', '2027-01-15 18:00'],
            ['EV009', 'Karaoke Night CAMT', 'CAMT', 'CT01', 'LC05', '2027-01-29 18:00'],

            // Engineering (created by smo@eng.com)
            ['EV010', 'ประกวดร้องเพลงวิศวะ', 'ENG', 'CT01', 'LC03', '2026-11-06 17:00'],
            ['EV011', 'ตลาดนัดของมือสองวิศวะ', 'ENG', 'CT02', 'LC02', '2026-11-13 16:00'],
            ['EV012', 'แข่งเกมหุ่นยนต์ Robot Game', 'ENG', 'CT05', 'LC03', '2026-11-20 09:00'],
            ['EV013', 'Engineering Concert', 'ENG', 'CT04', 'LC03', '2026-11-27 18:00'],
            ['EV014', 'พาน้องหมาเดินเล่นรอบอ่างแก้ว', 'ENG', 'CT03', 'LC01', '2026-12-06 07:00'],
            ['EV015', 'กีฬาสีวิศวะสัมพันธ์', 'ENG', 'CT06', 'LC03', '2026-12-13 08:00'],
            ['EV016', 'แข่งเกม RoV วิศวะ', 'ENG', 'CT05', 'LC05', '2026-12-20 13:00'],
            ['EV017', 'คอนเสิร์ตการกุศลวิศวะ', 'ENG', 'CT04', 'LC01', '2027-01-16 18:00'],
            ['EV018', 'นันทนาการรับน้องวิศวะ', 'ENG', 'CT06', 'LC02', '2027-01-30 08:00'],

            // Medicine (created by smo@med.com)
            ['EV019', 'ร้องเพลงเพื่อผู้ป่วย Music for Patients', 'MED', 'CT01', 'LC05', '2026-11-07 10:00'],
            ['EV020', 'ตลาดสุขภาพ Healthy Market', 'MED', 'CT02', 'LC02', '2026-11-14 16:00'],
            ['EV021', 'Pet Therapy Day สัตว์เลี้ยงบำบัด', 'MED', 'CT03', 'LC01', '2026-11-21 09:00'],
            ['EV022', 'Med Concert', 'MED', 'CT04', 'LC03', '2026-11-29 18:00'],
            ['EV023', 'แข่งเกมตอบคำถามสุขภาพ Med Quiz', 'MED', 'CT05', 'LC05', '2026-12-07 13:00'],
            ['EV024', 'Fun Run ริมอ่างแก้ว', 'MED', 'CT06', 'LC01', '2026-12-14 06:00'],
            ['EV025', 'ตลาดนัดหนังสือแพทย์มือสอง', 'MED', 'CT02', 'LC05', '2026-12-21 10:00'],
            ['EV026', 'Med Freshy Night', 'MED', 'CT04', 'LC03', '2027-01-17 18:00'],
            ['EV027', 'เกมสันทนาการสานสัมพันธ์หมอ', 'MED', 'CT06', 'LC02', '2027-01-31 08:00'],
        ];

        $smoEmails = [
            'CAMT' => 'smo@camt.com',
            'ENG' => 'smo@eng.com',
            'MED' => 'smo@med.com',
        ];

        foreach ($events as [$code, $name, $faculty, $categoryCode, $locationCode, $startAt]) {
            $event = new Event();
            $event->fill([
                'code' => $code,
                'name' => $name,
                'description' => "{$name}\nกิจกรรมสำหรับนักศึกษา " . User::FACULTY_NAMES[$faculty],
                'start_at' => $startAt,
            ]);
            $event->faculty = $faculty;
            $event->location()->associate(Location::where('code', $locationCode)->firstOrFail());
            $event->category()->associate(Category::where('code', $categoryCode)->firstOrFail());
            $event->creator()->associate(User::where('email', $smoEmails[$faculty])->firstOrFail());
            $event->save();
        }
    }
}
