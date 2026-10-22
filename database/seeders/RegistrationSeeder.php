<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        // Each demo student starts with two events of their own faculty.
        $registrations = [
            'student@camt.com' => ['EV001', 'EV004'],
            'student@eng.com' => ['EV010', 'EV013'],
            'student@med.com' => ['EV019', 'EV022'],
        ];

        foreach ($registrations as $email => $eventCodes) {
            $student = User::where('email', $email)->firstOrFail();
            $student->events()->attach(Event::whereIn('code', $eventCodes)->get());
        }
    }
}
