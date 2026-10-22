<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

// "List Event" menu: what this list shows depends on the logged-in user's role.
class MyEventController extends Controller
{
    function list(): View
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            $title = 'Events I Registered';
            $query = $user->events();
        } elseif ($user->isSmo()) {
            $title = 'Events I Created';
            $query = $user->createdEvents()->withCount('students');
        } else {
            $title = 'All Events';
            $query = Event::withCount('students');
        }

        return view('my-events.my-event-list', [
            'title' => $title,
            'events' => $query->with(['location', 'category'])
                ->orderBy('start_at')
                ->paginate(SearchableController::MAX_ITEMS),
        ]);
    }
}
