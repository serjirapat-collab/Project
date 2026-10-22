<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController extends SearchableController
{
    #[\Override]
    function getQuery(): Builder
    {
        return Category::orderBy('code');
    }

    function getRules(): array
    {
        return [
            'code' => ['required', 'max:64'],
            'name' => ['required', 'max:255'],
            'description' => ['required'],
        ];
    }

    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('list', Category::class);

        $criteria = [
            'term' => $request->getQueryParams()['term'] ?? '',
        ];

        return view('categories.category-list', [
            'title' => 'Category',
            'criteria' => $criteria,
            'categories' => $this->search($criteria['term'])
                ->withCount('events')
                ->paginate(static::MAX_ITEMS),
        ]);
    }

    function view(string $category): View
    {
        $category = $this->find($category)->loadCount('events');
        Gate::authorize('view', $category);

        return view('categories.category-view', [
            'title' => $category->name,
            'category' => $category,
        ]);
    }

    function showCreateForm(): View
    {
        Gate::authorize('create', Category::class);

        return view('categories.category-form', [
            'title' => 'Create Category',
            'category' => null,
        ]);
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        $rules = $this->getRules();
        $rules['code'][] = 'unique:categories,code';
        $validator = Validator::make($request->getParsedBody() ?? [], $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            $category = Category::create($validator->validated());

            return redirect()
                ->route('categories.view', ['category' => $category->code])
                ->with('status', "Category {$category->code} was created.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => 'Cannot save the category: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }

    function showUpdateForm(string $category): View
    {
        $category = $this->find($category);
        Gate::authorize('update', $category);

        return view('categories.category-form', [
            'title' => "Update {$category->code}",
            'category' => $category,
        ]);
    }

    function update(string $category, ServerRequestInterface $request): RedirectResponse
    {
        $category = $this->find($category);
        Gate::authorize('update', $category);

        $rules = $this->getRules();
        $rules['code'][] = "unique:categories,code,{$category->id}";
        $validator = Validator::make($request->getParsedBody() ?? [], $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            $category->fill($validator->validated());
            $category->save();

            return redirect()
                ->route('categories.view', ['category' => $category->code])
                ->with('status', "Category {$category->code} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => 'Cannot save the category: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }

    function delete(string $category): RedirectResponse
    {
        $category = $this->find($category);
        Gate::authorize('delete', $category);

        if ($category->events()->exists()) {
            return redirect()->back()->withErrors([
                'alert' => "Cannot delete {$category->name}: it still has events. Move or delete those events first.",
            ]);
        }

        try {
            $category->delete();

            return redirect()
                ->route('categories.list')
                ->with('status', "Category {$category->code} was deleted.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'alert' => 'Cannot delete the category: ' . ($excp->errorInfo[2] ?? $excp->getMessage()),
            ]);
        }
    }
}
