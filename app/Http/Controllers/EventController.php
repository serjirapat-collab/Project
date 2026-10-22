<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class EventController extends SearchableController
{
    #[\Override]
    function getQuery(): Builder
    {
        return Event::orderBy('start_at')->orderBy('code');
    }

    // Admin can create events for any faculty; an SMO only for their own faculty.
    function getAllowedFaculties(User $user): array
    {
        if ($user->isAdministrator()) {
            return User::FACULTY_NAMES;
        }

        return [$user->faculty() => $user->facultyName()];
    }

    function getRules(User $user): array
    {
        return [
            'code' => ['required', 'max:64'],
            'name' => ['required', 'max:255'],
            'description' => ['required'],
            'start_at' => ['required', 'date'],
            'location' => ['required', 'exists:locations,code'],
            'category' => ['required', 'exists:categories,code'],
            'faculty' => ['required', 'in:' . \implode(',', \array_keys($this->getAllowedFaculties($user)))],
        ];
    }

    function fillEvent(Event $event, array $data): void
    {
        $event->fill($data);
        $event->faculty = $data['faculty'];
        $event->location()->associate(Location::where('code', $data['location'])->firstOrFail());
        $event->category()->associate(Category::where('code', $data['category'])->firstOrFail());
    }

    // Filters: ?term=, ?faculty=, ?location=CODE (from a location page), ?category=CODE (from a category page).
    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('list', Event::class);

        $params = $request->getQueryParams();
        $criteria = [
            'term' => $params['term'] ?? '',
            'faculty' => $params['faculty'] ?? '',
            'location' => $params['location'] ?? '',
            'category' => $params['category'] ?? '',
        ];

        $query = $this->search($criteria['term'])
            ->with(['location', 'category'])
            ->withCount('students');

        if ($criteria['faculty'] !== '') {
            $query->where('faculty', $criteria['faculty']);
        }

        $location = null;
        if ($criteria['location'] !== '') {
            $location = Location::where('code', $criteria['location'])->firstOrFail();
            $query->where('location_id', $location->id);
        }

        $category = null;
        if ($criteria['category'] !== '') {
            $category = Category::where('code', $criteria['category'])->firstOrFail();
            $query->where('category_id', $category->id);
        }

        return view('events.event-list', [
            'title' => 'Event',
            'criteria' => $criteria,
            'location' => $location,
            'category' => $category,
            'faculties' => User::FACULTY_NAMES,
            'events' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    function view(string $event): View
    {
        $event = $this->find($event)->load(['location', 'category', 'creator', 'students']);
        Gate::authorize('view', $event);

        return view('events.event-view', [
            'title' => $event->name,
            'event' => $event,
        ]);
    }

    function showCreateForm(): View
    {
        Gate::authorize('create', Event::class);

        return view('events.event-form', [
            'title' => 'Create Event',
            'event' => null,
            'locations' => Location::orderBy('code')->get(),
            'categories' => Category::orderBy('code')->get(),
            'faculties' => $this->getAllowedFaculties(Auth::user()),
        ]);
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', Event::class);
        $user = Auth::user();

        $rules = $this->getRules($user);
        $rules['code'][] = 'unique:events,code';
        $validator = Validator::make($request->getParsedBody() ?? [], $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            $event = new Event();
            $this->fillEvent($event, $validator->validated());
            $event->creator()->associate($user);
            $event->save();

            return redirect()
                ->route('events.view', ['event' => $event->code])
                ->with('status', "Event {$event->code} was created.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => 'Cannot save the event: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }

    function showUpdateForm(string $event): View
    {
        $event = $this->find($event)->load(['location', 'category']);
        Gate::authorize('update', $event);

        return view('events.event-form', [
            'title' => "Update {$event->code}",
            'event' => $event,
            'locations' => Location::orderBy('code')->get(),
            'categories' => Category::orderBy('code')->get(),
            'faculties' => $this->getAllowedFaculties(Auth::user()),
        ]);
    }

    function update(string $event, ServerRequestInterface $request): RedirectResponse
    {
        $event = $this->find($event);
        Gate::authorize('update', $event);

        $rules = $this->getRules(Auth::user());
        // The code must be unique, except for this event itself.
        $rules['code'][] = "unique:events,code,{$event->id}";
        $validator = Validator::make($request->getParsedBody() ?? [], $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            $this->fillEvent($event, $validator->validated());
            $event->save();

            return redirect()
                ->route('events.view', ['event' => $event->code])
                ->with('status', "Event {$event->code} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => 'Cannot save the event: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }

    function delete(string $event): RedirectResponse
    {
        $event = $this->find($event);
        Gate::authorize('delete', $event);

        try {
            $event->delete();

            return redirect()
                ->route('events.list')
                ->with('status', "Event {$event->code} was deleted.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'alert' => 'Cannot delete the event: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }

    function register(string $event): RedirectResponse
    {
        $event = $this->find($event);
        $user = Auth::user();

        if (Gate::denies('register', $event)) {
            // Tell the user why, instead of a bare 403 page.
            $message = match (true) {
                !$user->isStudent() => 'Only students can register for events.',
                $user->faculty() !== $event->faculty => "This event is for {$event->facultyName()} students only.",
                default => "You already registered for {$event->code}.",
            };

            return redirect()->back()->withErrors(['alert' => $message]);
        }

        try {
            $event->students()->attach($user);

            return redirect()
                ->back()
                ->with('status', "You registered for {$event->name}.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'alert' => 'Cannot register: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }

    function unregister(string $event): RedirectResponse
    {
        $event = $this->find($event);
        Gate::authorize('unregister', $event);

        $event->students()->detach(Auth::user());

        return redirect()
            ->back()
            ->with('status', "You cancelled your registration for {$event->name}.");
    }
}
