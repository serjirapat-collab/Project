<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

// Shared search and lookup for controllers whose records are found by code.
abstract class SearchableController extends Controller
{
    const int MAX_ITEMS = 10;

    abstract function getQuery(): Builder;

    // Columns the search term is matched against.
    function getSearchFields(): array
    {
        return ['code', 'name'];
    }

    // Every word in the term must match at least one search field.
    function search(string $term): Builder
    {
        $query = $this->getQuery();
        $fields = $this->getSearchFields();

        foreach (\preg_split('/\s+/', \trim($term), flags: \PREG_SPLIT_NO_EMPTY) as $word) {
            $query->where(static function (Builder $wordQuery) use ($word, $fields): void {
                foreach ($fields as $field) {
                    $wordQuery->orWhere($field, 'LIKE', "%{$word}%");
                }
            });
        }

        return $query;
    }

    function find(string $code): Model
    {
        return $this->getQuery()->where('code', $code)->firstOrFail();
    }
}
