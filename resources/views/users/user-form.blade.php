{{-- Used for both create ($user is null) and update. --}}
@extends('layouts.main')
@section('title', $title)

@section('content')
    <form action="{{ $user ? route('users.update', ['user' => $user->email]) : route('users.create') }}" method="post"
        class="card">
        @csrf
        <label class="field">
            <span>Email {{ $user ? '' : '*' }}</span>
            @if ($user)
                {{-- Email cannot be changed: it decides the user's faculty. --}}
                <span class="code">{{ $user->email }}</span>
            @else
                <input type="email" name="email" value="{{ old('email') }}" placeholder="student@camt.com" required>
            @endif
        </label>
        <label class="field">
            <span>Name *</span>
            <input type="text" name="name" value="{{ old('name', $user?->name) }}" required>
        </label>
        <label class="field">
            <span>Role *</span>
            @if ($user === null || Gate::allows('updateRole', $user))
                <select name="role" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected($role === old('role', $user?->role ?? 'STUDENT'))>{{ $role }}</option>
                    @endforeach
                </select>
            @else
                <span class="badge">{{ $user->role }}</span>
                <small class="hint">You cannot change your own role.</small>
            @endif
        </label>
        <label class="field">
            <span>Password {{ $user ? '' : '*' }}</span>
            <input type="password" name="password" @required($user === null)
                placeholder="{{ $user ? 'Leave blank to keep the current password' : '' }}">
        </label>
        <p class="hint">Students and SMOs belong to the faculty in their email: @camt.com = CAMT, @eng.com = Engineering, @med.com = Medicine.</p>

        <div class="actions">
            <button type="submit" class="button">{{ $user ? 'Update' : 'Create' }}</button>
            <a href="{{ $user ? route('users.view', ['user' => $user->email]) : route('users.list') }}"
                class="button button-outline">Cancel</a>
        </div>
    </form>
@endsection
