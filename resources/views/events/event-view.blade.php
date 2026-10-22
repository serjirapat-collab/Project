@extends('layouts.main')
@section('title', $title)

@section('content')
    <div class="actions">
        <a href="{{ route('events.list') }}" class="button button-outline">&lt; All Events</a>
        <a href="{{ route('locations.list', ['event' => $event->code]) }}" class="button button-outline">View Location</a>
        @can('update', $event)
            <a href="{{ route('events.update-form', ['event' => $event->code]) }}" class="button">Update</a>
        @endcan
        @can('delete', $event)
            <form action="{{ route('events.delete', ['event' => $event->code]) }}" method="post"
                onsubmit="return confirm('Delete {{ $event->code }}?')">
                @csrf
                <button type="submit" class="button button-danger">Delete</button>
            </form>
        @endcan
    </div>

    <div class="card">
        <dl class="detail">
            <dt>Code</dt>
            <dd class="code">{{ $event->code }}</dd>

            <dt>Event</dt>
            <dd>{{ $event->name }}</dd>

            <dt>Date &amp; Time</dt>
            <dd>{{ $event->start_at->format('d/m/Y H:i') }}</dd>

            <dt>Location</dt>
            <dd><a href="{{ route('locations.view', ['location' => $event->location->code]) }}" class="link">{{ $event->location->name }}</a></dd>

            <dt>Category</dt>
            <dd><a href="{{ route('categories.view', ['category' => $event->category->code]) }}" class="link">{{ $event->category->name }}</a></dd>

            <dt>For faculty</dt>
            <dd><span class="badge badge-{{ strtolower($event->faculty) }}">{{ $event->facultyName() }}</span></dd>

            <dt>Created by</dt>
            <dd>{{ $event->creator?->name ?? '-' }}</dd>

            <dt>Registered</dt>
            <dd>{{ $event->students->count() }} students</dd>
        </dl>

        <p class="description">{{ $event->description }}</p>
    </div>

    {{-- Registration box: depends on who is looking. --}}
    <div class="card register-box">
        @guest
            <p>Please <a href="{{ route('login') }}" class="link">login</a> as a {{ $event->facultyName() }} student to register.</p>
        @else
            @can('register', $event)
                <form action="{{ route('events.register', ['event' => $event->code]) }}" method="post">
                    @csrf
                    <button type="submit" class="button button-accent">Register for this event</button>
                </form>
            @elsecan('unregister', $event)
                <p>You are registered for this event.</p>
                <form action="{{ route('events.unregister', ['event' => $event->code]) }}" method="post">
                    @csrf
                    <button type="submit" class="button button-danger">Cancel registration</button>
                </form>
            @else
                @if (Auth::user()->isStudent())
                    <p>This event is for <b>{{ $event->facultyName() }}</b> students only.</p>
                @else
                    <p>Only students can register for events.</p>
                @endif
            @endcan
        @endguest
    </div>

    @can('update', $event)
        <h2>Registered Students</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Registered at</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($event->students as $student)
                    <tr>
                        <td class="code">{{ $student->email }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->pivot->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="empty">No one has registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endcan
@endsection
