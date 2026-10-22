@extends('layouts.main')
@section('title', $title)

@section('content')
    <form action="{{ route('users.list') }}" method="get" class="search-bar">
        <input type="search" name="term" value="{{ $criteria['term'] }}" placeholder="Search email, name or role">
        <button type="submit" class="button">Search</button>
        @can('create', \App\Models\User::class)
            <a href="{{ route('users.create-form') }}" class="button button-accent">+ Create User</a>
        @endcan
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Role</th>
                <th>Faculty</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td><a href="{{ route('users.view', ['user' => $user->email]) }}" class="link code">{{ $user->email }}</a></td>
                    <td>{{ $user->name }}</td>
                    <td><span class="badge">{{ $user->role }}</span></td>
                    <td>{{ $user->facultyName() ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $users->withQueryString()->links() }}
@endsection
