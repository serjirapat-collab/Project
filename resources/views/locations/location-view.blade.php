@extends('layouts.main')
@section('title', $title)

@section('content')
    <div class="actions">
        <a href="{{ route('locations.list') }}" class="button button-outline">&lt; All Locations</a>
        <a href="{{ route('events.list', ['location' => $location->code]) }}" class="button button-outline">View Event</a>
        @can('update', $location)
            <a href="{{ route('locations.update-form', ['location' => $location->code]) }}" class="button">Update</a>
        @endcan
        @can('delete', $location)
            <form action="{{ route('locations.delete', ['location' => $location->code]) }}" method="post"
                onsubmit="return confirm('Delete {{ $location->name }}?')">
                @csrf
                <button type="submit" class="button button-danger">Delete</button>
            </form>
        @endcan
    </div>

    <div class="card">
        <dl class="detail">
            <dt>Code</dt>
            <dd class="code">{{ $location->code }}</dd>

            <dt>Location</dt>
            <dd>{{ $location->name }}</dd>

            <dt>Description</dt>
            <dd>{{ $location->description }}</dd>
        </dl>
    </div>

    <h2>Events at {{ $location->name }}</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Event</th>
                <th>Date &amp; Time</th>
                <th>Category</th>
                <th>Faculty</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events as $event)
                <tr>
                    <td><a href="{{ route('events.view', ['event' => $event->code]) }}" class="link code">{{ $event->code }}</a></td>
                    <td>{{ $event->name }}</td>
                    <td class="nowrap">{{ $event->start_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $event->category->name }}</td>
                    <td><span class="badge badge-{{ strtolower($event->faculty) }}">{{ $event->facultyName() }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">No events at this location yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
