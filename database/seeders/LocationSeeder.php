<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['LC01', 'อ่างแก้ว', 'อ่างเก็บน้ำหลังมหาวิทยาลัย มีลานริมน้ำสำหรับจัดกิจกรรมกลางแจ้ง'],
            ['LC02', 'ลานควายยิ้ม', 'ลานกิจกรรมกลางแจ้งใจกลางมหาวิทยาลัย'],
            ['LC03', 'ศูนย์กีฬา', 'ศูนย์กีฬามหาวิทยาลัยเชียงใหม่ ทั้งสนามและโรงยิม'],
            ['LC04', 'ใต้ CAMT', 'ใต้ถุนอาคารวิทยาลัยศิลปะ สื่อ และเทคโนโลยี'],
            ['LC05', 'Uniserv', 'อาคารสำนักบริการวิชาการ มีห้องประชุมและห้องจัดเลี้ยง'],
        ];

        foreach ($locations as [$code, $name, $description]) {
            Location::create([
                'code' => $code,
                'name' => $name,
                'description' => $description,
            ]);
        }
    }
}
