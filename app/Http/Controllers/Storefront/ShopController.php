<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->where('status', 'active')
            ->with([
                'categories:id,name,slug',
                'images' => function ($query) {
                    $query
                        ->where('is_primary', true)
                        ->orderBy('sort_order');
                },
                'variants' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('weight_grams');
                },
            ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->input('search'));

                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $category = $request->input('category');

                $query->whereHas('categories', function ($query) use ($category) {
                    $query->where('slug', $category);
                });
            })
            ->when(
                $request->input('sort') === 'name_asc',
                fn ($query) => $query->orderBy('name')
            )
            ->when(
                $request->input('sort') === 'name_desc',
                fn ($query) => $query->orderByDesc('name')
            )
            ->when(
                $request->input('sort') === 'name_asc',
                fn ($query) => $query->orderBy('name')
            )
            ->when(
                $request->input('sort') === 'name_desc',
                fn ($query) => $query->orderByDesc('name')
            )
            ->when(
                $request->input('sort') === 'featured' || ! $request->filled('sort'),
                fn ($query) => $query
                    ->orderByDesc('is_featured')
                    ->orderBy('sort_order')
                    ->orderBy('name')
            )
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
            ]);

        return view('storefront.shop.index', compact(
            'products',
            'categories'
        ));
    }
}
