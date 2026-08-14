@extends('layouts.storefront.app')

@section('title', 'Order Confirmed - ' . config('app.name'))

@section('content')

    <div class="container py-5">

        <div class="text-center">

            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success fs-1"></i>
            </div>

            <h1 class="mb-3">
                Order Placed Successfully!
            </h1>

            <p class="text-muted">
                Thank you for shopping with us.
            </p>

            <div class="card mx-auto mt-4" style="max-width: 600px;">

                <div class="card-body">

                    <h5 class="mb-3">
                        Order Number
                    </h5>

                    <h3 class="text-success">
                        {{ $order->order_number }}
                    </h3>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            Rs. {{ number_format($order->subtotal, 2) }}
                        </strong>

                    </div>

                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Shipping
                        </span>

                        <strong>
                            Rs. {{ number_format($order->shipping_cost, 2) }}
                        </strong>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span>
                            Total
                        </span>

                        <strong>
                            Rs. {{ number_format($order->total, 2) }}
                        </strong>

                    </div>

                    <hr>

                    <p class="mb-1">
                        <strong>
                            Payment:
                        </strong>

                        Cash on Delivery
                    </p>

                    <p class="mb-0">
                        <strong>
                            Status:
                        </strong>

                        {{ ucfirst($order->status) }}
                    </p>

                </div>

            </div>

            <div class="mt-4">

                <a href="{{ route('storefront.shop') }}" class="btn btn-storefront-primary">
                    Continue Shopping
                </a>

            </div>

        </div>

    </div>

@endsection
