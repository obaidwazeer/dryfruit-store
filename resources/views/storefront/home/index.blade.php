@extends('layouts.storefront.app')

@section('title', 'Premium Dry Fruits')

@section('content')

    {{-- =========================================================
        Homepage Hero
    ========================================================== --}}

    <section class="storefront-hero">

        {{-- Hero Background --}}
        <div class="storefront-hero-background">

            <img src="{{ asset('assets/storefront/images/home/hero-dry-fruits.jpg') }}" alt="Premium quality dry fruits"
                class="storefront-hero-image">

        </div>


        {{-- Dark overlay for text readability --}}
        <div class="storefront-hero-overlay"></div>


        {{-- Hero Content --}}
        <div class="container position-relative">

            <div class="row align-items-center">

                <div class="col-lg-6">

                    <div class="storefront-hero-content">

                        <span class="storefront-hero-eyebrow">
                            Premium Quality Dry Fruits
                        </span>


                        <h1 class="storefront-hero-title">

                            Nature's Best,

                            <span>
                                Delivered Fresh.
                            </span>

                        </h1>


                        <p class="storefront-hero-description">

                            Discover premium almonds, cashews,
                            pistachios, walnuts, dates and more —
                            carefully selected for exceptional taste
                            and freshness.

                        </p>


                        {{-- Hero Actions --}}
                        <div class="storefront-hero-actions">

                            <a href="#" class="btn btn-storefront-primary">
                                Shop Now

                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>


                            <a href="#" class="btn btn-storefront-secondary">
                                Explore Categories
                            </a>

                        </div>


                        {{-- Trust Indicators --}}
                        <div class="storefront-hero-trust">


                            {{-- Premium Quality --}}
                            <div class="hero-trust-item">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>
                                    Premium Quality
                                </span>

                            </div>


                            {{-- Nationwide Delivery --}}
                            <div class="hero-trust-item">

                                <i class="bi bi-truck"></i>

                                <span>
                                    Nationwide Delivery
                                </span>

                            </div>


                            {{-- Secure Shopping --}}
                            <div class="hero-trust-item">

                                <i class="bi bi-shield-check"></i>

                                <span>
                                    Secure Shopping
                                </span>

                            </div>


                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>
    {{-- =========================================================
    Shop by Category
========================================================== --}}

    <section class="storefront-categories section-padding">

        <div class="container">

            {{-- Section Heading --}}
            <div class="storefront-section-heading text-center">

                <span class="storefront-section-eyebrow">
                    Explore Our Collection
                </span>

                <h2 class="storefront-section-title">
                    Shop by Category
                </h2>

                <p class="storefront-section-description">
                    Discover carefully selected premium dry fruits
                    for every taste and occasion.
                </p>

            </div>


            {{-- Categories --}}
            <div class="row g-4">

                @forelse ($categories as $category)
                    <div class="col-6 col-md-4 col-lg-3">

                        <a href="#" class="category-card">

                            {{-- Category Image --}}
                            <div class="category-card-image">

                                <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}"
                                    loading="lazy">

                                <div class="category-card-overlay"></div>

                            </div>


                            {{-- Category Information --}}
                            <div class="category-card-content">

                                <h3 class="category-card-title">
                                    {{ $category->name }}
                                </h3>

                                <span class="category-card-link">
                                    Shop Now

                                    <i class="bi bi-arrow-right"></i>
                                </span>

                            </div>

                        </a>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="text-center py-5">

                            <p class="mb-0 text-muted">
                                Categories will be available soon.
                            </p>

                        </div>

                    </div>
                @endforelse

            </div>

        </div>

    </section>

@endsection
