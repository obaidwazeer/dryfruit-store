<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $slug): View
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('status', 'active')
            ->with([
                'categories:id,name,slug',

                'images' => function ($query) {
                    $query
                        ->orderByDesc('is_primary')
                        ->orderBy('sort_order');
                },

                'variants' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('weight_grams');
                },
            ])
            ->firstOrFail();

        $relatedProducts = Product::query()
            ->where('status', 'active')
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn(
                    'categories.id',
                    $product->categories->pluck('id')
                );
            })
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
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(4)
            ->get();

        return view(
            'storefront.products.show',
            compact(
                'product',
                'relatedProducts'
            )
        );
    }
}
