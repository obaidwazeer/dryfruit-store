<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentTransactionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\ShippingRateController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CategoryController as StoreFrontCategoryController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\PaymentAssistanceController;
use App\Http\Controllers\Storefront\PaymentController;
use App\Http\Controllers\Storefront\ProductController as StorefrontProductController;
use App\Http\Controllers\Storefront\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Guest Admin Routes
        |--------------------------------------------------------------------------
        */

        Route::middleware('guest')->group(function () {

            Route::get('/login', [
                AuthController::class,
                'showLogin',
            ])->name('login');

            Route::post('/login', [
                AuthController::class,
                'login',
            ])->name('login.store');

        });

        /*
        |--------------------------------------------------------------------------
        | Authenticated Admin Routes
        |--------------------------------------------------------------------------
        */

        Route::middleware('auth')->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Payment Transactions
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/payment-transactions/{paymentTransaction}',
                [PaymentTransactionController::class, 'show']
            )->name('admin.payment-transactions.show');

            Route::patch(
                '/payment-transactions/{paymentTransaction}/approve',
                [PaymentTransactionController::class, 'approve']
            )->name('admin.payment-transactions.approve');

            Route::patch(
                '/payment-transactions/{paymentTransaction}/reject',
                [PaymentTransactionController::class, 'reject']
            )->name('admin.payment-transactions.reject');

            /*
            |--------------------------------------------------------------------------
            | Shipment Status
            |--------------------------------------------------------------------------
            */

            Route::patch(
                '/orders/{order}/shipment/{shipment}/status',
                [ShipmentController::class, 'updateStatus']
            )->name('admin.orders.shipment.status');

            /*
            |--------------------------------------------------------------------------
            | Customers
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/customers',
                [CustomerController::class, 'index']
            )
                ->middleware('can:customers.view')
                ->name('customers.index');

            Route::get(
                '/customers/{customer}',
                [CustomerController::class, 'show']
            )
                ->middleware('can:customers.view')
                ->name('customers.show');

            /*
            |--------------------------------------------------------------------------
            | Shipment Management
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/orders/{order}/shipment',
                [ShipmentController::class, 'store']
            )
                ->middleware('can:orders.update')
                ->name('orders.shipment.store');

            Route::put(
                '/orders/{order}/shipment/{shipment}',
                [ShipmentController::class, 'update']
            )
                ->middleware('can:orders.update')
                ->name('orders.shipment.update');

            /*
            |--------------------------------------------------------------------------
            | Shipping Rates
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/shipping',
                [ShippingRateController::class, 'index']
            )
                ->middleware('can:shipping.view')
                ->name('shipping.index');

            Route::get(
                '/shipping/create',
                [ShippingRateController::class, 'create']
            )
                ->middleware('can:shipping.create')
                ->name('shipping.create');

            Route::post(
                '/shipping',
                [ShippingRateController::class, 'store']
            )
                ->middleware('can:shipping.create')
                ->name('shipping.store');

            Route::get(
                '/shipping/{shippingRate}/edit',
                [ShippingRateController::class, 'edit']
            )
                ->middleware('can:shipping.update')
                ->name('shipping.edit');

            Route::put(
                '/shipping/{shippingRate}',
                [ShippingRateController::class, 'update']
            )
                ->middleware('can:shipping.update')
                ->name('shipping.update');

            Route::delete(
                '/shipping/{shippingRate}',
                [ShippingRateController::class, 'destroy']
            )
                ->middleware('can:shipping.delete')
                ->name('shipping.destroy');

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/dashboard',
                [DashboardController::class, 'index']
            )
                ->middleware('can:dashboard.view')
                ->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | Categories
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/categories',
                [CategoryController::class, 'index']
            )
                ->middleware('can:categories.view')
                ->name('categories.index');

            Route::get(
                '/categories/create',
                [CategoryController::class, 'create']
            )
                ->middleware('can:categories.create')
                ->name('categories.create');

            Route::post(
                '/categories',
                [CategoryController::class, 'store']
            )
                ->middleware('can:categories.create')
                ->name('categories.store');

            Route::get(
                '/categories/{category}/edit',
                [CategoryController::class, 'edit']
            )
                ->middleware('can:categories.update')
                ->name('categories.edit');

            Route::put(
                '/categories/{category}',
                [CategoryController::class, 'update']
            )
                ->middleware('can:categories.update')
                ->name('categories.update');

            Route::delete(
                '/categories/{category}',
                [CategoryController::class, 'destroy']
            )
                ->middleware('can:categories.delete')
                ->name('categories.destroy');

            /*
            |--------------------------------------------------------------------------
            | Products
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/products',
                [ProductController::class, 'index']
            )
                ->middleware('can:products.view')
                ->name('products.index');

            Route::get(
                '/products/create',
                [ProductController::class, 'create']
            )
                ->middleware('can:products.create')
                ->name('products.create');

            Route::post(
                '/products',
                [ProductController::class, 'store']
            )
                ->middleware('can:products.create')
                ->name('products.store');

            Route::get(
                '/products/{product}/edit',
                [ProductController::class, 'edit']
            )
                ->middleware('can:products.update')
                ->name('products.edit');

            Route::put(
                '/products/{product}',
                [ProductController::class, 'update']
            )
                ->middleware('can:products.update')
                ->name('products.update');

            Route::delete(
                '/products/{product}',
                [ProductController::class, 'destroy']
            )
                ->middleware('can:products.delete')
                ->name('products.destroy');

            /*
            |--------------------------------------------------------------------------
            | Product Variants
            |--------------------------------------------------------------------------
            */

            Route::prefix('products/{product}/variants')
                ->name('products.variants.')
                ->group(function () {

                    Route::get(
                        '/',
                        [ProductVariantController::class, 'index']
                    )
                        ->middleware('can:products.update')
                        ->name('index');

                    Route::get(
                        '/create',
                        [ProductVariantController::class, 'create']
                    )
                        ->middleware('can:products.update')
                        ->name('create');

                    Route::post(
                        '/',
                        [ProductVariantController::class, 'store']
                    )
                        ->middleware('can:products.update')
                        ->name('store');

                    Route::get(
                        '/{variant}/edit',
                        [ProductVariantController::class, 'edit']
                    )
                        ->middleware('can:products.update')
                        ->name('edit');

                    Route::put(
                        '/{variant}',
                        [ProductVariantController::class, 'update']
                    )
                        ->middleware('can:products.update')
                        ->name('update');

                    Route::delete(
                        '/{variant}',
                        [ProductVariantController::class, 'destroy']
                    )
                        ->middleware('can:products.update')
                        ->name('destroy');

                });

            /*
            |--------------------------------------------------------------------------
            | Product Images
            |--------------------------------------------------------------------------
            */

            Route::prefix('products/{product}/images')
                ->name('products.images.')
                ->group(function () {

                    Route::post(
                        '/',
                        [ProductImageController::class, 'store']
                    )
                        ->middleware('can:products.update')
                        ->name('store');

                    Route::patch(
                        '/{image}/primary',
                        [ProductImageController::class, 'primary']
                    )
                        ->middleware('can:products.update')
                        ->name('primary');

                    Route::delete(
                        '/{image}',
                        [ProductImageController::class, 'destroy']
                    )
                        ->middleware('can:products.update')
                        ->name('destroy');

                });

            /*
            |--------------------------------------------------------------------------
            | Orders
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/orders',
                [OrderController::class, 'index']
            )->name('admin.orders.index');

            Route::get(
                '/orders/{order}',
                [OrderController::class, 'show']
            )->name('admin.orders.show');

            Route::patch(
                '/orders/{order}/status',
                [OrderController::class, 'updateStatus']
            )->name('admin.orders.status');

            Route::patch(
                '/orders/{order}/payment-status',
                [OrderController::class, 'updatePaymentStatus']
            )->name('admin.orders.payment-status');

            /*
            |--------------------------------------------------------------------------
            | Logout
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/logout',
                [AuthController::class, 'logout']
            )->name('logout');

        });

    });

/*
|--------------------------------------------------------------------------
| Storefront
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('storefront.home');

Route::get(
    '/shop',
    [ShopController::class, 'index']
)->name('storefront.shop');

Route::get(
    '/products/{slug}',
    [StorefrontProductController::class, 'show']
)->name('storefront.products.show');

/*
|--------------------------------------------------------------------------
| Cart
|--------------------------------------------------------------------------
*/

