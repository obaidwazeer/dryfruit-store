@extends('layouts.storefront.app')

@section('title', 'Premium Dry Fruits')

@section('content')

    {{-- =========================================================
    Homepage Hero
========================================================== --}}

    <section class="storefront-hero">

        <div class="storefront-hero-background">

            <img src="{{ asset('assets/storefront/images/home/hero-dry-fruits.jpg') }}" alt="Premium quality dry fruits"
                class="storefront-hero-image">

        </div>

        <div class="storefront-hero-overlay"></div>

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

                        <div class="storefront-hero-actions">

                            <a href="{{ route('storefront.shop') }}" class="btn btn-storefront-primary">
                                Shop Now
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>

                            <a href="#categories" class="btn btn-storefront-secondary">
                                Explore Categories
                            </a>

                        </div>

                        <div class="storefront-hero-trust">

                            <div class="hero-trust-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Premium Quality</span>
                            </div>

                            <div class="hero-trust-item">
                                <i class="bi bi-truck"></i>
                                <span>Nationwide Delivery</span>
                            </div>

                            <div class="hero-trust-item">
                                <i class="bi bi-shield-check"></i>
                                <span>Secure Shopping</span>
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

    <section id="categories" class="storefront-categories section-padding">

        <div class="container">

            <div class="storefront-category-heading text-center">

                <span class="storefront-category-eyebrow">
                    Explore Our Collection
                </span>

                <h2 class="storefront-category-title">
                    Shop by Category
                </h2>

                <p class="storefront-category-description">
                    Discover carefully selected premium dry fruits
                    for every taste and occasion.
                </p>

            </div>

            <div class="row g-4">

                @forelse ($categories as $category)
                    <div class="col-6 col-md-4 col-lg-3">

                        <a href="{{ route('storefront.categories.show', $category->slug) }}" class="category-card">
                            <div class="category-card-image">

                                <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}"
                                    loading="lazy">

                                <div class="category-card-overlay"></div>

                            </div>

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


    {{-- =========================================================
    Featured Products
========================================================== --}}

    <section class="storefront-featured-products section-padding">

        <div class="container">

            {{-- Featured Header --}}
            <div class="featured-products-header">

                <div class="featured-products-heading">

                    <span class="storefront-section-eyebrow">
                        Our Selection
                    </span>

                    <h2 class="storefront-section-title">
                        Featured Products
                    </h2>

                    <p class="storefront-section-description">
                        Explore some of our most popular premium
                        dry fruits.
                    </p>

                </div>


                <div class="featured-products-action">

                    <a href="{{ route('storefront.shop', ['sort' => 'featured']) }}" class="storefront-section-link">
                        View All
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            {{-- Featured Products --}}
            @if ($featuredProducts->isNotEmpty())

                <div class="row g-4">

                    @foreach ($featuredProducts as $product)
                        <div class="col-6 col-md-4 col-lg-3">

                            <x-storefront.product-card :product="$product" />


                        </div>
                    @endforeach

                </div>
            @else
                <div class="featured-products-empty text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-box-seam fs-1 text-muted"></i>
                    </div>

                    <h5>
                        Featured products coming soon
                    </h5>

                    <p class="text-muted mb-0">
                        We are preparing our featured dry fruit collection.
                    </p>

                </div>

            @endif

        </div>

    </section>

    {{-- =========================================================
    Why Choose Us
========================================================== --}}

    <section class="storefront-home-section storefront-benefits">

        <div class="container">

            {{-- Section Heading --}}
            <div class="storefront-section-heading">

                <span class="storefront-section-eyebrow">
                    Why Choose Us
                </span>

                <h2>
                    Quality You Can Taste
                </h2>

                <p>
                    We focus on quality, freshness and a simple shopping
                    experience from selection to delivery.
                </p>

            </div>


            {{-- Benefits --}}
            <div class="row g-4">

                {{-- Benefit 1 --}}
                <div class="col-12 col-md-6 col-lg-3">

                    <div class="benefit-card">

                        <div class="benefit-card-icon">

                            <i class="bi bi-award"></i>

                        </div>

                        <h3 class="benefit-card-title">
                            Premium Quality
                        </h3>

                        <p class="benefit-card-description">
                            Carefully selected dry fruits with a focus
                            on quality, taste and freshness.
                        </p>

                    </div>

                </div>


                {{-- Benefit 2 --}}
                <div class="col-12 col-md-6 col-lg-3">

                    <div class="benefit-card">

                        <div class="benefit-card-icon">

                            <i class="bi bi-box-seam"></i>

                        </div>

                        <h3 class="benefit-card-title">
                            Freshly Packed
                        </h3>

                        <p class="benefit-card-description">
                            Our products are packed carefully to help
                            maintain their freshness and quality.
                        </p>

                    </div>

                </div>


                {{-- Benefit 3 --}}
                <div class="col-12 col-md-6 col-lg-3">

                    <div class="benefit-card">

                        <div class="benefit-card-icon">

                            <i class="bi bi-truck"></i>

                        </div>

                        <h3 class="benefit-card-title">
                            Nationwide Delivery
                        </h3>

                        <p class="benefit-card-description">
                            Get your favourite dry fruits delivered
                            conveniently across Pakistan.
                        </p>

                    </div>

                </div>


                {{-- Benefit 4 --}}
                <div class="col-12 col-md-6 col-lg-3">

                    <div class="benefit-card">

                        <div class="benefit-card-icon">

                            <i class="bi bi-shield-check"></i>

                        </div>

                        <h3 class="benefit-card-title">
                            Secure Shopping
                        </h3>

                        <p class="benefit-card-description">
                            Shop confidently with a simple and secure
                            checkout experience.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- =========================================================
    Promotional CTA
