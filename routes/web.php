<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MyEventController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', static fn () => redirect()->route('events.list'));

// Don't let the browser cache pages, so "Back" after logout can't show private data.
Route::middleware([
    'cache.headers:no_store;no_cache;must_revalidate;max_age=0',
])->group(static function (): void {

    Route::controller(LoginController::class)
        ->prefix('/auth')
        ->group(static function (): void {
            Route::get('/login', 'showForm')->name('login');
            Route::post('/login', 'authenticate')->name('authenticate');
            Route::post('/logout', 'logout')->name('logout');
        });

    // ---------- Routes that require login ----------
    // Declared before the public routes, so "/events/create" is not
    // taken as an event with code "create" by "/events/{event}".
    Route::middleware(['auth'])->group(static function (): void {

        Route::controller(EventController::class)
            ->prefix('/events')
            ->name('events.')
            ->group(static function (): void {
                Route::get('/create', 'showCreateForm')->name('create-form');
                Route::post('', 'create')->name('create');
                Route::get('/{event}/update', 'showUpdateForm')->name('update-form');
                Route::post('/{event}/update', 'update')->name('update');
                Route::post('/{event}/delete', 'delete')->name('delete');
                Route::post('/{event}/register', 'register')->name('register');
                Route::post('/{event}/unregister', 'unregister')->name('unregister');
            });

        Route::controller(LocationController::class)
            ->prefix('/locations')
            ->name('locations.')
            ->group(static function (): void {
                Route::get('/create', 'showCreateForm')->name('create-form');
                Route::post('', 'create')->name('create');
                Route::get('/{location}/update', 'showUpdateForm')->name('update-form');
                Route::post('/{location}/update', 'update')->name('update');
                Route::post('/{location}/delete', 'delete')->name('delete');
            });

        Route::controller(CategoryController::class)
            ->prefix('/categories')
            ->name('categories.')
            ->group(static function (): void {
                Route::get('/create', 'showCreateForm')->name('create-form');
                Route::post('', 'create')->name('create');
                Route::get('/{category}/update', 'showUpdateForm')->name('update-form');
                Route::post('/{category}/update', 'update')->name('update');
                Route::post('/{category}/delete', 'delete')->name('delete');
            });

        Route::get('/my-events', [MyEventController::class, 'list'])->name('my-events.list');

        Route::controller(UserController::class)
            ->prefix('/users')
            ->name('users.')
            ->group(static function (): void {
                Route::get('', 'list')->name('list');
                Route::get('/create', 'showCreateForm')->name('create-form');
                Route::post('', 'create')->name('create');
                Route::get('/{user}', 'view')->name('view');
                Route::get('/{user}/update', 'showUpdateForm')->name('update-form');
                Route::post('/{user}/update', 'update')->name('update');
                Route::post('/{user}/delete', 'delete')->name('delete');
            });
    });

    // ---------- Public routes (guests can see these) ----------
    Route::controller(EventController::class)
        ->prefix('/events')
        ->name('events.')
        ->group(static function (): void {
            Route::get('', 'list')->name('list');
            Route::get('/{event}', 'view')->name('view');
        });

    Route::controller(LocationController::class)
        ->prefix('/locations')
        ->name('locations.')
        ->group(static function (): void {
            Route::get('', 'list')->name('list');
            Route::get('/{location}', 'view')->name('view');
        });

    Route::controller(CategoryController::class)
        ->prefix('/categories')
        ->name('categories.')
        ->group(static function (): void {
            Route::get('', 'list')->name('list');
            Route::get('/{category}', 'view')->name('view');
        });
});
