<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Location;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class LocationController extends SearchableController
{
    #[\Override]
    function getQuery(): Builder
    {
        return Location::orderBy('code');
    }

    function getRules(): array
    {
        return [
            'code' => ['required', 'max:64'],
            'name' => ['required', 'max:255'],
            'description' => ['required'],
        ];
    }

    // Filters: ?term=, ?event=CODE (from an event page: shows that event's location).
    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('list', Location::class);

        $params = $request->getQueryParams();
        $criteria = [
            'term' => $params['term'] ?? '',
            'event' => $params['event'] ?? '',
        ];

        $query = $this->search($criteria['term'])->withCount('events');

        $event = null;
        if ($criteria['event'] !== '') {
            $event = Event::where('code', $criteria['event'])->firstOrFail();
            $query->where('id', $event->location_id);
        }

        return view('locations.location-list', [
            'title' => 'Location',
            'criteria' => $criteria,
            'event' => $event,
            'locations' => $query->paginate(static::MAX_ITEMS),
        ]);
    }

    function view(string $location): View
    {
        $location = $this->find($location);
        Gate::authorize('view', $location);

        return view('locations.location-view', [
            'title' => $location->name,
            'location' => $location,
            'events' => $location->events()->with('category')->orderBy('start_at')->get(),
        ]);
    }

    function showCreateForm(): View
    {
        Gate::authorize('create', Location::class);

        return view('locations.location-form', [
            'title' => 'Create Location',
            'location' => null,
        ]);
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', Location::class);

        $rules = $this->getRules();
        $rules['code'][] = 'unique:locations,code';
        $validator = Validator::make($request->getParsedBody() ?? [], $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            $location = Location::create($validator->validated());

            return redirect()
                ->route('locations.view', ['location' => $location->code])
                ->with('status', "Location {$location->code} was created.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => 'Cannot save the location: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }

    function showUpdateForm(string $location): View
    {
        $location = $this->find($location);
        Gate::authorize('update', $location);

        return view('locations.location-form', [
            'title' => "Update {$location->code}",
            'location' => $location,
        ]);
    }

    function update(string $location, ServerRequestInterface $request): RedirectResponse
    {
        $location = $this->find($location);
        Gate::authorize('update', $location);

        $rules = $this->getRules();
        $rules['code'][] = "unique:locations,code,{$location->id}";
        $validator = Validator::make($request->getParsedBody() ?? [], $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            $location->fill($validator->validated());
            $location->save();

            return redirect()
                ->route('locations.view', ['location' => $location->code])
                ->with('status', "Location {$location->code} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => 'Cannot save the location: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }

    function delete(string $location): RedirectResponse
    {
        $location = $this->find($location);
        Gate::authorize('delete', $location);

        if ($location->events()->exists()) {
            return redirect()->back()->withErrors([
                'alert' => "Cannot delete {$location->name}: it still has events. Move or delete those events first.",
            ]);
        }

        try {
            $location->delete();

            return redirect()
                ->route('locations.list')
                ->with('status', "Location {$location->code} was deleted.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'alert' => 'Cannot delete the location: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }
}
