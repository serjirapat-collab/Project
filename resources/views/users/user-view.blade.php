@extends('layouts.main')
@section('title', $title)

@section('content')
    <div class="actions">
        <a href="{{ route('users.list') }}" class="button button-outline">&lt; All Users</a>
        @can('update', $user)
            <a href="{{ route('users.update-form', ['user' => $user->email]) }}" class="button">Update</a>
        @endcan
        @can('delete', $user)
            <form action="{{ route('users.delete', ['user' => $user->email]) }}" method="post"
                onsubmit="return confirm('Delete {{ $user->email }}?')">
                @csrf
                <button type="submit" class="button button-danger">Delete</button>
            </form>
        @endcan
    </div>

    <div class="card">
        <dl class="detail">
            <dt>Email</dt>
            <dd class="code">{{ $user->email }}</dd>

            <dt>Name</dt>
            <dd>{{ $user->name }}</dd>

            <dt>Role</dt>
            <dd><span class="badge">{{ $user->role }}</span></dd>

            <dt>Faculty</dt>
            <dd>{{ $user->facultyName() ?? '-' }}</dd>

            @if ($user->isStudent())
                <dt>Registered events</dt>
                <dd>{{ $user->events_count }}</dd>
            @else
                <dt>Created events</dt>
                <dd>{{ $user->created_events_count }}</dd>
            @endif
        </dl>
    </div>
@endsection
