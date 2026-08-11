<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('categories')
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $query->where(
                        'name',
                        'like',
                        '%'.$request->string('search').'%'
                    );
                }
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(
        StoreProductRequest $request
    ): RedirectResponse {
        $data = $request->validated();

        $categoryIds = $data['categories'];

        unset($data['categories']);

        $data['slug'] = $this->generateUniqueSlug(
            $data['slug'] ?? $data['name']
        );

        $data['is_featured'] = $request->boolean('is_featured');

        $product = DB::transaction(function () use (
            $data,
            $categoryIds
        ) {
            $product = Product::create($data);

            $product->categories()->sync($categoryIds);

            return $product;
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $product->load([
            'categories',
            'images',
        ]);

        return view(
            'admin.products.edit',
            compact('product', 'categories')
        );
    }

    public function update(
        UpdateProductRequest $request,
        Product $product
    ): RedirectResponse {
        $data = $request->validated();

        $categoryIds = $data['categories'];

        unset($data['categories']);

        $data['slug'] = $this->generateUniqueSlug(
            $data['slug'] ?? $data['name'],
            $product->id
        );

        $data['is_featured'] = $request->boolean('is_featured');

        DB::transaction(function () use (
            $product,
            $data,
            $categoryIds
        ) {
            $product->update($data);

            $product->categories()->sync($categoryIds);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function generateUniqueSlug(
        string $value,
        ?int $ignoreProductId = null
    ): string {
        $baseSlug = Str::slug($value);

        if ($baseSlug === '') {
            $baseSlug = 'product';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (
            Product::withTrashed()
                ->where('slug', $slug)
                ->when(
                    $ignoreProductId,
                    fn ($query) => $query->where(
                        'id',
                        '!=',
                        $ignoreProductId
                    )
                )
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
