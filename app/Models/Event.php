<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// faculty, location, category and creator are set explicitly in EventController.
#[Fillable(['code', 'name', 'description', 'start_at'])]
class Event extends Model
{
    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
        ];
    }

    function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Students who registered for this event.
    function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    function facultyName(): string
    {
        return User::FACULTY_NAMES[$this->faculty] ?? $this->faculty;
    }
}
