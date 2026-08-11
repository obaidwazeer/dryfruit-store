<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = $data['slug']
            ?? Str::slug($data['name']);

        $data['is_active'] = $request->boolean('is_active');

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ): RedirectResponse {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active');

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        abort_unless(
            request()->user()?->can('categories.delete'),
            403
        );

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
