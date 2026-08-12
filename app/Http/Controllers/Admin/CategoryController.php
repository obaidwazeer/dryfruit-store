<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->withCount('products')
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

        /*
        |--------------------------------------------------------------------------
        | Store Category Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {
            $data['image_path'] = $request
                ->file('image')
                ->store('categories', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Remove Uploaded File From Mass Assignment Data
        |--------------------------------------------------------------------------
        */

        unset($data['image']);

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

        /*
        |--------------------------------------------------------------------------
        | Handle New Category Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            /*
            |--------------------------------------------------------------------------
            | Store New Image First
            |--------------------------------------------------------------------------
            */

            $newImagePath = $request
                ->file('image')
                ->store('categories', 'public');

            /*
            |--------------------------------------------------------------------------
            | Remember Old Image
            |--------------------------------------------------------------------------
            */

            $oldImagePath = $category->image_path;

            /*
            |--------------------------------------------------------------------------
            | Update Category
            |--------------------------------------------------------------------------
            */

            $data['image_path'] = $newImagePath;

            unset($data['image']);

            $category->update($data);

            /*
            |--------------------------------------------------------------------------
            | Delete Old Image
            |--------------------------------------------------------------------------
            */

            if ($oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | No New Image
            |--------------------------------------------------------------------------
            */

            unset($data['image']);

            $category->update($data);
        }

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

        /*
        |--------------------------------------------------------------------------
        | Delete Category
        |--------------------------------------------------------------------------
        */

        $category->delete();

        /*
        |--------------------------------------------------------------------------
        | Delete Category Image
        |--------------------------------------------------------------------------
        */

        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