Route::get(
    '/cart',
    [CartController::class, 'index']
)->name('storefront.cart.index');

Route::post(
    '/cart',
    [CartController::class, 'store']
)->name('storefront.cart.store');

Route::patch(
    '/cart/{variantId}',
    [CartController::class, 'update']
)->name('storefront.cart.update');

Route::delete(
    '/cart/{variantId}',
    [CartController::class, 'destroy']
)->name('storefront.cart.destroy');

Route::delete(
    '/cart',
    [CartController::class, 'clear']
)->name('storefront.cart.clear');

/*
|--------------------------------------------------------------------------
| Checkout
|--------------------------------------------------------------------------
*/

Route::get(
    '/checkout',
    [CheckoutController::class, 'index']
)->name('storefront.checkout.index');

Route::post(
    '/checkout',
    [CheckoutController::class, 'store']
)->name('storefront.checkout.store');

Route::get(
    '/checkout/success/{order:order_number}',
    [CheckoutController::class, 'success']
)->name('storefront.checkout.success');

Route::get(
    '/checkout/shipping',
    [CheckoutController::class, 'shipping']
)->name('storefront.checkout.shipping');

/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
*/

Route::get(
    '/categories/{category:slug}',
    [StoreFrontCategoryController::class, 'show']
)->name('storefront.categories.show');

