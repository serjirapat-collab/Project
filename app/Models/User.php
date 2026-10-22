<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// email and role are set explicitly in UserController, never from mass assignment.
#[Fillable(['name', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    const array ROLES = ['ADMIN', 'SMO', 'STUDENT'];

    // Faculty code => email domain, e.g. student@camt.com belongs to CAMT.
    const array FACULTY_DOMAINS = [
        'CAMT' => 'camt.com',
        'ENG' => 'eng.com',
        'MED' => 'med.com',
    ];

    const array FACULTY_NAMES = [
        'CAMT' => 'CAMT',
        'ENG' => 'Engineering',
        'MED' => 'Medicine',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    static function facultyFromEmail(string $email): ?string
    {
        $domain = \strtolower(\substr(\strrchr($email, '@') ?: '', 1));
        $faculty = \array_search($domain, static::FACULTY_DOMAINS, true);

        return $faculty === false ? null : $faculty;
    }

    function faculty(): ?string
    {
        return $this->isAdministrator() ? null : static::facultyFromEmail($this->email);
    }

    function facultyName(): ?string
    {
        return static::FACULTY_NAMES[$this->faculty()] ?? null;
    }

    function isAdministrator(): bool
    {
        return $this->role === 'ADMIN';
    }

    function isSmo(): bool
    {
        return $this->role === 'SMO';
    }

    function isStudent(): bool
    {
        return $this->role === 'STUDENT';
    }

    // Events this student registered for.
    function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    // Events this SMO (or admin) created.
    function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'creator_id');
    }
}
