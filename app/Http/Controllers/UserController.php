<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends SearchableController
{
    #[\Override]
    function getQuery(): Builder
    {
        return User::orderBy('email');
    }

    #[\Override]
    function getSearchFields(): array
    {
        return ['email', 'name', 'role'];
    }

    // Users are found by email instead of code.
    #[\Override]
    function find(string $code): Model
    {
        return $this->getQuery()->where('email', $code)->firstOrFail();
    }

    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('list', User::class);

        $criteria = [
            'term' => $request->getQueryParams()['term'] ?? '',
        ];

        return view('users.user-list', [
            'title' => 'User',
            'criteria' => $criteria,
            'users' => $this->search($criteria['term'])->paginate(static::MAX_ITEMS),
        ]);
    }

    function view(string $user): View
    {
        $user = $this->find($user)->loadCount(['events', 'createdEvents']);
        Gate::authorize('view', $user);

        return view('users.user-view', [
            'title' => $user->email,
            'user' => $user,
        ]);
    }

    function showCreateForm(): View
    {
        Gate::authorize('create', User::class);

        return view('users.user-form', [
            'title' => 'Create User',
            'user' => null,
            'roles' => User::ROLES,
        ]);
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $validator = Validator::make(
            $request->getParsedBody() ?? [],
            [
                'email' => ['required', 'email', 'unique:users,email'],
                'name' => ['required'],
                'password' => ['required'],
                'role' => ['required', 'in:' . \implode(',', User::ROLES)],
            ],
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = $validator->validated();

        // Students and SMOs get their faculty from the email domain.
        if ($data['role'] !== 'ADMIN' && User::facultyFromEmail($data['email']) === null) {
            return redirect()->back()->withInput()->withErrors([
                'email' => 'Student and SMO emails must end with @camt.com, @eng.com or @med.com.',
            ]);
        }

        try {
            $user = new User();
            $user->fill($data);
            $user->email = $data['email'];
            $user->role = $data['role'];
            $user->save();

            return redirect()
                ->route('users.view', ['user' => $user->email])
                ->with('status', "User {$user->email} was created.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => 'Cannot save the user: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }

    function showUpdateForm(string $user): View
    {
        $user = $this->find($user);
        Gate::authorize('update', $user);

        return view('users.user-form', [
            'title' => "Update {$user->email}",
            'user' => $user,
            'roles' => User::ROLES,
        ]);
    }

    function update(string $user, ServerRequestInterface $request): RedirectResponse
    {
        $user = $this->find($user);
        Gate::authorize('update', $user);

        $data = $request->getParsedBody() ?? [];
        $canUpdateRole = Gate::allows('updateRole', $user);

        $validator = Validator::make(
            $data,
            [
                'name' => ['required'],
                'role' => $canUpdateRole ? ['required', 'in:' . \implode(',', User::ROLES)] : [],
            ],
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($canUpdateRole && $data['role'] !== 'ADMIN' && User::facultyFromEmail($user->email) === null) {
            return redirect()->back()->withInput()->withErrors([
                'role' => "{$user->email} has no faculty email domain, so it can only be ADMIN.",
            ]);
        }

        // Blank password means "keep the current password".
        if (($data['password'] ?? '') === '') {
            unset($data['password']);
        }

        try {
            $user->fill($data);
            if ($canUpdateRole) {
                $user->role = $data['role'];
            }
            $user->save();

            return redirect()
                ->route('users.view', ['user' => $user->email])
                ->with('status', "User {$user->email} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => 'Cannot save the user: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }

    function delete(string $user): RedirectResponse
    {
        $user = $this->find($user);
        Gate::authorize('delete', $user);

        try {
            $user->delete();

            return redirect()
                ->route('users.list')
                ->with('status', "User {$user->email} was deleted.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'alert' => 'Cannot delete the user: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }
}
