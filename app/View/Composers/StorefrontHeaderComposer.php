<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class StorefrontHeaderComposer
{
    public function compose(View $view): void
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
            ]);

        $cartCount = collect(session('cart', []))
            ->sum('quantity');

        $view->with([
            'categories' => $categories,
            'cartCount' => $cartCount,
        ]);
    }
}
