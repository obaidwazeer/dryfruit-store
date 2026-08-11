<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function store(
        Request $request,
        Product $product
    ): RedirectResponse {
        $request->validate([
            'images' => [
                'required',
                'array',
                'min:1',
                'max:10',
            ],

            'images.*' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $currentImageCount = $product->images()->count();

        if ($currentImageCount + count($request->file('images')) > 10) {
            return back()->withErrors([
                'images' => 'A product can have a maximum of 10 images.',
            ]);
        }

        foreach ($request->file('images') as $index => $image) {

            $path = $image->store(
                'products/' . $product->id,
                'public'
            );

            $product->images()->create([
                'path' => $path,
                'alt_text' => $product->name,
                'is_primary' => $currentImageCount === 0 && $index === 0,
                'sort_order' => $currentImageCount + $index,
            ]);
        }

        return back()->with(
            'success',
            'Product images uploaded successfully.'
        );
    }

    public function primary(
        Product $product,
        ProductImage $image
    ): RedirectResponse {
        abort_unless(
            $image->product_id === $product->id,
            404
        );

        $product->images()->update([
            'is_primary' => false,
        ]);

        $image->update([
            'is_primary' => true,
        ]);

        return back()->with(
            'success',
            'Primary image updated successfully.'
        );
    }

    public function destroy(
        Product $product,
        ProductImage $image
    ): RedirectResponse {
        abort_unless(
            $image->product_id === $product->id,
            404
        );

        $wasPrimary = $image->is_primary;

        Storage::disk('public')->delete($image->path);

        $image->delete();

        if ($wasPrimary) {

            $nextImage = $product->images()
                ->orderBy('sort_order')
                ->first();

            if ($nextImage) {
                $nextImage->update([
                    'is_primary' => true,
                ]);
            }
        }

        return back()->with(
            'success',
            'Product image deleted successfully.'
        );
    }
}