/*
|--------------------------------------------------------------------------
| Payment Assistance
|--------------------------------------------------------------------------
*/

Route::get(
    '/payment-assistance',
    [PaymentAssistanceController::class, 'create']
)->name('storefront.payment-assistance.create');

Route::post(
    '/payment-assistance',
    [PaymentAssistanceController::class, 'store']
)->name('storefront.payment-assistance.store');

Route::get(
    '/payment-assistance/success/{paymentAssistanceRequest}',
    [PaymentAssistanceController::class, 'success']
)->name('storefront.payment-assistance.success');

/*
|--------------------------------------------------------------------------
| Payment Initiation
|--------------------------------------------------------------------------
|
| Creates/initiates the payment transaction for an order.
|
|--------------------------------------------------------------------------
*/

Route::post(
    '/payment/{order}/initiate',
    [PaymentController::class, 'initiate']
)->name('storefront.payment.initiate');

/*
|--------------------------------------------------------------------------
| Bank Transfer Payment
|--------------------------------------------------------------------------
|
| IMPORTANT:
|
| The URL uses the PaymentTransaction's transaction_reference.
|
| Example:
|
| GET
| /payment/bank-transfer/PAY-20260816020508-QRLJPNDE
|
| POST
| /payment/bank-transfer/PAY-20260816020508-QRLJPNDE/submit
|
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Show Bank Transfer Page
|--------------------------------------------------------------------------
*/

Route::get(
    '/payment/bank-transfer/{transaction:transaction_reference}',
    [PaymentController::class, 'bankTransfer']
)->name('storefront.payment.bank-transfer');

/*
|--------------------------------------------------------------------------
| Submit Bank Transfer
|--------------------------------------------------------------------------
|
| This MUST remain POST because the form uploads payment proof.
|
|--------------------------------------------------------------------------
*/

Route::post(
    '/payment/bank-transfer/{transaction:transaction_reference}/submit',
    [PaymentController::class, 'submitBankTransfer']
)->name('storefront.payment.bank-transfer.submit');

/*
|--------------------------------------------------------------------------
| Bank Transfer Submission Confirmation
|--------------------------------------------------------------------------
|
| After successful POST submission, the controller should redirect here:
|
| /payment/bank-transfer/{transaction_reference}/submitted
|
| This page is GET because the customer is viewing a confirmation page.
|
|--------------------------------------------------------------------------
*/

Route::get(
    '/payment/bank-transfer/{transaction:transaction_reference}/submitted',
    [PaymentController::class, 'bankTransferSubmitted']
)->name('storefront.payment.bank-transfer.submitted');

/*
|--------------------------------------------------------------------------
| GET Safety Route For /submit
|--------------------------------------------------------------------------
|
| This is intentionally added as a safety net.
|
| If the browser accidentally navigates to:
|
| /payment/bank-transfer/PAY-XXXX/submit
|
| using GET, Laravel will NOT throw a 405 anymore.
|
| Instead, it will send the customer to the bank-transfer page.
|
| The actual submission is still handled ONLY by POST above.
|
|--------------------------------------------------------------------------
*/

Route::get(
    '/payment/bank-transfer/{transaction:transaction_reference}/submit',
    function ($transaction) {
        return redirect()->route(
            'storefront.payment.bank-transfer',
            ['transaction' => $transaction]
        );
    }
);
