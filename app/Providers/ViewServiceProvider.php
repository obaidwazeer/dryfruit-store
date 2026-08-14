<?php

namespace App\Providers;

use App\View\Composers\StorefrontHeaderComposer;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        view()->composer(
            'components.storefront.header',
            StorefrontHeaderComposer::class
        );

    }
}
