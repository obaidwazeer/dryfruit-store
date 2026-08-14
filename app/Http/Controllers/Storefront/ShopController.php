<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
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

            /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

            ->when($request->filled('search'), function ($query) use ($request) {

                $search = trim($request->input('search'));

                $query->where(function ($query) use ($search) {

                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere(
                            'short_description',
                            'like',
                            "%{$search}%"
                        );

                });

            })

            /*
            |--------------------------------------------------------------------------
            | Category Filter
            |--------------------------------------------------------------------------
            */

            ->when($request->filled('category'), function ($query) use ($request) {

                $category = $request->input('category');

                $query->whereHas('categories', function ($query) use ($category) {

                    $query->where('slug', $category);

                });

            })

            /*
|--------------------------------------------------------------------------
| Availability Filter
|--------------------------------------------------------------------------
*/
            ->when($request->input('availability') === 'in_stock', function ($query) {

                $query->whereHas('variants', function ($query) {
                    $query
                        ->where('is_active', true)
                        ->where('stock_quantity', '>', 0);
                });

            })
            ->when($request->input('availability') === 'out_of_stock', function ($query) {

                $query->whereDoesntHave('variants', function ($query) {
                    $query
                        ->where('is_active', true)
                        ->where('stock_quantity', '>', 0);
                });

            })

            /*
            |--------------------------------------------------------------------------
            | Sorting
            |--------------------------------------------------------------------------
            */

            ->when(
                $request->input('sort') === 'name_asc',
                fn ($query) => $query->orderBy('name')
            )

            ->when(
                $request->input('sort') === 'name_desc',
                fn ($query) => $query->orderByDesc('name')
            )

            ->when(
                $request->input('sort') === 'price_asc',
                function ($query) {

                    $query->orderBy(
                        ProductVariant::select('price')
                            ->whereColumn(
                                'product_variants.product_id',
                                'products.id'
                            )
                            ->where('is_active', true)
                            ->orderBy('price')
                            ->limit(1),
                        'asc'
                    );

                }
            )

            ->when(
                $request->input('sort') === 'price_desc',
                function ($query) {

                    $query->orderBy(
                        ProductVariant::select('price')
                            ->whereColumn(
                                'product_variants.product_id',
                                'products.id'
                            )
                            ->where('is_active', true)
                            ->orderByDesc('price')
                            ->limit(1),
                        'desc'
                    );

                }
            )

            /*
            |--------------------------------------------------------------------------
            | Default / Featured Sorting
            |--------------------------------------------------------------------------
            */

            ->when(
                ! $request->filled('sort') ||
                $request->input('sort') === 'featured',
                fn ($query) => $query
                    ->orderByDesc('is_featured')
                    ->orderBy('sort_order')
                    ->orderBy('name')
            )

            ->paginate(12)

            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
            ]);

        return view(
            'storefront.shop.index',
            compact(
                'products',
                'categories'
            )
        );
    }
}
