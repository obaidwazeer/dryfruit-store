<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Category $category): View
    {
        abort_unless($category->is_active, 404);

        $products = Product::query()
            ->where('status', 'active')
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', $category->id);
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
            ->paginate(12)
            ->withQueryString();

        return view('storefront.categories.show', compact(
            'category',
            'products'
        ));
    }
}
