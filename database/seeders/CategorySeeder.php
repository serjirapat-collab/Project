<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['CT01', 'ร้องเพลง', 'ประกวดร้องเพลงและคาราโอเกะ'],
            ['CT02', 'ตลาด', 'ตลาดนัดและการออกร้าน'],
            ['CT03', 'สัตว์เลี้ยง', 'กิจกรรมเกี่ยวกับสัตว์เลี้ยง'],
            ['CT04', 'คอนเสิร์ต', 'การแสดงดนตรีและคอนเสิร์ต'],
            ['CT05', 'แข่งเกม', 'การแข่งขันเกมและอีสปอร์ต'],
            ['CT06', 'นันทนาการ', 'กิจกรรมสันทนาการและสานสัมพันธ์'],
        ];

        foreach ($categories as [$code, $name, $description]) {
            Category::create([
                'code' => $code,
                'name' => $name,
                'description' => $description,
            ]);
        }
    }
}