========================================================== --}}

    <section class="storefront-promo">

        <div class="container">

            <div class="storefront-promo-card">

                {{-- Background --}}
                <div class="storefront-promo-background"></div>

                {{-- Overlay --}}
                <div class="storefront-promo-overlay"></div>


                {{-- Content --}}
                <div class="storefront-promo-content">

                    <span class="storefront-promo-eyebrow">
                        Premium Selection
                    </span>


                    <h2 class="storefront-promo-title">
                        Premium Dry Fruits,
                        <span>Delivered Fresh.</span>
                    </h2>


                    <p class="storefront-promo-description">
                        Discover carefully selected almonds, cashews,
                        pistachios, walnuts, dates and more —
                        packed with care and delivered to your door.
                    </p>


                    <div class="storefront-promo-actions">

                        <a href="{{ route('storefront.shop') }}" class="btn btn-storefront-primary">
                            Shop All Products

                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>


                        <a href="{{ route('storefront.shop') }}" class="btn btn-storefront-secondary">
                            Explore Collection
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- =========================================================
    Customer Testimonials
========================================================== --}}

    <section class="storefront-testimonials">

        <div class="container">

            {{-- Section Heading --}}
            <div class="storefront-section-heading text-center">

                <span class="storefront-section-eyebrow">
                    Customer Love
                </span>

                <h2 class="storefront-section-title">
                    What Our Customers Say
                </h2>

                <p class="storefront-section-description">
                    We care about quality, freshness and making every order
                    a great experience.
                </p>

            </div>


            {{-- Testimonials --}}
            <div class="row g-4">

                {{-- Testimonial 1 --}}
                <div class="col-md-6 col-lg-4">

                    <article class="testimonial-card">

                        <div class="testimonial-rating">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>


                        <p class="testimonial-text">
                            "The almonds and cashews were fresh and tasted
                            excellent. The packaging was also very neat."
                        </p>


                        <div class="testimonial-customer">

                            <div class="testimonial-avatar">
                                A
                            </div>

                            <div>
                                <h3 class="testimonial-name">
                                    Ahmed
                                </h3>

                                <span class="testimonial-location">
                                    Islamabad
                                </span>
                            </div>

                        </div>

                    </article>

                </div>


                {{-- Testimonial 2 --}}
                <div class="col-md-6 col-lg-4">

                    <article class="testimonial-card">

                        <div class="testimonial-rating">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>


                        <p class="testimonial-text">
                            "I really liked the quality of the dry fruits.
                            Everything arrived fresh and properly packed."
                        </p>


                        <div class="testimonial-customer">

                            <div class="testimonial-avatar">
                                S
                            </div>

                            <div>
                                <h3 class="testimonial-name">
                                    Sara
                                </h3>

                                <span class="testimonial-location">
                                    Lahore
                                </span>
                            </div>

                        </div>

                    </article>

                </div>


                {{-- Testimonial 3 --}}
                <div class="col-md-6 col-lg-4">

                    <article class="testimonial-card">

                        <div class="testimonial-rating">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>


                        <p class="testimonial-text">
                            "Good quality, easy ordering and a smooth shopping
                            experience. I will definitely order again."
                        </p>


                        <div class="testimonial-customer">

                            <div class="testimonial-avatar">
                                M
                            </div>

                            <div>
                                <h3 class="testimonial-name">
                                    Muhammad
                                </h3>

                                <span class="testimonial-location">
                                    Rawalpindi
                                </span>
                            </div>

                        </div>

                    </article>

                </div>

            </div>

        </div>

    </section>

@endsection
