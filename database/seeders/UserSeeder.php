<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    // Demo accounts only. Every account uses the same password: "password".
    public function run(): void
    {
        $users = [
            ['admin@eventhub.com', 'Administrator', 'ADMIN'],
            ['smo@camt.com', 'SMO CAMT', 'SMO'],
            ['smo@eng.com', 'SMO Engineering', 'SMO'],
            ['smo@med.com', 'SMO Medicine', 'SMO'],
            ['student@camt.com', 'Student CAMT', 'STUDENT'],
            ['student@eng.com', 'Student Engineering', 'STUDENT'],
            ['student@med.com', 'Student Medicine', 'STUDENT'],
        ];

        foreach ($users as [$email, $name, $role]) {
            $user = new User();
            $user->fill(['name' => $name, 'password' => 'password']);
            $user->email = $email;
            $user->role = $role;
            $user->save();
        }
    }
}
